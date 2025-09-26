<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::latest()->get();
        return view('expense_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:expense_categories|max:255']);
        ExpenseCategory::create($request->all());
        return redirect()->route('expense-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate(['name' => 'required|string|unique:expense_categories,name,' . $expenseCategory->id . '|max:255']);
        $expenseCategory->update($request->all());
        return redirect()->route('expense-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Add a check to prevent deleting a category that's in use
        if ($expenseCategory->expenses()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has expenses linked to it.');
        }
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Category deleted successfully.');
    }
}