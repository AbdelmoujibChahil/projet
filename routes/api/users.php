<?php
use App\Features\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api', 'admin'])->prefix('users')->group(function () {

    // ADMIN ONLY
    Route::get('/', [UserController::class, 'allUsers']);
    Route::post('/', [UserController::class, 'store']);

});

Route::middleware('auth:api')->group(function () {
    Route::get('/me', fn () => response()->json(auth()->user()));
    Route::put('/users/profile', [UserController::class, 'updateProfile']);
    Route::put('/users/password', [UserController::class, 'updatePassword']);
    Route::put('/users/phone', [UserController::class, 'updatePhone']);

});

 