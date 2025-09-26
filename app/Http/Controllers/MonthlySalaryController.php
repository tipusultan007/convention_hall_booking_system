<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\MonthlySalary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlySalaryController extends Controller
{
    public function index()
    {
        $salaries = MonthlySalary::with('worker')->latest()->get();
        return view('salaries.index', compact('salaries'));
    }

    public function generate(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);
        
        $month = Carbon::parse($request->month)->startOfMonth();
        $activeWorkers = Worker::where('is_active', true)->get();

        DB::beginTransaction();
        try {
            foreach ($activeWorkers as $worker) {
                MonthlySalary::firstOrCreate(
                    [
                        'worker_id' => $worker->id,
                        'salary_month' => $month->toDateString(),
                    ],
                    [
                        'total_salary' => $worker->monthly_salary,
                        'due_amount' => $worker->monthly_salary,
                        'status' => 'Unpaid',
                    ]
                );
            }
            DB::commit();
            return back()->with('success', 'Salaries for ' . $month->format('F, Y') . ' generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate salaries. ' . $e->getMessage());
        }
    }
    
    public function show(MonthlySalary $monthlySalary)
    {
        $monthlySalary->load('worker', 'payments');
        return view('salaries.show', compact('monthlySalary'));
    }

      public function edit(MonthlySalary $monthlySalary)
    {
        $monthlySalary->load('worker');
        return view('salaries.edit', compact('monthlySalary'));
    }

    public function update(Request $request, MonthlySalary $monthlySalary)
    {
        $request->validate([
            'total_salary' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $monthlySalary) {
            $newTotalSalary = $request->total_salary;
            $paidAmount = $monthlySalary->paid_amount;
            $newDueAmount = $newTotalSalary - $paidAmount;

            $newStatus = 'Unpaid';
            if ($newDueAmount <= 0) {
                $newStatus = 'Paid';
            } elseif ($paidAmount > 0 && $newDueAmount > 0) {
                $newStatus = 'Partially Paid';
            }

            $monthlySalary->update([
                'total_salary' => $newTotalSalary,
                'due_amount' => $newDueAmount,
                'status' => $newStatus,
            ]);
        });

        return redirect()->route('salaries.index')->with('success', 'Salary record updated successfully.');
    }
}