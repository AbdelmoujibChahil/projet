<?php

namespace App\Features\Oauth\Controllers;

use App\Features\Oauth\Services\GoogleAuthService ;
use App\Http\Controllers\Controller;


class GoogleAuthController extends Controller
{

    //Redirection to Google
    public function redirect(GoogleAuthService $googleAuthService)
    {
        return $googleAuthService->redirectToGoogle();
    }

    // Google callback here
    public function callback(GoogleAuthService $googleAuthService)
    {
       return $googleAuthService->handleGoogleCallback();
    }
}
