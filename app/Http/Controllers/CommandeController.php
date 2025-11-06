<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
public function store(Request $request) :JsonResponse
{
        $user = Auth::user(); // utilisateur connecté

        $commande = Commande::create([
            'user_id' => $user->id,
            'date_commande' => now(),
            'statut' => 'en attente'
        ]);

        // Ajouter les plats à la commande
        foreach ($request->plats as $plat) {
            $commande->plats()->attach($plat['plat_id'], ['quantite' => $plat['quantite']]);
        }

        return response()->json([
            'message' => 'Commande créée avec succès',
            'commande' => $commande->load('plats')
        ], 201);
    }


      public function calculecommande($id) : JsonResponse
      {
     $commande = Commande::find($id);
     $prixTotale = $commande->calculertotale();
 
return response()->json(['prixtotale'=> $prixTotale ]);
    }
    
    public function getCommandeServices($id): JsonResponse
    {
       $commande = Commande::find($id);
       $respnse = $commande->plats;

return response()->json(['plats'=> $respnse ]);
    }

    
}
