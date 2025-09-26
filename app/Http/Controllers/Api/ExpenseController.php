<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExpenseResource;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('category')->latest()->paginate(20);
        return ExpenseResource::collection($expenses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $expense = Expense::create($validator->validated());
        
        // Load the relationship before returning the resource
        $expense->load('category');
        
        return new ExpenseResource($expense);
    }

    /**
     * Display the specified resource for editing.
     */
    public function show(Expense $expense)
    {
        // We can reuse the same resource for showing/editing
        $expense->load('category');
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->json()->all(), [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $expense->update($validator->validated());
        
        // Load the relationship before returning the updated resource
        $expense->load('category');
        
        return new ExpenseResource($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        
        // Return a standard success response with no content
        return response()->noContent();
    }
}