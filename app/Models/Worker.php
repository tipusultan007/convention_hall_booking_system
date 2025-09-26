<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'designation', 'monthly_salary', 'is_active'];

    public function monthlySalaries()
    {
        return $this->hasMany(MonthlySalary::class);
    }
}