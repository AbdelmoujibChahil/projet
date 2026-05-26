<?php

use App\Features\Driver\Controllers\DriverController;
use Illuminate\Support\Facades\Route;
 
/*   AUTH USERS    */
    Route::middleware('auth:api')->prefix('drivers')->group(function () {

        Route::get(
            '/me/deliveries',
            [DriverController::class, 'myDeliveries']
        );

        Route::get(
            '/available',
            [DriverController::class, 'getAvailableDrivers']
        );
    });

 /*   ADMIN    */
    Route::middleware(['auth:api', 'admin'])->prefix('drivers')->group(function () {

        Route::get('/dashboard', [DriverController::class, 'dashboard']);

        Route::get('/', [DriverController::class, 'index']);
        Route::post('/', [DriverController::class, 'store']);

        Route::get('/{driver}', [DriverController::class, 'show']);
        Route::put('/{driver}', [DriverController::class, 'update']);
        Route::delete('/{driver}', [DriverController::class, 'destroy']);

        Route::patch('/{driver}/status', [DriverController::class, 'updateStatus']);

        Route::post('/assign', [DriverController::class, 'assignToOrder']);
    
});