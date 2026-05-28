<?php

use App\Features\Rating\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']);
});

Route::get('/ratings/{plat}/average', [RatingController::class, 'averageRating']);