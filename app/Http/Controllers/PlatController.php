<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Rating;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlatController extends Controller
{
    // Afficher tous les plats
  public function index()
{
    $plats = Plat::all()->map(function($plat) {
        $avg = Rating::where('plat_id', $plat->id)->avg('rating');
        $count = Rating::where('plat_id', $plat->id)->count();
        return [
            'id' => $plat->id,
            'nom' => $plat->nom,
            'prix' => $plat->prix,
            'image' => $plat->image,
            'rating' => round($avg ?? 0, 1),
            'review_count' => $count,
        ];
    });

    return response()->json($plats);
}


    // Ajouter un plat
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prix' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|string|max:255',
    ]);
        $plat = Plat::create($validated);
        return response()->json( 
            ['message'=>'plat ajoute avec succes',
            'plat'=> $plat], 201);
    }

    // Afficher un plat
    public function show($id)
    {
        return response()->json(Plat::findOrFail($id));
    }

    // Modifier un plat
    public function update(Request $request, $id)
    {
        $plat = Plat::findOrFail($id);
        $plat->update($request->all());
        return response()->json( 
            ['message'=>'plat modifie avec succes',
            'plat'=> $plat]);
    }
   
    public function incrementReviewCount($id){
 $plat  =  plat::find($id);
$plat->review_count += 1;
    $plat->save();

    return response()->json([
        'message' => 'Review count updated',
        'review_count' => $plat->review_count
]);    }

    // Supprimer un plat
    public function destroy($id)
    {
        Plat::destroy($id);
        return response()->json(['message' => 'Plat supprimé']);
    }
}