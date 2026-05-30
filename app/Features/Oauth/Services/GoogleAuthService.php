<?php

namespace App\Features\Oauth\Services;

use App\Models\User;
use Laravel\Socialite\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class GoogleAuthService
{
    //Redirection to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback here
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        //search user
        $user = User::where('email', $googleUser->getEmail())->first();

        // Create user if not exists
        if (!$user) {
            $user = User::create([
           'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
            ]);
        }

        //  generate JWT
        $token = JWTAuth::fromUser($user);

        return redirect("http://localhost:3000/login-success?token=$token");
    }
}