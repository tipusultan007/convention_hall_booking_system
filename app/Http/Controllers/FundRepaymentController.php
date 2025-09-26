<?php

namespace App\Http\Controllers;

use App\Models\BorrowedFund;
use App\Models\FundRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundRepaymentController extends Controller
{
    public function store(Request $request, BorrowedFund $borrowedFund)
    {
        $request->validate([
            'repayment_amount' => 'required|numeric|min:0.01|max:' . $borrowedFund->due_amount,
            'repayment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $borrowedFund) {
            $borrowedFund->repayments()->create($request->all());

            $totalRepaid = $borrowedFund->repayments()->sum('repayment_amount');
            $dueAmount = $borrowedFund->amount_borrowed - $totalRepaid;
            $status = ($dueAmount <= 0) ? 'Repaid' : 'Partially Repaid';

            $borrowedFund->update([
                'amount_repaid' => $totalRepaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Repayment recorded successfully.');
    }

    public function destroy(FundRepayment $fundRepayment)
    {
        DB::transaction(function () use ($fundRepayment) {
            $borrowedFund = $fundRepayment->borrowedFund;
            $fundRepayment->delete();

            $totalRepaid = $borrowedFund->repayments()->sum('repayment_amount');
            $dueAmount = $borrowedFund->amount_borrowed - $totalRepaid;

            $status = 'Due';
            if ($dueAmount <= 0) {
                $status = 'Repaid';
            } elseif ($totalRepaid > 0) {
                $status = 'Partially Repaid';
            }

            $borrowedFund->update([
                'amount_repaid' => $totalRepaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Repayment record deleted successfully.');
    }
}
