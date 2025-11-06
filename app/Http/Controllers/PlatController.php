<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlatController extends Controller
{
    // Afficher tous les plats
    public function index(): JsonResponse
    {
        return response()->json(Plat::all());
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

    // Supprimer un plat
    public function destroy($id)
    {
        Plat::destroy($id);
        return response()->json(['message' => 'Plat supprimé']);
    }
}