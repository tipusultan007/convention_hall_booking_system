<?php

namespace App\Observers;

use App\Models\BorrowedFund;

class BorrowedFundObserver
{
    /**
     * Handle the BorrowedFund "created" event.
     */
    public function created(BorrowedFund $borrowedFund): void
    {
        $borrowedFund->load('lender');

        // Create a 'credit' transaction because money is coming IN to the business
        $borrowedFund->transaction()->create([
            'amount' => $borrowedFund->amount_borrowed,
            'type' => 'credit',
            'transaction_date' => $borrowedFund->date_borrowed,
            'description' => "Fund borrowed from " . ($borrowedFund->lender->name ?? 'N/A') . " for: " . $borrowedFund->purpose,
        ]);
    }

    /**
     * Handle the BorrowedFund "deleted" event.
     */
    public function deleted(BorrowedFund $borrowedFund): void
    {
        // Important: Also delete all repayment transactions before deleting the main transaction
        foreach ($borrowedFund->repayments as $repayment) {
            $repayment->transaction()->delete();
        }

        $borrowedFund->transaction()->delete();
    }
}
