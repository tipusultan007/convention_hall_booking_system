<?php

namespace App\Http\Controllers;

use App\Models\MonthlySalary;
use App\Models\SalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller
{
    public function store(Request $request, MonthlySalary $monthlySalary)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $monthlySalary->due_amount,
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $monthlySalary) {
            // 1. Create the payment record
            $monthlySalary->payments()->create($request->all());

            // 2. Recalculate and update the parent monthly_salary record
            $totalPaid = $monthlySalary->payments()->sum('payment_amount');
            $dueAmount = $monthlySalary->total_salary - $totalPaid;

            $status = 'Partially Paid';
            if ($dueAmount <= 0) {
                $status = 'Paid';
            }

            $monthlySalary->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function destroy(SalaryPayment $salaryPayment)
    {
        DB::transaction(function () use ($salaryPayment) {
            $monthlySalary = $salaryPayment->monthlySalary;
            $salaryPayment->delete(); // Delete the payment first

            // Recalculate and update the parent record
            $totalPaid = $monthlySalary->payments()->sum('payment_amount');
            $dueAmount = $monthlySalary->total_salary - $totalPaid;

            $status = 'Partially Paid';
            if ($dueAmount <= 0 && $totalPaid > 0) {
                 $status = 'Paid';
            } elseif ($totalPaid == 0) {
                 $status = 'Unpaid';
            }
            
            $monthlySalary->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });
        
        return back()->with('success', 'Payment record deleted successfully.');
    }

 public function edit(SalaryPayment $salaryPayment)
    {
        return view('salary_payments.edit', compact('salaryPayment'));
    }

    public function update(Request $request, SalaryPayment $salaryPayment)
    {
        $monthlySalary = $salaryPayment->monthlySalary;
        
        // The max validation must account for the original due amount + the amount of the payment being edited
        $maxAmount = $monthlySalary->due_amount + $salaryPayment->payment_amount;

        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $maxAmount,
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $salaryPayment, $monthlySalary) {
            // 1. Update the payment record itself
            $salaryPayment->update($request->all());

            // 2. Recalculate the parent monthly_salary record from scratch to ensure accuracy
            $totalPaid = $monthlySalary->payments()->sum('payment_amount');
            $dueAmount = $monthlySalary->total_salary - $totalPaid;

            $status = 'Unpaid';
            if ($dueAmount <= 0) {
                $status = 'Paid';
            } elseif ($totalPaid > 0) {
                $status = 'Partially Paid';
            }

            $monthlySalary->update([
                'paid_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return redirect()->route('salaries.show', $monthlySalary->id)->with('success', 'Payment updated successfully.');
    }

}