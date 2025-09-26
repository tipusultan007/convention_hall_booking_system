<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookingDetailResource;
use Illuminate\Support\Facades\Validator;

class BookingPaymentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->json()->all(), [
            'payment_amount' => 'required|numeric|min:0.01|max:' . $booking->due_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the validated data
        $validatedData = $validator->validated();

        DB::transaction(function () use ($validatedData, $booking) {
            // Use the validated data to create the payment
            $booking->payments()->create($validatedData);

            // The rest of the logic remains the same
            $totalPaid = $booking->payments()->sum('payment_amount');
            $dueAmount = $booking->total_amount - $totalPaid;
            $status = ($dueAmount <= 0) ? 'Paid' : 'Partially Paid';

            $booking->update([
                'advance_amount' => $totalPaid,
                'due_amount' => $dueAmount,
                'status' => $status,
            ]);
        });
        
        // Return the full, updated booking details
        $booking->load('customer', 'bookingDates', 'payments');
        return new BookingDetailResource($booking);
    }
}