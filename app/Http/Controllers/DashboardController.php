<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingPayment;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
     public function index()
    {
        // Define the date range for "this month"
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // --- WIDGET CALCULATIONS (Using the Correct Central Ledger) ---

        // These widgets remain correct as they are not transactional
        $upcomingBookingsCount = BookingDate::whereBetween('event_date', [Carbon::now(), Carbon::now()->addDays(7)])->count();
        $bookingsThisMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        
        // This is a projection of future payments, so it correctly comes from the bookings table
        $totalOutstandingDues = Booking::where('status', '!=', 'Paid')->sum('due_amount');
        
        // ** THE FIX: Calculate true revenue from the central transactions table **
        $revenueThisMonth = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                                       ->where('type', 'credit')
                                       ->sum('amount');
        
        // --- RETURN DATA ---
        // For the Web Controller:
        return view('dashboard.index', [
            'upcomingBookingsCount' => $upcomingBookingsCount,
            'totalOutstandingDues' => $totalOutstandingDues,
            'bookingsThisMonth' => $bookingsThisMonth,
            'revenueThisMonth' => $revenueThisMonth,
        ]);

        /*
        // For the API Controller:
        return response()->json([
            'upcomingBookingsCount' => $upcomingBookingsCount,
            'totalOutstandingDues' => $totalOutstandingDues,
            'bookingsThisMonth' => $bookingsThisMonth,
            'revenueThisMonth' => $revenueThisMonth,
        ]);
        */
    }
}
