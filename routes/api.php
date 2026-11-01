<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MasterIncomeTypeController;
use App\Http\Controllers\MasterOutcomeCategoryController;
use App\Http\Controllers\MasterOutcomeDetailTagController;
use App\Http\Controllers\MasterOutcomeTypeController;
use App\Http\Controllers\MasterPaymentController;
use App\Http\Controllers\MasterPeriodController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\OutcomeDetailController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/google', [AuthController::class, 'handleGoogleAuth']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // --- PROJECT MANAGEMENT ---
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{project}/members', [ProjectController::class, 'addMember']);
    Route::delete('projects/{project}/members/{user}', [ProjectController::class, 'removeMember']);

    // --- PROJECT-SCOPED ROUTES ---
    Route::middleware('project')->group(function () {
        // --- MASTER DATA ---
        Route::apiResource('master-periods', MasterPeriodController::class);
        Route::apiResource('master-income-types', MasterIncomeTypeController::class);
        Route::apiResource('master-outcome-categories', MasterOutcomeCategoryController::class);
        Route::apiResource('master-outcome-types', MasterOutcomeTypeController::class);
        Route::apiResource('master-payments', MasterPaymentController::class);
        Route::apiResource('master-outcome-detail-tags', MasterOutcomeDetailTagController::class);

        // --- TRANSACTIONS: INCOMES ---
        Route::apiResource('incomes', IncomeController::class);

        // --- TRANSACTIONS: OUTCOMES ---
        Route::apiResource('outcomes', OutcomeController::class);

        // --- OUTCOME DETAILS ---
        Route::apiResource('outcome-details', OutcomeDetailController::class)->except(['show']);

        // --- ACTIVITY LOGS ---
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);

        // --- DASHBOARD ---
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // --- REPORT ---
        Route::get('/report/{period_id}', [ReportController::class, 'index']);
        Route::get('/report-range', [ReportController::class, 'range']);
    });

    // --- AUTH & ME ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});