<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
    public function index()
    {
        $decisions = Decision::with(['meeting', 'assignee'])->get();
        return response()->json($decisions, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id', // شكون مكلف بتنفيذ القرار
            'deadline_date' => 'nullable|date',
        ]);

        $decision = Decision::create($request->all());

        return response()->json(['message' => 'Décision ajoutée', 'decision' => $decision], 201);
    }

    public function show($id)
    {
        $decision = Decision::with(['meeting', 'assignee'])->findOrFail($id);
        return response()->json($decision, 200);
    }

    public function update(Request $request, $id)
    {
        $decision = Decision::findOrFail($id);
        $decision->update($request->all());

        return response()->json(['message' => 'Décision mise à jour', 'decision' => $decision], 200);
    }

    public function destroy($id)
    {
        Decision::destroy($id);
        return response()->json(['message' => 'Décision supprimée'], 200);
    }
}