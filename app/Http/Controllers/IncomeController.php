<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('category')->latest()->get();
        $categories = IncomeCategory::orderBy('name')->get();
        return view('incomes.index', compact('incomes', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'income_category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Income::create($request->all());
        return redirect()->route('incomes.index')->with('success', 'Income logged successfully.');
    }

    public function edit(Income $income)
    {
        $categories = IncomeCategory::orderBy('name')->get();
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, Income $income)
    {
        $request->validate([
            'income_category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $income->update($request->all());
        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }
}
