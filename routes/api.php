<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    require __DIR__.'/auth.php';
    require __DIR__.'/api/users.php';
    require __DIR__.'/api/products.php';
    require __DIR__.'/api/orders.php';
    require __DIR__.'/api/drivers.php';
    require __DIR__.'/api/payments.php';
    require __DIR__.'/api/analytics.php';
    require __DIR__.'/api/reports.php';
    require __DIR__.'/api/ratings.php';
    require __DIR__.'/api/addresses.php';
    require __DIR__.'/api/categories.php';
    require __DIR__.'/api/dashboard.php';

});