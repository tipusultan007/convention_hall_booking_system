<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingDate;

class BookingEventController extends Controller
{
    /**
     * Handle the incoming request to fetch booking events for the calendar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // 1. Add validation to ensure start and end dates are provided by FullCalendar
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        // 2. Use whereBetween to efficiently fetch only the events for the visible date range
        $bookingDates = BookingDate::whereBetween('event_date', [$request->start, $request->end])
                                   ->with('booking.customer') // Eager load relationships
                                   ->get();

        $events = []; // Initialize an empty array for events

        foreach ($bookingDates as $date) {
            // 3. (THE BUG FIX) Check if the associated booking exists before trying to access it
            if (!$date->booking || !$date->booking->customer) {
                // If the booking or customer was deleted, just skip this record
                // This prevents the 500 Internal Server Error
                continue;
            }

            // Determine the event color based on the booking status
            $status = $date->booking->status;
            $color = '#6c757d'; // Default color (grey)
            if ($status === 'Pending') {
                $color = '#ffc107'; // Yellow for Pending
            } elseif ($status === 'Confirmed') {
                $color = '#198754'; // Green for Confirmed
            }

            // Add the structured event object to our array
            $events[] = [
                'title' => $date->booking->event_type . ' - ' . $date->booking->customer->name . ' (' . $date->time_slot . ')',
                'start' => $date->event_date,
                'allDay' => true,
                'url' => route('bookings.show', $date->booking_id),
                'backgroundColor' => $color,
                'borderColor' => $color
            ];
        }

        return response()->json($events);
    }
}