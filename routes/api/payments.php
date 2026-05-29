<?php

use Illuminate\Support\Facades\Route;
use App\Features\Payment\Controllers\PaymentController;

Route::prefix('payments')
    ->middleware('auth:api')
    ->group(function () {

        Route::post(
            '/cash',
            [PaymentController::class, 'cash']
        );

        Route::post(
            '/stripe/intent',
            [PaymentController::class, 'createStripeIntent']
        );

        Route::post(
            '/paypal/verify',
            [PaymentController::class, 'verifyPaypal']
        );
    });