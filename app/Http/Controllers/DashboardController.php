<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\User;
use App\Models\Contribution;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Claim;
use App\Models\Intervention;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getKpis(Request $request)
    {
        // 1. الفيلتر: إذا السانديك اختار شهر وعام، كنخدمو بيهم، وإلا كناخدو الشهر الحالي
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        // 2. إحصائيات عامة
        $totalLots = Lot::count();
        $totalOwners = User::where('role', 'owner')->count();

        // 3. إحصائيات المداخيل (Cotisations) بناءً على الشهر والعام
        $expectedContributions = Contribution::whereMonth('due_date', $month)
                                             ->whereYear('due_date', $year)
                                             ->sum('amount_due');

        $collectedContributions = Payment::where('status', 'validated')
                                         ->whereMonth('payment_date', $month)
                                         ->whereYear('payment_date', $year)
                                         ->sum('amount_paid');

        $remainingContributions = $expectedContributions - $collectedContributions;
        if ($remainingContributions < 0) $remainingContributions = 0;

        // 4. إحصائيات المصاريف (Dépenses) ديال هاد الشهر
        $periodExpenses = Expense::where('status', 'paid')
                                 ->whereMonth('payment_date', $month)
                                 ->whereYear('payment_date', $year)
                                 ->sum('amount');

        // 5. الخزينة (Trésorerie globale) = جميع المداخيل (من نهار بدات لابليكاسيون) ناقص جميع المصاريف
        $globalEncaissements = Payment::where('status', 'validated')->sum('amount_paid');
        $globalDecaissements = Expense::where('status', 'paid')->sum('amount');
        $soldeDisponible = $globalEncaissements - $globalDecaissements; // Solde Initial يعتبر 0 حاليا

        // 6. الشكايات والإصلاحات
        $openClaims = Claim::whereIn('status', ['new', 'in_progress'])->count();
        $upcomingInterventions = Intervention::whereIn('status', ['to_plan', 'planned'])->count();

        // 7. كنصيفطو هادشي كامل لـ Flutter فـ JSON واحد منظم
        return response()->json([
            'period' => [
                'month' => $month,
                'year' => $year
            ],
            'kpis' => [
                'total_lots' => $totalLots,
                'total_owners' => $totalOwners,
                'expected_contributions' => $expectedContributions,
                'collected_contributions' => $collectedContributions,
                'remaining_contributions' => $remainingContributions,
                'period_expenses' => $periodExpenses,
                'available_balance' => $soldeDisponible, // Solde disponible en banque et en caisse
                'open_claims' => $openClaims,
                'upcoming_interventions' => $upcomingInterventions,
            ]
        ], 200);
    }
}