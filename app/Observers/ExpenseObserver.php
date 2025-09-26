<?php

namespace App\Observers;
use App\Models\Expense;

class ExpenseObserver
{
    public function created(Expense $expense): void
    {
        $expense->transaction()->create([
            'amount' => $expense->amount,
            'type' => 'debit',
            'transaction_date' => $expense->expense_date,
            'description' => "General Expense: " . ($expense->category->name ?? '') . " - " . $expense->description,
        ]);
    }

    public function deleted(Expense $expense): void
    {
        $expense->transaction()->delete();
    }
}
