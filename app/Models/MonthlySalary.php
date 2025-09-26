<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    use HasFactory;
    protected $fillable = ['worker_id', 'salary_month', 'total_salary', 'paid_amount', 'due_amount', 'status'];

    protected $casts = [
        'salary_month' => 'date', // <-- THIS IS THE FIX
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function payments()
    {
        return $this->hasMany(SalaryPayment::class);
    }
}
