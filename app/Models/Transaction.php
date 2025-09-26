<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['amount', 'type', 'transaction_date', 'description', 'transactionable_id', 'transactionable_type'];

    protected $casts = [
      'transaction_date' => 'date',
    ];
    /**
     * Get the parent transactionable model (Expense, Income, etc.).
     */
    public function transactionable()
    {
        return $this->morphTo();
    }
}
