<?php

namespace App\Observers;
use App\Models\Income;

class IncomeObserver
{
    public function created(Income $income): void
    {
        $income->transaction()->create([
            'amount' => $income->amount,
            'type' => 'credit',
            'transaction_date' => $income->income_date,
            'description' => "Other Income: " . ($income->category->name ?? '') . " - " . $income->description,
        ]);
    }

    public function deleted(Income $income): void
    {
        $income->transaction()->delete();
    }
}
