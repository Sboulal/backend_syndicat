<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    // جلب جميع الشكايات مع مولاها، الشقة، والإصلاح المرتبط بيها
    public function index()
    {
        $claims = Claim::with(['user', 'lot', 'intervention'])->get();
        return response()->json($claims, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // مول الشكاية
            'lot_id' => 'nullable|exists:lots,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,normal,urgent',
        ]);

        // الحالة كتمشي 'new' أوتوماتيكيا
        $claim = Claim::create($request->all());

        return response()->json(['message' => 'Réclamation enregistrée', 'claim' => $claim], 201);
    }

    public function show($id)
    {
        $claim = Claim::with(['user', 'lot', 'intervention', 'assignee'])->findOrFail($id);
        return response()->json($claim, 200);
    }

    public function update(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);
        $claim->update($request->all());

        return response()->json(['message' => 'Réclamation modifiée', 'claim' => $claim], 200);
    }

    // دالة خاصة باش السانديك يبدل الحالة ديال الشكاية (مثلا: "قيد المعالجة" أو "تم الحل")
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,waiting,resolved',
            'intervention_id' => 'nullable|exists:interventions,id', // باش نربطوها مع شي إصلاح
        ]);

        $claim = Claim::findOrFail($id);
        $claim->update([
            'status' => $request->status,
            'intervention_id' => $request->intervention_id ?? $claim->intervention_id
        ]);

        return response()->json(['message' => 'Statut de la réclamation mis à jour', 'claim' => $claim], 200);
    }

    public function destroy($id)
    {
        Claim::destroy($id);
        return response()->json(['message' => 'Réclamation supprimée'], 200);
    }
}