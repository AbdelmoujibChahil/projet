<?php

use App\Http\Controllers\AdresseLivraisonController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\paypalVerify;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;

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
        return  $request->user();
    });
//ALL USERS
Route::get('/allusers',[UserController::class,'allusers']);

//Modification d un USER
Route::put('/modifier/{id}',function($id,Request $request){
    $User = User::FindorFail($id);
 $validated= $request->validate([
      'name' => [ 'string', 'max:255'],
      'email' => [ 'string', 'email', 'max:255', 'unique:users,email,'.$User->id],
      //'unique:table,column,except_id' REGLES 
     'password' => [ 'nullable',Password::defaults()],
     ]);
      // Si le mot de passe est fourni, on le hache
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        // Sinon, on le supprime du tableau pour ne pas écraser l'ancien mot de passe
        unset($validated['password']);
    }
 $User->update($validated);
 return response()->json(['message'=> 'modification reussite',
'User' => $User,
]);
}); 
    
 Route::patch('/modifier/{id}/username', [UserController::class, 'updatename']); //modifier Username
 Route::patch('/modifier/{id}/email', [UserController::class, 'updateemail']); //modifier email
 Route::patch('/modifier-password', [UserController::class, 'updatepassword']); //modifier password
 Route::patch('/modifier-phone', [UserController::class, 'updatePhone']); //modifier phone



//plats
Route::get('/plats', [PlatController::class, 'index']);         // liste plats
Route::get('/plats/{id}',[PlatController::class,'show']); // // détail plat

Route::post('/plats/{id}/review', [PlatController::class, 'incrementReviewCount']);


//commandes
Route::post('/commande',[CommandeController::class,'store']); //ajoutez commande
Route::get('/commandes',[CommandeController::class,'getCommandeServices']);//ADMIN
Route::patch('/commande/{id}',[CommandeController::class,'updateStatus']); //modifier status

// Ce groupe nécessite d'être connecté ET d'avoir le rôle 'admin'
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

Route::put('/plats/{id}',[PlatController::class,'update']); // modifier plat 'ADMIN'
Route::delete('/plats/{id}',[PlatController::class,'destroy']); // suppprimer plat 'ADMIN'
Route::post('/plats',[PlatController::class,'store']);   // ajouter plats 'ADMIN'
Route::get('/commande-client', [CommandeController::class, 'getCommandeUsers']);//recuperer les commandes avec les plats d un client
Route::get('/categories', [CategoryController::class, 'getCategories']);//recuperer les CATEGORIES

    // 1. Route pour les cartes de statistiques (KPIs)
    Route::get('/stats', [DashboardController::class, 'getKpis']);
   
    // 2. Route pour les données de graphique de revenus
    // Le paramètre {period} sera '7days', '30days', etc.
    Route::get('/chart/revenue/{period}', [DashboardController::class, 'getRevenueTrends']);
 Route::get('/getOrderDistribution/{period}', [DashboardController::class, 'getOrderDistribution']);
});

Route::post('commandes/{id}/payment', [PaymentController::class, 'store']);



//Adresse_Livraison
Route::middleware('auth:sanctum')->post('/adresse-livraison', [AdresseLivraisonController::class, 'store']);

//Payment
use App\Http\Controllers\StripeController;

Route::post('/payment-intent', [StripeController::class, 'createPaymentIntent']);
//----PAYPAL----
Route::post('/paypal/verify', [paypalVerify::class, 'paypalVerify']);
 

//RATINGS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rating', [RatingController::class, 'store']);
});
Route::get('/items/{id}/rating', [RatingController::class, 'averages']);


require __DIR__.'/auth.php';

