<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingEventController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BookingPaymentController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\IncomeCategoryController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\MonthlySalaryController;
use App\Http\Controllers\Api\SalaryPaymentController;
use App\Http\Controllers\Api\LenderController;
use App\Http\Controllers\Api\BorrowedFundController;
use App\Http\Controllers\Api\FundRepaymentController;
use App\Http\Controllers\Api\TransactionController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/booking-events', [BookingEventController::class, 'index']);

// Public API route for logging in
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected routes that require a valid token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard-stats', [DashboardController::class, 'index']); // <-- ADD THIS
    Route::apiResource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/payments', [BookingPaymentController::class, 'store']);

    // Expense Categories (for dropdowns)
    Route::get('/expense-categories', [ExpenseCategoryController::class, 'index']);

    // Expenses (List and Create)
    Route::apiResource('expenses', ExpenseController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('/income-categories', [IncomeCategoryController::class, 'index']);

    // Incomes (Full CRUD)
    Route::apiResource('incomes', IncomeController::class);

    // Worker Management

    Route::apiResource('workers', WorkerController::class);
    Route::post('/salaries/generate', [MonthlySalaryController::class, 'generate']);
    Route::apiResource('salaries', MonthlySalaryController::class)->only(['index', 'show']);
    Route::post('/salaries/{monthlySalary}/payments', [SalaryPaymentController::class, 'store']);
    Route::apiResource('lenders', LenderController::class);
    Route::apiResource('borrowed-funds', BorrowedFundController::class);
    Route::post('/borrowed-funds/{borrowedFund}/repayments', [FundRepaymentController::class, 'store']);

    Route::get('/transactions', [TransactionController::class, 'index']);



});
