<?php

use App\Http\Controllers\CommandeController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });


//plats
Route::get('/plats', [PlatController::class, 'index']);         // liste plats
Route::middleware(['auth:sanctum','isAdmin'])->post('/plats',[PlatController::class,'store']);   // ajouter plats 'ADMIN'
Route::get('/plats/{id}',[PlatController::class,'show']); // // détail plat
Route::middleware(['auth:sanctum','isAdmin'])->put('/plats/{id}',[PlatController::class,'update']); // modifier plat 'ADMIN'
Route::middleware(['auth:sanctum','isAdmin'])->delete('/plats/{id}',[PlatController::class,'destroy']); // suppprimer plat 'ADMIN'


//commandes
Route::post('/commande',[CommandeController::class,'store']); //ajoutez commande
Route::get('/commande/{id}/totale',[CommandeController::class,'calculecommande']); //calculecommande
Route::get('/commande/{id}/services',[CommandeController::class,'getCommandeServices']);

require __DIR__.'/auth.php';    

