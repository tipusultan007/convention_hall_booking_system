<?php

namespace App\Models;

use App\Observers\SalaryPaymentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;
    protected $fillable = ['monthly_salary_id', 'payment_amount', 'payment_date', 'notes'];

    protected $casts = [
        'payment_date' => 'date',
    ];
    protected static function booted(): void
    {
        static::observe(SalaryPaymentObserver::class);
    }
    public function monthlySalary()
    {
        return $this->belongsTo(MonthlySalary::class);
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
