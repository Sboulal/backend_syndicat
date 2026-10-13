<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    // جلب جميع العمارات
    public function index()
    {
        // كنجيبو العمارات وكنجيبو معاهم الشقق اللي تابعين ليهم
        $buildings = Building::with('lots')->get();
        return response()->json($buildings, 200);
    }

    // إضافة عمارة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $building = Building::create($request->all());

        return response()->json([
            'message' => 'Bâtiment ajouté avec succès', 
            'building' => $building
        ], 201);
    }

    // عرض عمارة وحدة
    public function show($id)
    {
        $building = Building::with('lots')->findOrFail($id);
        return response()->json($building, 200);
    }

    // تعديل عمارة
    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);
        $building->update($request->all());

        return response()->json([
            'message' => 'Bâtiment modifié avec succès', 
            'building' => $building
        ], 200);
    }

    // مسح عمارة
    public function destroy($id)
    {
        Building::destroy($id);
        return response()->json(['message' => 'Bâtiment supprimé avec succès'], 200);
    }
}