<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowedFund;
use App\Http\Resources\BorrowedFundResource;
use App\Http\Resources\BorrowedFundDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowedFundController extends Controller
{
    public function index(Request $request)
    {
        $query = BorrowedFund::with('lender');

        // ** ADD THIS FILTERING LOGIC **
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date_borrowed', [$request->start_date, $request->end_date]);
        }
        if ($request->has('lender_id')) {
            $query->where('lender_id', $request->lender_id);
        }
        // ****************************

        $funds = $query->latest()->paginate(20);
        return BorrowedFundResource::collection($funds);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'lender_id' => 'required|exists:lenders,id',
            'amount_borrowed' => 'required|numeric|min:1',
            'date_borrowed' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $validatedData['due_amount'] = $validatedData['amount_borrowed'];
        $validatedData['status'] = 'Due';

        $borrowedFund = BorrowedFund::create($validatedData);
        $borrowedFund->load('lender'); // Load relationship for the resource

        return response(new BorrowedFundResource($borrowedFund), 201);
    }

    public function show(BorrowedFund $borrowedFund)
    {
        $borrowedFund->load('lender', 'repayments');
        return new BorrowedFundDetailResource($borrowedFund);
    }

    public function update(Request $request, BorrowedFund $borrowedFund)
    {
        $validator = Validator::make($request->json()->all(), [
            'lender_id' => 'required|exists:lenders,id',
            'amount_borrowed' => 'required|numeric|min:' . $borrowedFund->amount_repaid,
            'date_borrowed' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $dueAmount = $validatedData['amount_borrowed'] - $borrowedFund->amount_repaid;
        $status = ($dueAmount <= 0) ? 'Repaid' : $borrowedFund->status;

        $validatedData['due_amount'] = $dueAmount;
        $validatedData['status'] = $status;

        $borrowedFund->update($validatedData);
        $borrowedFund->load('lender');

        return new BorrowedFundResource($borrowedFund);
    }

    public function destroy(BorrowedFund $borrowedFund)
    {
        if ($borrowedFund->repayments()->exists()) {
            return response()->json(['message' => 'Cannot delete a record that has repayments.'], 409);
        }

        $borrowedFund->delete();
        return response()->noContent();
    }
}
