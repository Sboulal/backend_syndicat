<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppartementController extends Controller
{
    // 1. Afficher ga3 les appartements
    public function index()
    {
        // 'with' katjib lina hta les informations dyal l'moul dar (proprietaire)
        $appartements = Appartement::with('proprietaire')->get();
        return response()->json($appartements);
    }

    // 2. Ajouter appartement jdid
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero' => 'required|string',
            'etage' => 'required|integer',
            'bloc' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $appartement = Appartement::create($validatedData);

        return response()->json([
            'message' => 'Appartement ajouté avec succès',
            'appartement' => $appartement
        ], 201);
    }

    // 3. Afficher les détails dyal appartement wa7ed
    public function show($id)
    {
        // 'findOrFail' ila mal9atouch kat3ti erreur 404
        $appartement = Appartement::with(['proprietaire', 'paiements'])->findOrFail($id);
        return response()->json($appartement);
    }

    // 4. Modifier appartement
    public function update(Request $request, $id)
    {
        $appartement = Appartement::findOrFail($id);

        $validatedData = $request->validate([
            'numero' => 'sometimes|string',
            'etage' => 'sometimes|integer',
            'bloc' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $appartement->update($validatedData);

        return response()->json([
            'message' => 'Appartement modifié avec succès',
            'appartement' => $appartement
        ]);
    }

    // 5. Supprimer appartement
    public function destroy($id)
    {
        $appartement = Appartement::findOrFail($id);
        $appartement->delete();

        return response()->json([
            'message' => 'Appartement supprimé avec succès'
        ]);
    }
}