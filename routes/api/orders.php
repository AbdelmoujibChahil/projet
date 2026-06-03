<?php

use Illuminate\Support\Facades\Route;

use App\Features\Order\Controllers\OrderController;

/* USER ROUTES */

Route::middleware('auth:api')
    ->prefix('orders')
    ->group(function () {

        // Create order
        Route::post('/', [OrderController::class, 'store']);

        // Client orders
        Route::get('/client', [
            OrderController::class,
            'getClientOrders'
        ]);
        
        Route::patch('/{commande}',[
            OrderController::class,
            'updateStatus']);
    });

/* ADMIN ROUTES*/

Route::middleware(['auth:api', 'admin'])
    ->prefix('admin/orders')
    ->group(function () {

        // All orders
        Route::get('/', [
            OrderController::class,
            'index'
        ]);
        // Update status
        Route::patch('/{commande}', [
            OrderController::class,
            'updateStatus'
        ]);
        //Dashboard
        Route::get('/dashboard', [
            OrderController::class,
             'dashboard'
         ]);

    });