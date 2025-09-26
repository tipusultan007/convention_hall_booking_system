<?php

namespace App\Models;

use App\Observers\ExpenseObserver;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
     protected $fillable = [
        'expense_category_id',
        'amount',
        'expense_date',
        'description',
    ];

     protected $casts = [
         'expense_date' => 'date',
     ];
    protected static function booted(): void
    {
        static::observe(ExpenseObserver::class);
    }
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
