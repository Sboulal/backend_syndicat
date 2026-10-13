<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    // جلب جميع الشقق (مع العمارة ديالهم والسكان)
    public function index()
    {
        $lots = Lot::with(['building', 'users'])->get();
        return response()->json($lots, 200);
    }

    // إضافة شقة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'building_id' => 'nullable|exists:buildings,id',
            'number' => 'required|string|max:50',
            'floor' => 'nullable|integer',
            'type' => 'required|in:appartement,commerce,cave,garage',
            'quote_part' => 'required|numeric|min:0',
        ]);

        $lot = Lot::create($request->all());

        return response()->json([
            'message' => 'Lot ajouté avec succès', 
            'lot' => $lot
        ], 201);
    }

    // عرض شقة وحدة (مع تفاصيلها: السكان والمساهمات اللي عليها)
    public function show($id)
    {
        $lot = Lot::with(['building', 'users', 'contributions'])->findOrFail($id);
        return response()->json($lot, 200);
    }

    // تعديل شقة
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'in:appartement,commerce,cave,garage',
            'quote_part' => 'numeric|min:0',
        ]);

        $lot = Lot::findOrFail($id);
        $lot->update($request->all());

        return response()->json([
            'message' => 'Lot modifié avec succès', 
            'lot' => $lot
        ], 200);
    }

    // مسح شقة
    public function destroy($id)
    {
        Lot::destroy($id);
        return response()->json(['message' => 'Lot supprimé avec succès'], 200);
    }
}