<?php

namespace App\Observers;

use App\Models\FundRepayment;

class FundRepaymentObserver
{
    /**
     * Handle the FundRepayment "created" event.
     */
    public function created(FundRepayment $fundRepayment): void
    {
        $fundRepayment->load('borrowedFund.lender');

        // Create a 'debit' transaction because money is going OUT of the business
        $fundRepayment->transaction()->create([
            'amount' => $fundRepayment->repayment_amount,
            'type' => 'debit',
            'transaction_date' => $fundRepayment->repayment_date,
            'description' => "Loan Repayment to " . ($fundRepayment->borrowedFund->lender->name ?? 'N/A') . " for: " . $fundRepayment->borrowedFund->purpose,
        ]);
    }

    /**
     * Handle the FundRepayment "deleted" event.
     */
    public function deleted(FundRepayment $fundRepayment): void
    {
        $fundRepayment->transaction()->delete();
    }
}
