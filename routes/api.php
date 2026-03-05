<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MasterIncomeTypeController;
use App\Http\Controllers\MasterOutcomeCategoryController;
use App\Http\Controllers\MasterOutcomeDetailTagController;
use App\Http\Controllers\MasterOutcomeTypeController;
use App\Http\Controllers\MasterPaymentController;
use App\Http\Controllers\MasterPeriodController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\OutcomeDetailController;
use Illuminate\Support\Facades\Route;


// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // --- MASTER DATA ---
    Route::apiResource('master-periods', MasterPeriodController::class);
    Route::apiResource('master-income-types', MasterIncomeTypeController::class);
    Route::apiResource('master-outcome-categories', MasterOutcomeCategoryController::class);
    Route::apiResource('master-outcome-types', MasterOutcomeTypeController::class);
    Route::apiResource('master-payments', MasterPaymentController::class);
    Route::apiResource('master-outcome-detail-tags', MasterOutcomeDetailTagController::class);

    // --- TRANSACTIONS: INCOMES ---
    Route::apiResource('incomes', IncomeController::class);
    // Route::post('incomes/{id}/restore', [IncomeController::class, 'restore']);
    // Route::delete('incomes/{id}/force', [IncomeController::class, 'forceDelete']);

    // --- TRANSACTIONS: OUTCOMES ---
    Route::apiResource('outcomes', OutcomeController::class);
    // Route::post('outcomes/{id}/restore', [OutcomeController::class, 'restore']);
    // Route::delete('outcomes/{id}/force', [OutcomeController::class, 'forceDelete']);

    // --- OUTCOME DETAILS ---
    // Biasanya diakses berdasarkan parent (misal: /outcomes/{id}/details)
    // Tapi apiResource mandiri juga oke kalau mau update item spesifik
    Route::apiResource('outcome-details', OutcomeDetailController::class)->except(['show']);
    // Route::apiResource('outcome-details', OutcomeDetailController::class)->except(['index', 'store']);
    // Route::post('outcomes/{outcome_id}/details', [OutcomeDetailController::class, 'store']); // Create detail buat parent tertentu

    // --- AUTH & ME ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});