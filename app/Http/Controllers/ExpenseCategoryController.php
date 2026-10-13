<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        return response()->json(ExpenseCategory::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);

        $category = ExpenseCategory::create($request->all());

        return response()->json(['message' => 'Catégorie ajoutée', 'category' => $category], 201);
    }

    public function show($id)
    {
        return response()->json(ExpenseCategory::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->update($request->all());

        return response()->json(['message' => 'Catégorie modifiée', 'category' => $category], 200);
    }

    public function destroy($id)
    {
        ExpenseCategory::destroy($id);
        return response()->json(['message' => 'Catégorie supprimée'], 200);
    }
}