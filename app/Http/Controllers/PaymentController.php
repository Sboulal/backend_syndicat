<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('contribution.lot')->get();
        return response()->json($payments, 200);
    }

    // تسجيل الدفع (من طرف السكان أو السانديك)
    public function store(Request $request)
    {
        $request->validate([
            'contribution_id' => 'required|exists:contributions,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:virement,especes,cheque',
        ]);

        // توليد رقم توصيل فريد (مثال: REC-20261012-ABC12)
        $receiptNumber = 'REC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        $payment = Payment::create(array_merge($request->all(), [
            'receipt_number' => $receiptNumber,
            'status' => 'pending_validation' // كيبقى يتسنى السانديك يڤاليديه
        ]));

        return response()->json(['message' => 'Paiement enregistré', 'payment' => $payment], 201);
    }

    // تأكيد الدفع من طرف السانديك
    public function validatePayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'validated') {
            return response()->json(['message' => 'Paiement déjà validé'], 400);
        }

        $payment->update(['status' => 'validated']);

        // تحديث حالة المساهمة (Appel de fond)
        $contribution = Contribution::findOrFail($payment->contribution_id);
        
        // حساب المجموع ديال كاع الأداءات اللي مڤاليديا فهاد المساهمة
        $totalPaid = Payment::where('contribution_id', $contribution->id)
                            ->where('status', 'validated')
                            ->sum('amount_paid');

        if ($totalPaid >= $contribution->amount_due) {
            $contribution->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $contribution->update(['status' => 'partial']);
        }

        return response()->json([
            'message' => 'Paiement validé avec succès',
            'contribution_status' => $contribution->status
        ], 200);
    }

    // جلب معلومات التوصيل
    public function downloadReceipt($id)
    {
        $payment = Payment::with('contribution.lot.users')->findOrFail($id);

        if ($payment->status !== 'validated') {
            return response()->json(['message' => 'Reçu non disponible pour un paiement non validé'], 403);
        }

        // فالمستقبل نقدرو نزيدو باكيج DomPDF باش يرجع PDF حقيقي، دابا غنرجعو الداتا
        return response()->json([
            'message' => 'Reçu généré',
            'receipt' => $payment
        ], 200);
    }
}