<?php

use App\Features\Dashboard\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/stats', [DashboardController::class, 'getKpis']);
Route::get('/chart/revenue/{period}', [DashboardController::class, 'getRevenueTrends']);
Route::get('/getOrderDistribution/{period}', [DashboardController::class, 'getOrderDistribution']);