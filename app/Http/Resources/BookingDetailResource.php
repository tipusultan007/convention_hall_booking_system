<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'receipt_no' => $this->receipt_no,
            'status' => $this->status,
            'notes_in_words' => $this->notes_in_words,
            'created_at' => $this->created_at->format('M d, Y, h:i A'),

            // Customer Details
            'customer' => [
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
                'address' => $this->customer->address,
            ],
            
            // Event Details
            'event' => [
                'type' => $this->event_type,
                'guests' => $this->guests_count,
                'tables' => $this->tables_count,
                'servers' => $this->boys_count,
            ],

            // Financials
            'financials' => [
                'total_amount' => number_format($this->total_amount, 2),
                'total_paid' => number_format($this->advance_amount, 2),
                'due_amount' => number_format($this->due_amount, 2),
            ],

            // Dates (formatted for display)
            'dates' => $this->bookingDates->sortBy('event_date')->map(function ($date) {
                return [
                    'date' => \Carbon\Carbon::parse($date->event_date)->format('l, F j, Y'),
                    'slot' => $date->time_slot,
                ];
            }),

            // Payments (formatted for display)
            'payments' => $this->payments->sortByDesc('payment_date')->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => number_format($payment->payment_amount, 2),
                    'date' => $payment->payment_date->format('M d, Y'),
                    'method' => $payment->payment_method,
                    'notes' => $payment->notes,
                ];
            }),
        ];
    }
}