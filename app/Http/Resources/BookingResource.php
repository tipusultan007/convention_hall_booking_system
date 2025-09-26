<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer->name,
            'event_type' => $this->event_type,
            'status' => $this->status,
            'due_amount' => $this->due_amount,
            'first_event_date' => $this->bookingDates->sortBy('event_date')->first()?->event_date,
        ];
    }
}
