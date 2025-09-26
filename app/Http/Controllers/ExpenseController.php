<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
     public function index()
    {
        // Fetch BOTH the list of expenses AND the list of categories
        $expenses = Expense::with('category')->latest()->get();
        $categories = ExpenseCategory::orderBy('name')->get(); // This is the new line

        // Pass both variables to the view
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create($request->all());
        return redirect()->route('expenses.index')->with('success', 'Expense logged successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }



    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense->update($request->all());
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}