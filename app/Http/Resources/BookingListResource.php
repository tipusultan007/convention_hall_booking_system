<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'receipt_no' => $this->receipt_no,
            'customer_name' => $this->customer->name,
            'event_type' => $this->event_type,
            'status' => $this->status,
            'due_amount' => number_format($this->due_amount, 2),
            // Get the earliest event date for display in the list
            'first_event_date' => $this->bookingDates->sortBy('event_date')->first()?->event_date->format('M d, Y'),
        ];
    }
}