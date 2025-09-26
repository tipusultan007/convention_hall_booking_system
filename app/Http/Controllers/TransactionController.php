<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf; // Use the Snappy Facade

class TransactionController extends Controller
{
    /**
     * Display the general ledger with filtering and pagination.
     */
    public function index(Request $request)
    {
        // Get paginated data by default
        $data = $this->getTransactionData($request, true);
        return view('transactions.index', $data);
    }

    /**
     * Export the filtered general ledger as a PDF.
     */
    public function exportPDF(Request $request)
    {
        // Get ALL data (not paginated) for the PDF
        $data = $this->getTransactionData($request, false);

        $pdf = Pdf::loadView('transactions.pdf', $data)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', '10mm');

        $fileName = 'Transaction-Ledger-' . $data['startDate']->format('Y-m-d') . '-to-' . $data['endDate']->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * A private helper method to fetch, filter, and calculate transaction data.
     *
     * @param Request $request
     * @param bool $paginate    // New parameter to control pagination
     * @return array
     */
    private function getTransactionData(Request $request, bool $paginate = true): array
    {
        // Date filtering remains the same
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        $transactionsQuery = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        // Calculate the definitive opening balance (balance BEFORE the start date)
        $creditsBefore = Transaction::where('transaction_date', '<', $startDate)->where('type', 'credit')->sum('amount');
        $debitsBefore = Transaction::where('transaction_date', '<', $startDate)->where('type', 'debit')->sum('amount');
        $openingBalance = $creditsBefore - $debitsBefore;

        // Fetch the data, either paginated or all records
        $transactions = $paginate
            ? $transactionsQuery->paginate(25)->appends($request->query())
            : $transactionsQuery->get();

        // The collection to iterate over is different for paginated results
        $collection = $paginate ? $transactions->getCollection() : $transactions;

        // Calculate the running balance for each transaction
        $runningBalance = $openingBalance;
        $collection->transform(function ($transaction) use (&$runningBalance) {
            if ($transaction->type == 'credit') {
                $runningBalance += $transaction->amount;
            } else { // debit
                $runningBalance -= $transaction->amount;
            }
            $transaction->balance = $runningBalance; // Add the calculated balance to the object
            return $transaction;
        });

        // For the PDF, the closing balance is the final running balance.
        // For the web view, this value is less meaningful but we can calculate it.
        $closingBalance = $runningBalance;

        // If not paginating (i.e., for the PDF), we can calculate a true closing balance
        if (!$paginate) {
            $closingBalance = $openingBalance + $collection->where('type', 'credit')->sum('amount') - $collection->where('type', 'debit')->sum('amount');
        }

        return [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
        ];
    }
}
