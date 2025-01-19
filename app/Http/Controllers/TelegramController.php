<?php

namespace App\Http\Controllers;

use App\Models\QrMove;
use App\Models\Steps;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use App\Models\QrCode;
use function Symfony\Component\String\b;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        $update = Telegram::getWebhookUpdate();
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'];
        $first_name = $request['message']['chat']['first_name'] ?? null;
        $user_name = $request['message']['chat']['username'] ?? null;
        $lastName = $update['message']['chat']['last_name'] ?? null;
        $text = $update['message']['text'] ?? null;

        // Foydalanuvchini tekshirish
        $user = User::query()->where('chat_id', $chatId)->first();
        $step = Steps::query()->where('chat_id', '=', $chatId)->first();

        if (!$step) {
            $step = new Steps();
            $step->chat_id = $chatId;
            $step->step = 'start';
            $step->save();
        }

        if (!$user) {
            $user = new User();
            $user->chat_id = $chatId;
            $user->name = $first_name;
            $user->last_name = $lastName;
            $user->user_name = $user_name;
            do {
                $email = Str::random(10) . '@example.com';
                $user->email = $email;
                $user->email_verified_at = now();
            } while (User::query()->where('email', $email)->exists());
            $user->save();
        }
        if ($text == '/start') {
                $this->sendMessage($chatId, "<b>Link uchun ism kiriting namuna:</b>\n Telegram,Youtube,Instagram ");
                $step->step = 'waiting_for_name';
                $step->save();

        } elseif ($step->step == 'waiting_for_name') {
            $step->name = $text;
            $step->step = 'waiting_for_link';
            $step->save();
            $this->sendMessage($chatId, "Link yuboring namuna:\n https://allanbalo.com");

        } elseif ($step->step == 'waiting_for_link') {
            if (isset($update['message']['entities']) && isset($update['message']['entities'][0]) && $update['message']['entities'][0]['type'] == 'url') {
                $step->link = $text;
                $step->step = 'done';
                $step->save();

                $response = $this->generateQrCode($user, $step->name, $step->link);

                $photoPath = public_path($response['qr_image']);

                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => "O'chirish 🚫", 'callback_data' => "delete:{$response['id']}"]),
                        Keyboard::inlineButton(['text' => "Bog'lanish soni 🔗", 'callback_data' => "show:{$response['id']}"]),
                    ]);

                $response = Telegram::sendPhoto([
                    'chat_id' => $chatId,
                    'photo' => InputFile::create($photoPath), // Rasmni yuborish uchun InputFile
                    'caption' => "\n<b>Link nomi: {$response['name']}</b>\n"
                        . "\n<b>Ko'rishlar soni: {$response['views']} ta</b>\n"
                        . "\n<b>Qisqartirilgan link: {$response['generated_link']}</b>",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $keyboard,

                ]);
                $word = "berilgan qisqa link yoki Qr kod orqali bog'langanlar soni haqida <b>Bog'lanish soni</b> tugmasi orqali xabar beramiz !\n"
                    ."\n"."tashrifingizdan xursandmiz ! \n"."web ilovamizda kutamiz : itap.uz";
                $this->sendMessage($chatId, $word);
                $step->delete();
            } else {
                $this->sendMessage($chatId, 'Iltimos linkni namunadagidek yuboring: https://allanbalo.com/allanbalo');
            }
        } elseif ($text != '/my_links' && $step->step == 'start' && empty($update['callback_query'])) {
            $this->sendMessage($chatId, 'Boshlash uchun /start ni bosing');
        } elseif ($text == '/my_links') {
            $qrs = QrCode::query()->where('user_id', $user->id)->get();

            $keyboard = Keyboard::make()->inline();
            foreach ($qrs as $qr) {
                $keyboard->row([
                    Keyboard::inlineButton(['text' => "$qr->qr_link", 'callback_data' => "mylinks:{$qr->id}"]),
                ]);
            }

            $response = Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "<b>Sizning qr kodlaringiz ro'yxati 📗</b>\n",
                'reply_markup' => $keyboard,
                'parse_mode' => 'HTML',
            ]);
        }
        // Qr Code ni delete qilish qismi
        if ($update->has('callback_query')) {
            $callbackData = $update['callback_query']['data'];
            [$action, $qrId] = explode(':', $callbackData);
            $messageId = $update['callback_query']['message']['message_id'];

            if ($action === 'delete') {
                $qr = QrCode::find($qrId);

                if ($qr) {
                    $remove = new QrMove();
                    $remove->message_id = $messageId;
                    $remove->qr_id = $qrId;
                    $remove->chat_id = $chatId;
                    $remove->save();

                    $message = "<b> '$qr->name' - </b> nomli qr kodni o'chirishni tasdiqlaysizmi ?\n";
                    $keyboard = Keyboard::make()
                        ->inline()
                        ->row([
                            Keyboard::inlineButton(['text' => "Ha 👍", 'callback_data' => "yes:{$messageId}"]),
                            Keyboard::inlineButton(['text' => "Yo'q 👎", 'callback_data' => "no:{$messageId}"]),
                        ]);

                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'HTML',
                        'reply_markup' => $keyboard,
                    ]);
                } else {
                    $this->sendMessage($chatId, 'QR kod topilmadi ! ⚠️');
                }
            } elseif ($action === 'show') {
                $qr = QrCode::query()->find($qrId);

                if ($qr) {
                    $word = "QR code nomi : <b> $qr->name </b>\n"."\nKo'rishlar soni : <b>$qr->views</b> ta";
                    $this->sendMessage($chatId, $word);
                } else {
                    $this->sendMessage($chatId, 'QR kod topilmadi! ⚠️');
                }
            } elseif ($action === 'yes'){
                $xabar = QrMove::query()->where('message_id', '=', $qrId)->first();
                $qr = QrCode::query()->find($xabar->qr_id);

               if ($qr && $xabar->chat_id == $chatId) {
                     $qr->delete();
                     Telegram::deleteMessage([
                         'chat_id' => $chatId,
                         'message_id' => $xabar->message_id,
                     ]);
                     Telegram::deleteMessage([
                         'chat_id' => $chatId,
                         'message_id' => $messageId,
                     ]);
                   $xabar->delete();
                   $this->sendMessage($chatId, 'QR kod muvaffaqiyatli o‘chirildi! ✅');
               } else {
                   $this->sendMessage($chatId, 'Noto\'g\'ri harakat ⚠️ keyinroq urinib koring');
               }

            } elseif ($action === 'no') {
                $xabarId = QrMove::query()->where('message_id', $qrId)->first();

                if ($xabarId) {
                    $xabarId->delete();
                    Telegram::deleteMessage([
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                    ]);
                }

            }elseif ($action === 'mylinks'){
                $qr = QrCode::query()->find($qrId);

                if (!$qr){
                    $this->sendMessage($chatId, 'Qr kod topilmadi ! ⚠️');
                }

                if ($qr) {
                $qr_image = public_path($qr->qr_image);

                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => "O'chirish 🚫", 'callback_data' => "delete:{$qr->id}"]),
                        Keyboard::inlineButton(['text' => "Bog'lanish soni 🔗", 'callback_data' => "show:{$qr->id}"]),
                    ]);

                    $response = Telegram::sendPhoto([
                        'chat_id' => $chatId,
                        'photo' => InputFile::create($qr_image),
                        'caption' => "\n<b>Link nomi: {$qr->name}</b>\n"
                            . "\n<b>Ko'rishlar soni: {$qr->views} ta</b>\n"
                            . "\n<b>Qisqartirilgan link:</b> \n{$qr->generated_link}",
                        'parse_mode' => 'HTML',
                        'reply_markup' => $keyboard,

                    ]);
                }

            } else {
                $this->sendMessage($chatId, 'Noma’lum operatsiya! ⚠️');
            }
        }
    }

    public function sendMessage($chatId, $text)
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function generateQrCode($user, $name, $link)
    {
        $qr = new QrCode();
        $qr->name = $name;
        $qr->qr_link = $link;
        $qr->qr_image = 'png';
        $qr->views = 0;
        $qr->user_id = $user->id;
        $qr->save();

        // QR kodni generatsiya qilish
        $data = route('qrcodes.scan', ['id' => $qr->id]);
        $qrCodeImage = QrCodeFacade::format('png')
            ->size(2000) // Katta o'lcham
            ->margin(3) // Minimal margin
            ->errorCorrection('H') // Yuqori aniqlikda tiklash darajasi
            ->generate($data);

        // Rasm uchun papka yaratish
        $qrImagesDir = public_path('qr_images');
        if (!file_exists($qrImagesDir)) {
            mkdir($qrImagesDir, 0755, true);
        }

        // Faylni saqlash
        $filename = time() . '.png';
        $filePath = $qrImagesDir . '/' . $filename;
        file_put_contents($filePath, $qrCodeImage);

        // QR kodni yangilash
        $qr->generated_link = $data;
        $qr->qr_image = 'qr_images/' . $filename;
        $qr->save();

        // Malumotni qaytarish
        return [
            'id'=> $qr->id,
            'name'=> $qr->name,
            'qr_image' => $qr->qr_image,
            'generated_link' => $qr->generated_link,
            'views' => $qr->views,
        ];
    }
}
