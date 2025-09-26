<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lender;
use App\Http\Resources\LenderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BorrowedFundResource; // Import this resource


class LenderController extends Controller
{
    public function index()
    {
        return LenderResource::collection(Lender::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|unique:lenders,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lender = Lender::create($validator->validated());
        return response(new LenderResource($lender), 201);
    }

    /**
     * Display the specified lender and all their associated fund records.
     */
    public function show(Lender $lender)
    {
        // Eager load the borrowedFunds relationship and order by the most recent
        $lender->load(['borrowedFunds' => function ($query) {
            $query->with('lender')->latest();
        }]);

        // We can reuse the BorrowedFundResource to format the list of loans
        $borrowedFunds = $lender->borrowedFunds;

        // Return the lender's details along with their loan history
        return response()->json([
            'lender' => [
                'id' => $lender->id,
                'name' => $lender->name,
                'contact_person' => $lender->contact_person,
                'phone' => $lender->phone,
                'notes' => $lender->notes,
            ],
            'history' => BorrowedFundResource::collection($borrowedFunds),
        ]);
    }

    public function update(Request $request, Lender $lender)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|unique:lenders,name,' . $lender->id . '|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lender->update($validator->validated());
        return new LenderResource($lender);
    }

    public function destroy(Lender $lender)
    {
        if ($lender->borrowedFunds()->exists()) {
            return response()->json(['message' => 'Cannot delete a lender with active fund records.'], 409); // 409 Conflict
        }

        $lender->delete();
        return response()->noContent();
    }
}