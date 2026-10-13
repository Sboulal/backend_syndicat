<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    // Afficher les réclamations
    public function index()
    {
        $reclamations = Reclamation::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($reclamations);
    }

    // Créer une réclamation (m3a photo dyal l'incident)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo_url')) {
            $path = $request->file('photo_url')->store('reclamations', 'public');
            $validatedData['photo_url'] = $path;
        }

        $reclamation = Reclamation::create($validatedData);

        return response()->json([
            'message' => 'Réclamation envoyée avec succès',
            'reclamation' => $reclamation
        ], 201);
    }

    // Le Syndic kaytb3 l'avancement dyal la réclamation
    public function updateStatus(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:jdida,en_cours,resolue'
        ]);

        $reclamation->update(['status' => $validatedData['status']]);

        return response()->json([
            'message' => 'Statut de la réclamation mis à jour',
            'reclamation' => $reclamation
        ]);
    }
}