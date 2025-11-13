<?php

use App\Http\Controllers\AdresseLivraisonController;
use App\Http\Controllers\CommandeController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
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
Route::get('/allusers',function(){
return User::all();
});

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
    


//plats
Route::get('/plats', [PlatController::class, 'index']);         // liste plats
Route::middleware(['auth:sanctum','isAdmin'])->post('/plats',[PlatController::class,'store']);   // ajouter plats 'ADMIN'
Route::get('/plats/{id}',[PlatController::class,'show']); // // détail plat
Route::middleware(['auth:sanctum','isAdmin'])->put('/plats/{id}',[PlatController::class,'update']); // modifier plat 'ADMIN'
Route::middleware(['auth:sanctum','isAdmin'])->delete('/plats/{id}',[PlatController::class,'destroy']); // suppprimer plat 'ADMIN'


//commandes
Route::post('/commande',[CommandeController::class,'store']); //ajoutez commande
Route::get('/commandes',[CommandeController::class,'getCommandeServices']);


//Adresse_Livraison
Route::middleware('auth:sanctum')->post('/adresse-livraison', [AdresseLivraisonController::class, 'store']);



require __DIR__.'/auth.php';

