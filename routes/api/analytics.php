
<?php

use App\Features\Analytics\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('analytics')->group(function () {

    Route::get('/stats', [AnalyticsController::class, 'stats']);
    Route::get('/revenue-trends', [AnalyticsController::class, 'revenueTrends']);
    Route::get('/top-categories', [AnalyticsController::class, 'topCategories']);
    Route::get('/payment-methods', [AnalyticsController::class, 'paymentMethods']);
    Route::get('/top-products', [AnalyticsController::class, 'topProducts']);
    Route::get('/peak-hours', [AnalyticsController::class, 'peakHours']);
    Route::get('/customer-metrics', [AnalyticsController::class, 'customerMetrics']);

});