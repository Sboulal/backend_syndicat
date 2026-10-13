<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        return response()->json($equipments, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // بحال: مصعد الجناح أ
            'location' => 'nullable|string|max:255',
        ]);

        $equipment = Equipment::create($request->all());

        return response()->json(['message' => 'Équipement ajouté', 'equipment' => $equipment], 201);
    }

    public function show($id)
    {
        // كنجيبو المعدة وكنجيبو معاها كاع الإصلاحات اللي دازو فيها باش السانديك يعرف واش كتخسر بزاف
        $equipment = Equipment::with('interventions')->findOrFail($id);
        return response()->json($equipment, 200);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());

        return response()->json(['message' => 'Équipement modifié', 'equipment' => $equipment], 200);
    }

    public function destroy($id)
    {
        Equipment::destroy($id);
        return response()->json(['message' => 'Équipement supprimé'], 200);
    }
}