<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// استدعاء جميع الـ Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. رابط تسجيل الدخول (Public) - ما كيحتاجش Token
Route::post('/login', [AuthController::class, 'login']);

// ==============================================================================
// جميع الروابط اللي لتحت محمية (خاص ضروري Token من بعد ما يدير خينا Login)
// ==============================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- Authentification & Profil ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // --- Dashboard & KPIs ---
    Route::get('/dashboard', [DashboardController::class, 'getKpis']);

    // --- Copropriété (البنايات والشقق والسكان) ---
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('lots', LotController::class);
    Route::apiResource('users', UserController::class); // لتدبير السكان (Admin/Syndic)
    
    // --- Finances : Recettes (المساهمات والأداءات) ---
    Route::apiResource('contributions', ContributionController::class);
    Route::post('contributions/generate', [ContributionController::class, 'generateForLots']); // توليد المساهمات لجميع الشقق
    
    Route::apiResource('payments', PaymentController::class);
    Route::get('payments/{id}/receipt', [PaymentController::class, 'downloadReceipt']); // تحميل التوصيل
    Route::post('payments/{id}/validate', [PaymentController::class, 'validatePayment']); // فاليداسيون ديال الأداء (Syndic)

    // --- Finances : Dépenses (المصاريف) ---
    Route::apiResource('expense-categories', ExpenseCategoryController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::post('expenses/{id}/pay', [ExpenseController::class, 'pay']); // تغيير حالة المصروف إلى "مؤدى"

    // --- Fournisseurs & Entretien (الموردين والصيانة) ---
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('equipments', EquipmentController::class);
    Route::apiResource('interventions', InterventionController::class);
    Route::patch('interventions/{id}/status', [InterventionController::class, 'updateStatus']); // تغيير حالة الإصلاح

    // --- Réclamations (الشكايات) ---
    Route::apiResource('claims', ClaimController::class);
    Route::patch('claims/{id}/status', [ClaimController::class, 'updateStatus']);

    // --- Assemblées Générales (الجموع العامة) ---
    Route::apiResource('meetings', MeetingController::class);
    Route::apiResource('decisions', DecisionController::class);

    // --- Documents (الوثائق) ---
    Route::apiResource('documents', DocumentController::class)->except(['update']); // Upload & List
    
    // --- Rapports & Exports (التقارير) ---
    Route::get('reports/financial', [ExportController::class, 'exportFinancials']);
    Route::get('reports/unpaid', [ExportController::class, 'exportUnpaid']);

});