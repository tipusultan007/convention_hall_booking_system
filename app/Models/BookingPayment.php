<?php

namespace App\Models;

use App\Observers\BookingPaymentObserver;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    protected $fillable = ['booking_id', 'payment_amount', 'payment_date', 'payment_method', 'notes'];

    protected $casts = [
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::observe(BookingPaymentObserver::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function transaction() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
