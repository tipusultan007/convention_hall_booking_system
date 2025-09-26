@extends('layout.master')

@section('title', 'Manage Booking #' . $booking->id)

@section('content')
    <div class="row">
        {{-- Left Column: All Booking Information --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Booking Details</h4>
                        <small class="text-muted-light">Contract #: {{ $booking->id }} | Receipt #: {{ $booking->receipt_no ?? 'N/A' }}</small>
                    </div>
                    <span class="badge
                    @if($booking->status == 'Paid') bg-success
                    @elseif($booking->status == 'Partially Paid') bg-warning text-dark
                    @else bg-danger @endif fs-6">
                    {{ $booking->status }}
                </span>
                </div>
                <div class="card-body">

                    {{-- Booking & Event Details Table --}}
                    <table class="table table-borderless table-sm mb-4">
                        <tr><td width="150px"><strong>Customer Name:</strong></td><td>{{ $booking->customer->name }}</td></tr>
                        <tr><td><strong>Customer Phone:</strong></td><td>{{ $booking->customer->phone }}</td></tr>
                        <tr><td><strong>Event Type:</strong></td><td>{{ $booking->event_type }}</td></tr>
                        <tr><td><strong>Guests:</strong></td><td>{{ $booking->guests_count ?? 'N/A' }}</td></tr>
                        <tr><td><strong>Tables:</strong></td><td>{{ $booking->tables_count ?? 'N/A' }}</td></tr>
                        <tr><td><strong>Servers (Boys):</strong></td><td>{{ $booking->boys_count ?? 'N/A' }}</td></tr>
                    </table>

                    <hr>

                    {{-- Scheduled Dates --}}
                    <h5 class="mb-3">Scheduled Dates</h5>
                    <ul class="list-group">
                        @forelse($booking->bookingDates->sortBy('event_date') as $date)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i data-lucide="calendar" class="icon-sm me-2 text-primary"></i>
                            <strong>{{ \Carbon\Carbon::parse($date->event_date)->format('l, F j, Y') }}</strong>
                        </span>
                                <span class="badge bg-info rounded-pill">{{ $date->time_slot }}</span>
                            </li>
                        @empty
                            <li class="list-group-item">No dates have been scheduled for this booking.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <small class="text-muted-light">Booking created on: {{ $booking->created_at->format('M d, Y, h:i A') }}</small>
                    <div>
                        <a href="{{ route('bookings.receipt.pdf', $booking->id) }}" class="btn btn-sm btn-outline-danger me-2">Download Receipt</a>
                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning">Edit Booking</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Financials and Payment Management --}}
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><h5>Financial Summary</h5></div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <p class="text-muted mb-0">Total Amount</p>
                            <h4>৳{{ number_format($booking->total_amount, 2) }}</h4>
                        </div>
                        <div class="col-4 border-end text-success">
                            <p class="text-muted mb-0">Total Paid</p>
                            <h4>৳{{ number_format($booking->advance_amount, 2) }}</h4>
                        </div>
                        <div class="col-4 text-danger">
                            <p class="text-muted mb-0">Amount Due</p>
                            <h4 class="fw-bold">৳{{ number_format($booking->due_amount, 2) }}</h4>
                        </div>
                    </div>
                    @if($booking->notes_in_words)
                        <div class="mt-3 border-top pt-3">
                            <p class="mb-1"><strong>In Words:</strong> <span class="text-muted-light fst-italic">"{{ $booking->notes_in_words }}"</span></p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5>Payment History & Actions</h5></div>
                <div class="card-body">
                    @if ($booking->due_amount > 0)
                        {{-- Add Payment Form --}}
                        <h6 class="mb-3">Record a Payment</h6>
                        <form action="{{ route('bookings.payments.store', $booking->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_amount" class="form-label">Amount to Pay</label>
                                <input type="number" step="0.01" name="payment_amount" class="form-control" max="{{ $booking->due_amount }}" placeholder="Max: {{ number_format($booking->due_amount, 2) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Payment Date</label>
                                <input type="text" id="payment_date" name="payment_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Mobile Banking">Mobile Banking</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Record Payment</button>
                        </form>
                        <hr>
                    @endif

                    {{-- Payment History Table --}}
                    <h6 class="mb-3">Transaction History</h6>
                    <div class="table-responsive" style="max-height: 300px;">
                        <table class="table table-sm">
                            <thead><tr><th>Date</th><th class="text-end">Amount</th><th>Action</th></tr></thead>
                            <tbody>
                            @forelse($booking->payments->sortByDesc('payment_date') as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}<br><small class="text-muted-light">{{ $payment->payment_method }}</small></td>
                                    <td class="text-end">৳{{ number_format($payment->payment_amount, 2) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('booking-payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Delete this payment record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-xs"><i data-lucide="trash-2" class="icon-xs"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted-light py-3">No payments recorded yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#payment_date", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                defaultDate: "today"
            });
        });
    </script>
@endpush
