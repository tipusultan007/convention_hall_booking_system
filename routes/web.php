<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\BorrowedFundController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FundRepaymentController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LenderController;
use App\Http\Controllers\MonthlySalaryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes for Authentication
require __DIR__ . '/auth.php';


// All application routes are protected by the 'auth' middleware
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view dashboard');


    // --- BOOKINGS & PAYMENTS ---
    Route::get('/bookings/{booking}/receipt-pdf', [BookingController::class, 'downloadReceiptPDF'])->name('bookings.receipt.pdf')->middleware('permission:download booking receipts');
    Route::post('bookings/{booking}/payments', [BookingPaymentController::class, 'store'])->name('bookings.payments.store')->middleware('permission:manage booking payments');
    Route::delete('booking-payments/{bookingPayment}', [BookingPaymentController::class, 'destroy'])->name('booking-payments.destroy')->middleware('permission:manage booking payments');
    Route::resource('bookings', BookingController::class)->middleware('permission:view bookings'); // Base permission


    // --- INCOME ---
    Route::resource('income-categories', IncomeCategoryController::class)->except(['show'])->middleware('permission:manage income categories');
    Route::resource('incomes', IncomeController::class)->except(['show'])->middleware('permission:view income');


    // --- EXPENSES ---
    Route::resource('expense-categories', ExpenseCategoryController::class)->except(['show'])->middleware('permission:manage expense categories');
    Route::resource('expenses', ExpenseController::class)->except(['show'])->middleware('permission:view expenses');


    // --- HR (WORKERS & SALARIES) ---
    Route::resource('workers', WorkerController::class)->middleware('permission:view workers');

    Route::prefix('salaries')->name('salaries.')->middleware('permission:view salaries')->group(function () {
        Route::get('/', [MonthlySalaryController::class, 'index'])->name('index');
        Route::get('/{monthlySalary}', [MonthlySalaryController::class, 'show'])->name('show');

        // Actions requiring higher permissions
        Route::post('/generate', [MonthlySalaryController::class, 'generate'])->name('generate')->middleware('permission:generate salaries');
        Route::get('/{monthlySalary}/edit', [MonthlySalaryController::class, 'edit'])->name('edit')->middleware('permission:manage salary payments');
        Route::put('/{monthlySalary}', [MonthlySalaryController::class, 'update'])->name('update')->middleware('permission:manage salary payments');
    });

    Route::prefix('salary-payments')->name('salary-payments.')->middleware('permission:manage salary payments')->group(function () {
        Route::post('/{monthlySalary}/payments', [SalaryPaymentController::class, 'store'])->name('payments.store'); // Note: This route was duplicated, corrected here.
        Route::get('/{salaryPayment}/edit', [SalaryPaymentController::class, 'edit'])->name('edit');
        Route::put('/{salaryPayment}', [SalaryPaymentController::class, 'update'])->name('update');
        Route::delete('/{salaryPayment}', [SalaryPaymentController::class, 'destroy'])->name('destroy');
    });


    // --- LIABILITIES ---
    Route::resource('lenders', LenderController::class)->except(['show'])->middleware('permission:manage lenders');
    Route::resource('borrowed-funds', BorrowedFundController::class)->middleware('permission:view liabilities');
    Route::post('borrowed-funds/{borrowedFund}/repayments', [FundRepaymentController::class, 'store'])->name('borrowed-funds.repayments.store')->middleware('permission:manage liability repayments');
    Route::delete('fund-repayments/{fundRepayment}', [FundRepaymentController::class, 'destroy'])->name('fund-repayments.destroy')->middleware('permission:manage liability repayments');


    // --- REPORTS & LEDGER ---
    Route::prefix('reports')->name('reports.')->middleware('permission:view reports')->group(function () {
        Route::get('/profit-loss', [ReportController::class, 'profitAndLoss'])->name('profit_loss');
        Route::get('/profit-loss/pdf', [ReportController::class, 'exportProfitAndLossPDF'])->name('profit_loss.pdf');
        Route::get('/expense-by-category', [ReportController::class, 'expenseReport'])->name('expense_by_category');
    });

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index')->middleware('permission:view ledger');
    Route::get('/transactions/pdf', [TransactionController::class, 'exportPDF'])->name('transactions.pdf')->middleware('permission:view ledger');

    Route::resource('users', UserController::class)->middleware('permission:manage users');
});
