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
    /**
     * Display a paginated listing of the resource, with optional search and date filtering.
     */
    public function index(Request $request)
    {
        // Start with the base query and eager load relationships for efficiency
        $query = Booking::with('customer', 'bookingDates');

        // --- FILTERING LOGIC ---

        // 1. Filter by Search Term
        if ($request->filled('search')) {
            $searchTerm = $request->query('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('receipt_no', 'like', "%{$searchTerm}%")
                    ->orWhere('event_type', 'like', "%{$searchTerm}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
                        $customerQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('phone', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // 2. Filter by Date Range
        // This will filter bookings based on their EVENT dates.
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');

            // This ensures that we only get bookings that have at least one event date within the range.
            $query->whereHas('bookingDates', function ($dateQuery) use ($startDate, $endDate) {
                $dateQuery->whereBetween('event_date', [$startDate, $endDate]);
            });
        }

        // Apply ordering and pagination to the final query
        $bookings = $query->latest('id')->paginate(20);

        return BookingListResource::collection($bookings);
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
