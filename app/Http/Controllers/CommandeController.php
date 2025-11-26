<?php

namespace App\Http\Controllers;
use App\Models\AdresseLivraison;
use App\Models\Plat;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
public function store(Request $request): JsonResponse
{
    $user = Auth::user();

    // 1. Validation
    $validated = $request->validate([
        'adresse_livraison_id' => 'required|exists:adresses_livraison,id',
          'paymentMethod' => 'required|string',
        'plats' => 'required|array|min:1',
        'plats.*.plat_id' => 'required|exists:plats,id',
        'plats.*.quantite' => 'required|integer|min:1'
    ]);

    // 2. Récupérer l'adresse (obligatoire !)
    $adresse = AdresseLivraison::findOrFail($validated['adresse_livraison_id']);

    // 3. Calcul du total
    $total = 0;

    foreach ($validated['plats'] as $item) {
        $plat = Plat::findOrFail($item['plat_id']);
        $total += $plat->prix * $item['quantite'];
    }

    $deliveryFee = 10;
    $totalFinal = $total + $deliveryFee;

    // 4. Créer la commande
    $commande = Commande::create([
        'user_id' => $user->id,
        'adresse_livraison_id' => $adresse->id,
        'prix_total' => $totalFinal,
         'paymentMethod' => $validated['paymentMethod'], 
        'statut' => 'en attente',
        'date_commande' => now()
    ]);

    // 5. Ajouter les plats dans la table pivot
    foreach ($validated['plats'] as $plat) {
        $commande->plats()->attach($plat['plat_id'], [
            'quantite' => $plat['quantite']
        ]);
    }

    // 6. Retour API
    return response()->json([
        'message' => 'Commande créée avec succès',
        'id'=>$commande->id,
        'commande' => $commande->load('plats', 'adresseLivraison')
    ], 201);
}




    public function updateStatus(Request $request, $id)
    {
        // Validation
        $request->validate([
            'statut' => 'required|string' 
        ]);
        // Récupérer la commande
        $commande = Commande::findOrFail($id);

        // Mettre à jour uniquement le champ status
        $commande->statut = $request->statut;
        $commande->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès',
            'commande' => $commande
        ]);
    }



    
    public function getCommandeUsers(): JsonResponse
    {
       $commande = Commande::with(['plats','user'])->get();
return response()->json($commande);
    }


public function getCommandeClient(): JsonResponse
{
     $user_id= auth()->user()->id;
    $commande = Commande::where('user_id', $user_id)
                        ->with('plats')
                        ->get();

    return response()->json($commande);
}


   


}
