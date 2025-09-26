<?php

namespace App\Models;

use App\Observers\IncomeObserver;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['income_category_id', 'amount', 'income_date', 'description'];

    protected $casts = [
        'income_date' => 'date',
    ];
    protected static function booted(): void
    {
        static::observe(IncomeObserver::class);
    }
    public function category() {
        return $this->belongsTo(IncomeCategory::class, 'income_category_id');
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
