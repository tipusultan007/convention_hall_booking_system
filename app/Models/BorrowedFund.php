<?php

namespace App\Models;

use App\Observers\BorrowedFundObserver;
use Illuminate\Database\Eloquent\Model;

class BorrowedFund extends Model
{
    protected $fillable = ['lender_id', 'amount_borrowed', 'date_borrowed', 'purpose', 'amount_repaid', 'due_amount', 'status'];

    protected $casts = [
      'date_borrowed' => 'date',
    ];
    protected static function booted(): void
    {
        static::observe(BorrowedFundObserver::class);
    }
    public function lender() {
        return $this->belongsTo(Lender::class);
    }
    public function repayments() {
        return $this->hasMany(FundRepayment::class);
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
