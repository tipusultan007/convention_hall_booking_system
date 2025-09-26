<?php

namespace App\Observers;

use App\Models\SalaryPayment;

class SalaryPaymentObserver
{
    /**
     * Handle the SalaryPayment "created" event.
     */
    public function created(SalaryPayment $salaryPayment): void
    {
        $salaryPayment->load('monthlySalary.worker');

        // Create a 'debit' transaction because money is going OUT
        $salaryPayment->transaction()->create([
            'amount' => $salaryPayment->payment_amount,
            'type' => 'debit',
            'transaction_date' => $salaryPayment->payment_date,
            'description' => "Salary Payment to " . ($salaryPayment->monthlySalary->worker->name ?? 'N/A') . " for " . ($salaryPayment->monthlySalary->salary_month->format('F, Y') ?? 'N/A'),
        ]);
    }

    /**
     * Handle the SalaryPayment "deleted" event.
     */
    public function deleted(SalaryPayment $salaryPayment): void
    {
        $salaryPayment->transaction()->delete();
    }
}
