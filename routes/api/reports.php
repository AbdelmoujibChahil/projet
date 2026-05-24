<?php

use App\Features\Report\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::prefix('reports')->group(function () {

    Route::post('/', [
        ReportController::class,
        'store'
    ]);

    Route::middleware([
        'auth:api',
        'admin'
    ])->group(function () {

        Route::get('/', [
            ReportController::class,
            'index'
        ]);

        Route::get('/kpis', [
            ReportController::class,
            'kpis'
        ]);

        Route::get('/dashboard', [
            ReportController::class,
            'dashboard'
        ]);

        Route::patch('/{report}/read', [
            ReportController::class,
            'markAsRead'
        ]);

        Route::patch('/{report}/resolve', [
            ReportController::class,
            'markAsResolved'
        ]);  
        
        Route::delete('/{report}', [
            ReportController::class,
            'destroy'
        ]);
    });
});