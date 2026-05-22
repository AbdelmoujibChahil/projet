<?php

use Illuminate\Support\Facades\Route;

use App\Features\Product\Controllers\ProductController;

Route::prefix('products')->group(function () {

    // Get all products
    Route::get('/', [ProductController::class, 'index']);

    // Get single product
    Route::get('/{plat}', [ProductController::class, 'show']);

});
Route::middleware(['auth:api', 'admin'])
    ->prefix('admin/products')
    ->group(function () {
        // Create product
        Route::post('/', [ProductController::class, 'store']);

        // Update product
        Route::put('/{plat}', [ProductController::class, 'update']);

        // Delete product
        Route::delete('/{plat}', [ProductController::class, 'destroy']);
    });