<?php

namespace App\Http\Controllers;

use App\Models\BorrowedFund;
use App\Models\Lender;
use Illuminate\Http\Request;

class BorrowedFundController extends Controller
{
    public function index()
    {
        $borrowedFunds = BorrowedFund::with('lender')->latest()->get();
        $lenders = Lender::orderBy('name')->get();
        return view('borrowed-funds.index', compact('borrowedFunds', 'lenders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lender_id' => 'required|exists:lenders,id',
            'amount_borrowed' => 'required|numeric|min:1',
            'date_borrowed' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        BorrowedFund::create([
            'lender_id' => $request->lender_id,
            'amount_borrowed' => $request->amount_borrowed,
            'date_borrowed' => $request->date_borrowed,
            'purpose' => $request->purpose,
            'due_amount' => $request->amount_borrowed,
            'status' => 'Due',
        ]);

        return redirect()->route('borrowed-funds.index')->with('success', 'Fund record created successfully.');
    }

    public function show(BorrowedFund $borrowedFund)
    {
        $borrowedFund->load('lender', 'repayments');
        return view('borrowed-funds.show', compact('borrowedFund'));
    }

    public function edit(BorrowedFund $borrowedFund)
    {
        $lenders = Lender::orderBy('name')->get();
        return view('borrowed-funds.edit', compact('borrowedFund', 'lenders'));
    }

    public function update(Request $request, BorrowedFund $borrowedFund)
    {
        $request->validate([
            'lender_id' => 'required|exists:lenders,id',
            'amount_borrowed' => 'required|numeric|min:' . $borrowedFund->amount_repaid,
            'date_borrowed' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        $dueAmount = $request->amount_borrowed - $borrowedFund->amount_repaid;
        $status = $borrowedFund->status;
        if ($dueAmount <= 0) {
            $status = 'Repaid';
        }

        $borrowedFund->update([
            'lender_id' => $request->lender_id,
            'amount_borrowed' => $request->amount_borrowed,
            'date_borrowed' => $request->date_borrowed,
            'purpose' => $request->purpose,
            'due_amount' => $dueAmount,
            'status' => $status,
        ]);

        return redirect()->route('borrowed-funds.index')->with('success', 'Fund record updated successfully.');
    }

    public function destroy(BorrowedFund $borrowedFund)
    {
        if ($borrowedFund->repayments()->exists()) {
            return redirect()->route('borrowed-funds.index')->with('error', 'Cannot delete a record that has repayments.');
        }

        $borrowedFund->delete();
        return redirect()->route('borrowed-funds.index')->with('success', 'Fund record deleted successfully.');
    }
}
