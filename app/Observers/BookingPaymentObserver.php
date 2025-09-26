<?php

namespace App\Observers;

use App\Models\BookingPayment;

class BookingPaymentObserver
{
    /**
     * Handle the BookingPayment "created" event.
     */
    public function created(BookingPayment $bookingPayment): void
    {
        // Eager load the relationship to prevent extra queries
        $bookingPayment->load('booking.customer');

        // Create a 'credit' transaction because money is coming IN
        $bookingPayment->transaction()->create([
            'amount' => $bookingPayment->payment_amount,
            'type' => 'credit',
            'transaction_date' => $bookingPayment->payment_date,
            'description' => "Booking Payment received from " . ($bookingPayment->booking->customer->name ?? 'N/A') . " (Booking ID: #{$bookingPayment->booking_id})",
        ]);
    }

    /**
     * Handle the BookingPayment "deleted" event.
     */
    public function deleted(BookingPayment $bookingPayment): void
    {
        // When a payment is deleted, the corresponding central transaction must also be deleted.
        $bookingPayment->transaction()->delete();
    }
}
