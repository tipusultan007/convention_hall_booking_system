<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'receipt_no',
        'event_type',
        'guests_count',
        'tables_count',
        'boys_count',
        'total_amount',
        'advance_amount',
        'due_amount',
        'status',
        'notes_in_words',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookingDates()
    {
        return $this->hasMany(BookingDate::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }
}
