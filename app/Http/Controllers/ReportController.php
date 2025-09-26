<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Expense;
use App\Models\SalaryPayment;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf;

class ReportController extends Controller
{
    /**
     * Display the Profit & Loss report in HTML format.
     */
    public function profitAndLoss(Request $request)
    {
        $data = $this->getReportData($request); // <-- Get data from our new helper method
        return view('reports.profit_loss', $data);
    }

    /**
     * Export the Profit & Loss report as a PDF file.
     */
    public function exportProfitAndLossPDF(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('reports.profit_loss_pdf', $data)
            ->setOption('enable-local-file-access', true)
            ->setPaper('a4', 'landscape'); // Landscape is better for the two-column layout

        $fileName = 'Profit-Loss-Report-' . $data['startDate']->format('Y-m-d') . '-to-' . $data['endDate']->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * A private helper method to fetch and calculate all report data
     * using the central transactions table as the single source of truth.
     *
     * @param Request $request
     * @return array
     */
    /* private function getReportData(Request $request): array
    {
        // Date filtering logic remains the same
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        // --- CALCULATIONS (Now much simpler and more accurate) ---

        // 1. Calculate Total Revenue
        $totalRevenue = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'credit')
            ->sum('amount');

        // 2. Calculate Total Expenses
        $totalExpenses = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'debit')
            ->sum('amount');

        // 3. Calculate Net Profit
        $netProfit = $totalRevenue - $totalExpenses;

        // --- FETCH DETAILS FOR BREAKDOWN TABLES ---

        // Fetch all transactions in the period and eager-load their parent models
        $allTransactions = Transaction::with('transactionable')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest('transaction_date')
            ->get();

        // Now, we filter this single collection to create our breakdowns.
        // This is far more efficient than running multiple separate queries.
        $bookingDetails = $allTransactions->where('transactionable_type', 'App\Models\BookingPayment');
        $otherIncomeDetails = $allTransactions->where('transactionable_type', 'App\Models\Income');
        $expensesDetails = $allTransactions->where('transactionable_type', 'App\Models\Expense');
        $salaryPaymentsDetails = $allTransactions->where('transactionable_type', 'App\Models\SalaryPayment');

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'bookingDetails' => $bookingDetails,
            'otherIncomeDetails' => $otherIncomeDetails,
            'expensesDetails' => $expensesDetails,
            'salaryPaymentsDetails' => $salaryPaymentsDetails,
        ];
    } */

          private function getReportData(Request $request): array
    {
        // Date filtering logic remains the same
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        // --- CALCULATIONS (WITH CORRECTIONS) ---
        
        // 1. **THE FIX**: Calculate Total Revenue based on payments received in the period
        $bookingPaymentsRevenue = BookingPayment::whereBetween('payment_date', [$startDate, $endDate])->sum('payment_amount');
        $otherIncome = Income::whereBetween('income_date', [$startDate, $endDate])->sum('amount');
        $totalRevenue = $bookingPaymentsRevenue + $otherIncome;

        // 2. Calculate Total Expenses (This logic is already correct)
        $generalExpensesSum = \App\Models\Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $salaryPaymentsSum = \App\Models\SalaryPayment::whereBetween('payment_date', [$startDate, $endDate])->sum('payment_amount');
        $totalExpenses = $generalExpensesSum + $salaryPaymentsSum;

        // 3. Calculate Net Profit (This is now accurate)
        $netProfit = $totalRevenue - $totalExpenses;

        // --- FETCH DETAILS FOR BREAKDOWN TABLES ---
        
        // **THE FIX**: Fetch BookingPayment details instead of Booking details
        $bookingPaymentDetails = BookingPayment::with('booking.customer')
                                ->whereBetween('payment_date', [$startDate, $endDate])
                                ->latest('payment_date')
                                ->get();
        
        // The rest of the detail fetching is correct
        $otherIncomeDetails = Income::with('category')->whereBetween('income_date', [$startDate, $endDate])->latest('income_date')->get();
        $expensesDetails = \App\Models\Expense::with('category')->whereBetween('expense_date', [$startDate, $endDate])->latest('expense_date')->get();
        $salaryPaymentsDetails = \App\Models\SalaryPayment::with('monthlySalary.worker')->whereBetween('payment_date', [$startDate, $endDate])->latest('payment_date')->get();
        
        return [
            'startDate' => $startDate, 'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'bookingPaymentDetails' => $bookingPaymentDetails, // <-- Pass new data to the view
            'otherIncomeDetails' => $otherIncomeDetails,
            'expensesDetails' => $expensesDetails,
            'salaryPaymentsDetails' => $salaryPaymentsDetails,
        ];
    }
}
