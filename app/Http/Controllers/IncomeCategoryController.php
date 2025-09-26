<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::latest()->get();
        return view('income-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:income_categories,name|max:255']);
        IncomeCategory::create($request->all());
        return redirect()->route('income-categories.index')->with('success', 'Income category created.');
    }

    public function edit(IncomeCategory $incomeCategory)
    {
        return view('income-categories.edit', compact('incomeCategory'));
    }

    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $request->validate(['name' => 'required|string|unique:income_categories,name,' . $incomeCategory->id . '|max:255']);
        $incomeCategory->update($request->all());
        return redirect()->route('income-categories.index')->with('success', 'Income category updated.');
    }

    public function destroy(IncomeCategory $incomeCategory)
    {
        if ($incomeCategory->incomes()->exists()) {
            return back()->with('error', 'Cannot delete a category that has income records linked to it.');
        }
        $incomeCategory->delete();
        return redirect()->route('income-categories.index')->with('success', 'Income category deleted.');
    }
}
