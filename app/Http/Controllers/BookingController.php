<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rakibhstu\Banglanumber\Facades\NumberToBangla as FacadesNumberToBangla;
use Rakibhstu\Banglanumber\NumberToBangla;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load the 'customer' and 'bookingDates' relationships
        // to prevent the N+1 query problem. Order by the newest first.
        $bookings = Booking::with('customer', 'bookingDates')->latest()->get();

        // Return the view and pass the bookings data to it
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        return view('bookings.create');
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'event_type' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'tables_count' => 'nullable|integer|min:0',
            'boys_count' => 'nullable|integer|min:0',
            'advance_amount' => 'required|numeric|min:0',
            'booking_dates.date' => 'required|array|min:1',
            'booking_dates.date.*' => 'required|date',
            'booking_dates.slot' => 'required|array|min:1',
            'booking_dates.slot.*' => 'required|string',
            'receipt_no' => 'nullable|string|unique:bookings,receipt_no,' . ($booking->id ?? ''),
        ]);

        // Use a database transaction to ensure data integrity
        DB::beginTransaction();
        try {
            $numto = new NumberToBangla();
            $notesInWords = $request->notes_in_words;
            if (empty($notesInWords) && $request->filled('total_amount') && $request->total_amount > 0) {
                // Use the new package's simple syntax
                $words = $numto->bnWord($request->total_amount);
                $notesInWords = ucfirst($words) . ' টাকা মাত্র।';
            }
            // 1. Create or Find the customer
            $customer = Customer::firstOrCreate(
                ['phone' => $request->customer_phone],
                ['name' => $request->customer_name, 'address' => $request->customer_address]
            );

            // 2. Create the Booking
            $booking = $customer->bookings()->create([
                'event_type' => $request->event_type,
                'guests_count' => $request->guests_count,
                'tables_count' => $request->tables_count,
                'boys_count' => $request->boys_count,
                'total_amount' => $request->total_amount,
                'advance_amount' => $request->advance_amount,
                'due_amount' => $request->total_amount - $request->advance_amount,
                'notes_in_words' => $notesInWords,
                'status' => ($request->advance_amount > 0) ? 'Partially Paid' : 'Pending',
                'receipt_no' => $request->receipt_no,
            ]);

            // 3. Add all the associated booking dates
            $bookingDates = $request->input('booking_dates');
            for ($i = 0; $i < count($bookingDates['date']); $i++) {
                $booking->bookingDates()->create([
                    'event_date' => $bookingDates['date'][$i],
                    'time_slot' => $bookingDates['slot'][$i],
                ]);
            }

            // ***** 4. THE NEW LOGIC: Create the initial payment record if an advance was made *****
            if ($request->filled('advance_amount') && $request->advance_amount > 0) {
                $booking->payments()->create([
                    'payment_amount' => $request->advance_amount,
                    'payment_date'   => now()->toDateString(), // Use the current date for the initial payment
                    'payment_method' => 'Cash', // Or a default value you prefer
                    'notes'          => 'Initial advance payment during booking.',
                ]);

                // We can also update the status to Paid if the booking is fully paid upfront
                if ($booking->due_amount <= 0) {
                    $booking->update(['status' => 'Paid']);
                }
            }
            // ***********************************************************************************

            DB::commit(); // All good, commit the transaction

            // Redirect with a success message (we'll need to set this up in the view later)
            return redirect()->route('bookings.create')->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Something went wrong, rollback
            Log::error('Booking creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create booking. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Eager load the relationships to prevent extra queries in the view
        $booking->load('customer', 'bookingDates');

        return view('bookings.show', compact('booking'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Eager load the relationships to use in the view
        $booking->load('customer', 'bookingDates');

        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $totalPaid = $booking->payments()->sum('payment_amount');
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'event_type' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'boys_count' => 'nullable|integer|min:0',
            'advance_amount' => 'required|numeric|min:0',
            'booking_dates.date' => 'required|array|min:1',
            'booking_dates.date.*' => 'required|date',
            'booking_dates.slot' => 'required|array|min:1',
            'booking_dates.slot.*' => 'required|string',
            'receipt_no' => 'nullable|string|unique:bookings,receipt_no,' . ($booking->id ?? ''),
        ]);

        DB::beginTransaction();
        try {
            $numto = new NumberToBangla();
            $notesInWords = $request->notes_in_words;
            if (empty($notesInWords) && $request->filled('total_amount') && $request->total_amount > 0) {
                // Use the new package's simple syntax
                $words = $numto->bnWord($request->total_amount);
                $notesInWords = ucfirst($words) . ' টাকা মাত্র।';
            }

            // 1. Update the Customer details
            $booking->customer->update([
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'address' => $request->customer_address,
            ]);

            $newTotalAmount = $request->total_amount;
            $newDueAmount = $newTotalAmount - $totalPaid;
            $newStatus = 'Pending';
            if ($newDueAmount <= 0) {
                $newStatus = 'Paid';
            } elseif ($totalPaid > 0) {
                $newStatus = 'Partially Paid';
            }
            // 2. Update the Booking details
            $booking->update([
                'receipt_no' => $request->receipt_no,
                'event_type' => $request->event_type,
                'guests_count' => $request->guests_count,
                'tables_count' => $request->tables_count,
                'boys_count' => $request->boys_count,
                'total_amount' => $newTotalAmount,
                // The 'advance_amount' (total paid) is NOT updated directly from the form.
                // It's only updated when a new payment is made.
                'due_amount' => $newDueAmount,
                'status' => $newStatus,
                'notes_in_words' => $notesInWords,
            ]);

            // 3. Sync the booking dates. The easiest way is to delete the old ones and create the new ones.
            $booking->bookingDates()->delete();

            $bookingDates = $request->input('booking_dates');
            for ($i = 0; $i < count($bookingDates['date']); $i++) {
                $booking->bookingDates()->create([
                    'event_date' => $bookingDates['date'][$i],
                    'time_slot' => $bookingDates['slot'][$i],
                ]);
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to update booking. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return redirect()->route('bookings.index')->with('error', 'Failed to delete the booking.');
        }
    }

    /**
     * Generate and download a PDF money receipt for a specific booking.
     */
    public function downloadReceiptPDF(Booking $booking)
    {
        $booking->load('customer', 'bookingDates');

        // Snappy's syntax is slightly different
        $pdf = app('snappy.pdf.wrapper')->loadView('bookings.receipt_pdf', compact('booking'))
            ->setOption('enable-local-file-access', true)
            ->setPaper('a5')
            ->setOrientation('landscape')
            // 2. Add a zoom option to scale the content down to fit the smaller page.
            //    You can experiment with this value (e.g., 0.8, 0.9) to get the perfect fit.
            ->setOption('zoom', 0.8)
            // 3. (Optional) Adjust margins for the smaller paper size.
            ->setOption('margin-top', '5mm')
            ->setOption('margin-bottom', '5mm')
            ->setOption('margin-left', '5mm')
            ->setOption('margin-right', '5mm'); // Example of setting an option

        $fileName = 'Momota-Receipt-#' . $booking->id . '-' . $booking->customer->name . '.pdf';

        return $pdf->download($fileName);
    }
}
