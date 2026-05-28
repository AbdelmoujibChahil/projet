<?php

use App\Features\DeliveryAddress\Controllers\DeliveryAdressController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('/addresses', [DeliveryAdressController::class, 'store']);
});