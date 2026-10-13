<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaiementController extends Controller
{
    // Afficher ga3 les paiements (Pour le Syndic)
    public function index()
    {
        $paiements = Paiement::with('appartement.proprietaire')->get();
        return response()->json($paiements);
    }

    // Copropriétaire kaydéclarer paiement jdid (m3a upload dyal reçu)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'montant' => 'required|numeric',
            'mois_concerne' => 'required|date',
            'appartement_id' => 'required|exists:appartements,id',
            'preuve_image' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048' // Tswira ola PDF
        ]);

        // Logic dyal l'upload dyal tswira f dossier 'public/paiements'
        if ($request->hasFile('preuve_image')) {
            $path = $request->file('preuve_image')->store('paiements', 'public');
            $validatedData['preuve_image'] = $path;
        }

        $validatedData['status'] = 'en_attente'; // Par défaut

        $paiement = Paiement::create($validatedData);

        return response()->json([
            'message' => 'Paiement déclaré avec succès',
            'paiement' => $paiement
        ], 201);
    }

    // Le Syndic kayvalider ola kayrejeter l'paiement
    public function changerStatus(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:valide,rejete'
        ]);

        $paiement->update(['status' => $validatedData['status']]);

        // Hna n9dro nzidou logic bach n'génériw wa7ed reçu PDF ila kan 'valide'

        return response()->json([
            'message' => 'Statut du paiement mis à jour',
            'paiement' => $paiement
        ]);
    }
}