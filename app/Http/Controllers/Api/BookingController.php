<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingDetailResource;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rakibhstu\Banglanumber\NumberToBangla;
use App\Http\Resources\BookingListResource; // Use this for the index


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('customer', 'bookingDates')->latest()->paginate(20);
        return BookingResource::collection($bookings);
    }
    public function show(Booking $booking)
    {
        // Eager load all relationships needed for the detail resource
        $booking->load('customer', 'bookingDates', 'payments');
        return new BookingDetailResource($booking);
    }

    public function store(Request $request)
    {
        $jsonData = $request->json()->all();
        $validator = Validator::make($jsonData, [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'event_type' => 'required|string',
            'receipt_no' => 'nullable|string|unique:bookings,receipt_no',
            'guests_count' => 'nullable|integer',
            'tables_count' => 'nullable|integer',
            'boys_count' => 'nullable|integer',
            'total_amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0|lte:total_amount',
            'notes_in_words' => 'nullable|string',
            'booking_dates' => 'required|array|min:1',
            'booking_dates.*.date' => 'required|date_format:Y-m-d',
            'booking_dates.*.slot' => 'required|string|in:Day,Night',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $validatedData = $validator->validated();

        $notesInWords = $validatedData['notes_in_words'] ?? null;
        if (empty($notesInWords) && $validatedData['total_amount'] > 0) {
            $numToBangla = new NumberToBangla();
            $words = $numToBangla->bnWord($validatedData['total_amount']);
            $notesInWords = ucfirst($words) . ' টাকা মাত্র।';
        }
        
        $booking = null;
        DB::transaction(function () use ($validatedData, $notesInWords, &$booking) {
            $customer = Customer::firstOrCreate(
                ['phone' => $validatedData['customer_phone']],
                ['name' => $validatedData['customer_name'], 'address' => $validatedData['customer_address']]
            );
            
            $dueAmount = $validatedData['total_amount'] - ($validatedData['advance_amount'] ?? 0);
            $status = ($validatedData['advance_amount'] > 0) ? ($dueAmount <= 0 ? 'Paid' : 'Partially Paid') : 'Pending';

            $booking = $customer->bookings()->create([
                'receipt_no' => $validatedData['receipt_no'] ?? null,
                'event_type' => $validatedData['event_type'],
                'guests_count' => $validatedData['guests_count'] ?? null,
                'tables_count' => $validatedData['tables_count'] ?? null,
                'boys_count' => $validatedData['boys_count'] ?? null,
                'total_amount' => $validatedData['total_amount'],
                'advance_amount' => $validatedData['advance_amount'] ?? 0,
                'due_amount' => $dueAmount,
                'status' => $status,
                'notes_in_words' => $notesInWords,
            ]);

            foreach ($validatedData['booking_dates'] as $bookingDate) {
                $booking->bookingDates()->create([
                    'event_date' => $bookingDate['date'],
                    'time_slot' => $bookingDate['slot'],
                ]);
            }

            if (($validatedData['advance_amount'] ?? 0) > 0) {
                $booking->payments()->create([
                    'payment_amount' => $validatedData['advance_amount'],
                    'payment_date'   => now()->toDateString(),
                    'payment_method' => 'Cash',
                    'notes'          => 'Initial advance payment during booking creation.',
                ]);
            }
        });

        $booking->load('customer', 'bookingDates', 'payments');
        return new BookingDetailResource($booking);
    }
}
