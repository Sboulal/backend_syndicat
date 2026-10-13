<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function index()
    {
        $interventions = Intervention::with(['equipment', 'supplier'])->get();
        return response()->json($interventions, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'nullable|exists:equipments,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'subject' => 'required|string|max:255',
            'scheduled_date' => 'nullable|date',
            'priority' => 'required|in:low,normal,urgent',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        $intervention = Intervention::create($request->all());

        return response()->json(['message' => 'Intervention programmée', 'intervention' => $intervention], 201);
    }

    public function show($id)
    {
        $intervention = Intervention::with(['equipment', 'supplier', 'claims'])->findOrFail($id);
        return response()->json($intervention, 200);
    }

    public function update(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->update($request->all());

        return response()->json(['message' => 'Intervention modifiée', 'intervention' => $intervention], 200);
    }

    // دالة خاصة بتغيير حالة الإصلاح (Status)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:to_plan,planned,in_progress,done',
            'real_cost' => 'nullable|numeric|min:0', // ملي يسالي الإصلاح نقدروا ندخلو التكلفة الحقيقية
            'realized_date' => 'nullable|date',
        ]);

        $intervention = Intervention::findOrFail($id);
        
        $dataToUpdate = ['status' => $request->status];
        
        // إذا كان الإصلاح كمل (done)، كنسجلو التكلفة الحقيقية والتاريخ
        if ($request->status === 'done') {
            $dataToUpdate['real_cost'] = $request->real_cost ?? $intervention->estimated_cost;
            $dataToUpdate['realized_date'] = $request->realized_date ?? now();
        }

        $intervention->update($dataToUpdate);

        return response()->json(['message' => 'Statut mis à jour', 'intervention' => $intervention], 200);
    }

    public function destroy($id)
    {
        Intervention::destroy($id);
        return response()->json(['message' => 'Intervention supprimée'], 200);
    }
}