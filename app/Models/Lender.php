<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lender extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'notes'];

    public function borrowedFunds() {
        return $this->hasMany(BorrowedFund::class);
    }
}
