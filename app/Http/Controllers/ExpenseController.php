<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // جلب المصاريف مع الفئة والمورد
    public function index()
    {
        $expenses = Expense::with(['category', 'supplier'])->get();
        return response()->json($expenses, 200);
    }

    // تسجيل مصروف جديد (فاتورة يالاه وصلات وباقي ماتخلصاتش)
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        // بار ديفو، الفاتورة كتسجل status ديالها 'to_pay'
        $expense = Expense::create($request->all());

        return response()->json(['message' => 'Dépense enregistrée', 'expense' => $expense], 201);
    }

    public function show($id)
    {
        $expense = Expense::with(['category', 'supplier'])->findOrFail($id);
        return response()->json($expense, 200);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return response()->json(['message' => 'Dépense modifiée', 'expense' => $expense], 200);
    }

    // دالة خاصة بأداء الفاتورة (باش تنقص من الطوطال ديال الفلوس المتاحة)
    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:virement,especes,cheque',
            'payment_date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);

        if ($expense->status === 'paid') {
            return response()->json(['message' => 'Cette dépense est déjà payée'], 400);
        }

        $expense->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
        ]);

        return response()->json(['message' => 'Dépense marquée comme payée', 'expense' => $expense], 200);
    }

    public function destroy($id)
    {
        Expense::destroy($id);
        return response()->json(['message' => 'Dépense supprimée'], 200);
    }
}