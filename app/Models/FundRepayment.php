<?php

namespace App\Models;

use App\Observers\FundRepaymentObserver;
use Illuminate\Database\Eloquent\Model;

class FundRepayment extends Model
{
    protected $fillable = ['borrowed_fund_id', 'repayment_amount', 'repayment_date', 'notes'];

    protected $casts = [
        'repayment_date' => 'date',
    ];
    protected static function booted(): void
    {
        static::observe(FundRepaymentObserver::class);
    }
    public function borrowedFund() {
        return $this->belongsTo(BorrowedFund::class);
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
