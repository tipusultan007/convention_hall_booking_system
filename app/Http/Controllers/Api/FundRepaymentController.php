<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowedFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BorrowedFundDetailResource;

class FundRepaymentController extends Controller
{
    public function store(Request $request, BorrowedFund $borrowedFund)
    {
        $validator = Validator::make($request->json()->all(), [
            'repayment_amount' => 'required|numeric|min:0.01|max:' . $borrowedFund->due_amount,
            'repayment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::transaction(function () use ($validator, $borrowedFund) {
            $borrowedFund->repayments()->create($validator->validated());

            $totalRepaid = $borrowedFund->repayments()->sum('repayment_amount');
            $dueAmount = $borrowedFund->amount_borrowed - $totalRepaid;
            $status = ($dueAmount <= 0) ? 'Repaid' : 'Partially Repaid';

            $borrowedFund->update([
                'amount_repaid' => $totalRepaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        // Return the full, updated details so the Flutter app can refresh its state
        $borrowedFund->load('lender', 'repayments');
        return new BorrowedFundDetailResource($borrowedFund);
    }
}