<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {

        try {

            $googleUser = Socialite::driver('google')->stateless()->user();


            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken ?? null,
                ]);

                $user->roles()->sync([2]);
            }

            Auth::login($user);


            return redirect()->route('admin.qrcodes.index');

        }catch (Exception $e) {
            return redirect()->route('login')->withErrors(['google_login' => 'Failed to authenticate with Google.']);
        }

    }
}
