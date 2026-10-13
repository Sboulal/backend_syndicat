<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Lot;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    // جلب جميع المساهمات مع الشقق ديالهم والأداءات
    public function index()
    {
        $contributions = Contribution::with(['lot', 'payments'])->get();
        return response()->json($contributions, 200);
    }

    // إضافة مساهمة لشقة وحدة
    public function store(Request $request)
    {
        $request->validate([
            'lot_id' => 'required|exists:lots,id',
            'title' => 'required|string|max:255',
            'amount_due' => 'required|numeric|min:1',
            'due_date' => 'required|date',
        ]);

        $contribution = Contribution::create($request->all());

        return response()->json(['message' => 'Contribution ajoutée', 'contribution' => $contribution], 201);
    }

    // عرض مساهمة وحدة
    public function show($id)
    {
        $contribution = Contribution::with(['lot', 'payments'])->findOrFail($id);
        return response()->json($contribution, 200);
    }

    // توليد مساهمات لجميع الشقق دفعة واحدة (مهمة للسانديك)
    public function generateForLots(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'amount_due' => 'required|numeric|min:1', // يقدر يكون مبلغ موحد، أو نخدمو بـ quote_part من بعد
            'due_date' => 'required|date',
        ]);

        $lots = Lot::all();
        $contributions = [];

        foreach ($lots as $lot) {
            $contributions[] = Contribution::create([
                'lot_id' => $lot->id,
                'title' => $request->title . ' - ' . $lot->number,
                'amount_due' => $request->amount_due,
                'due_date' => $request->due_date,
                'status' => 'unpaid'
            ]);
        }

        return response()->json(['message' => count($contributions) . ' contributions générées avec succès'], 201);
    }

    public function destroy($id)
    {
        Contribution::destroy($id);
        return response()->json(['message' => 'Contribution supprimée'], 200);
    }
}