<?php

namespace App\Http\Controllers;

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
        $chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'] ?? null;
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
                $this->sendMessage($chatId, "<b>Link uchun ism kiriting misol:</b>\n Telegram,Youtube,Instagram ");
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
                        Keyboard::inlineButton(['text' => "O'chirish", 'callback_data' => "delete:{$response['id']}"]),
                        Keyboard::inlineButton(['text' => "Ko'rishlar soni", 'callback_data' => "show:{$response['id']}"]),
                    ]);

                $response = Telegram::sendPhoto([
                    'chat_id' => $chatId,
                    'photo' => InputFile::create($photoPath), // Rasmni yuborish uchun InputFile
                    'caption' => "\n<b>Link nomi: {$response['name']}</b>\n"
                        . "\n<b>Ko'rishlar soni: {$response['views']}</b>\n"
                        . "\n<b>Qisqartirilgan link: {$response['generated_link']}</b>",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $keyboard,

                ]);
                $word = "berilgan qisqa link yoki Qr kod orqali o'tishlar soni haqida <b>ko'rishlar soni</b> tugmasi orqali xabar beramiz !\n"
                    ."\n"."tashrifingizdan xursandmiz ! \n"."web ilovamizda kutamiz : itap.uz";
                $this->sendMessage($chatId, $word);
                $step->delete();
            } else {
                $this->sendMessage($chatId, 'Iltimos linkni namunadagidek yuboring: https://allanbalo.com/allanbalo');
            }
        } elseif ($step->step == 'start' && empty($update['callback_query'])) {
            $this->sendMessage($chatId, 'Boshlash uchun /start ni bosing');
        }
        // Qr Code ni delete qilish qismi
        if ($update->has('callback_query')) {
            $callbackData = $update['callback_query']['data'];
            [$action, $qrId] = explode(':', $callbackData);

            if ($action === 'delete') {
                $messageId = $update['callback_query']['message']['message_id'];
                $qr = QrCode::find($qrId);

                if ($qr) {
                    $qr->delete();
                    Telegram::deleteMessage([
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                    ]);
                    $this->sendMessage($chatId, 'QR kod muvaffaqiyatli o‘chirildi! ✅');
                } else {
                    $this->sendMessage($chatId, 'QR kod topilmadi yoki allaqachon o‘chirib tashlangan! ⚠️');
                }
            } elseif ($action === 'show') {
                $qr = QrCode::query()->find($qrId);

                $word = "QR code nomi : <b> $qr->name </b>\n"."\n Ko'rishlar soni: <b>$qr->views</b> ta";
                if ($qr) {
                    $this->sendMessage($chatId, $word);
                } else {
                    $this->sendMessage($chatId, 'QR kod topilmadi! ⚠️');
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
