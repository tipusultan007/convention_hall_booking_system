<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingPaymentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $booking->due_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Use a transaction to ensure data integrity
        DB::transaction(function () use ($request, $booking) {
            // 1. Create the new payment record
            $booking->payments()->create($request->all());

            // 2. Recalculate the booking's financial totals
            $totalPaid = $booking->payments()->sum('payment_amount');
            $dueAmount = $booking->total_amount - $totalPaid;

            // 3. Determine the new status
            $status = 'Partially Paid';
            if ($dueAmount <= 0) {
                $status = 'Paid';
            }

            // 4. Update the parent booking record
            $booking->update([
                'advance_amount' => $totalPaid, // 'advance_amount' now acts as 'total_paid'
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function destroy(BookingPayment $bookingPayment)
    {
        DB::transaction(function () use ($bookingPayment) {
            $booking = $bookingPayment->booking;
            $bookingPayment->delete(); // Delete the payment record

            // Recalculate the booking's financial totals again
            $totalPaid = $booking->payments()->sum('payment_amount');
            $dueAmount = $booking->total_amount - $totalPaid;

            $status = 'Pending';
            if ($dueAmount <= 0) {
                $status = 'Paid';
            } elseif ($totalPaid > 0) {
                $status = 'Partially Paid';
            }

            $booking->update([
                'advance_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });

        return back()->with('success', 'Payment record deleted successfully.');
    }
}
