<?php

namespace App\Http\Controllers;

use App\Models\Lender;
use Illuminate\Http\Request;

class LenderController extends Controller
{
    public function index()
    {
        $lenders = Lender::latest()->get();
        return view('lenders.index', compact('lenders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lenders,name|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        Lender::create($request->all());
        return redirect()->route('lenders.index')->with('success', 'Lender added successfully.');
    }

    public function edit(Lender $lender)
    {
        return view('lenders.edit', compact('lender'));
    }

    public function update(Request $request, Lender $lender)
    {
        $request->validate([
            'name' => 'required|string|unique:lenders,name,' . $lender->id . '|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $lender->update($request->all());
        return redirect()->route('lenders.index')->with('success', 'Lender updated successfully.');
    }

    public function destroy(Lender $lender)
    {
        if ($lender->borrowedFunds()->exists()) {
            return redirect()->route('lenders.index')->with('error', 'Cannot delete a lender with active fund records.');
        }

        $lender->delete();
        return redirect()->route('lenders.index')->with('success', 'Lender deleted successfully.');
    }
}
