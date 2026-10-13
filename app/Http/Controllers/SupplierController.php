<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'activity' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json(['message' => 'Fournisseur ajouté', 'supplier' => $supplier], 201);
    }

    // ملي نبغيو نشوفو المورد، كنجيبو معاه كاع الإصلاحات والفاتورات اللي درنا معاه (Historique)
    public function show($id)
    {
        $supplier = Supplier::with(['interventions', 'expenses'])->findOrFail($id);
        return response()->json($supplier, 200);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return response()->json(['message' => 'Fournisseur modifié', 'supplier' => $supplier], 200);
    }

    public function destroy($id)
    {
        Supplier::destroy($id);
        return response()->json(['message' => 'Fournisseur supprimé'], 200);
    }
}