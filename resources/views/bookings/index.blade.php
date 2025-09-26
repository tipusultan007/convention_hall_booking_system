@extends('layout.master')

@section('title', 'All Bookings')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>All Bookings</h4>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">Create New Booking</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Contract #</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Event Type</th>
                        <th scope="col">Event Dates</th>
                        <th scope="col" class="text-end">Total Amount</th>
                        <th scope="col" class="text-end">Due Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <th scope="row">#{{ $booking->id }}</th>
                            <td>
                                {{ $booking->customer->name }} <br>
                                <small class="text-muted">{{ $booking->customer->phone }}</small>
                            </td>
                            <td>{{ $booking->event_type }}</td>
                            <td>
                                {{-- Loop through the multiple dates for this booking --}}
                                @foreach ($booking->bookingDates as $date)
                                    <span class="badge bg-secondary mb-1">
                                        {{ \Carbon\Carbon::parse($date->event_date)->format('M d, Y') }} ({{ $date->time_slot }})
                                    </span><br>
                                @endforeach
                            </td>
                            <td class="text-end">{{ number_format($booking->total_amount, 2) }}</td>
                            <td class="text-end fw-bold {{ $booking->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($booking->due_amount, 2) }}
                            </td>
                            <td>
                                <span class="badge
                                    @if($booking->status == 'Pending') bg-warning text-dark
                                    @elseif($booking->status == 'Confirmed') bg-success
                                    @else bg-secondary @endif">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td>
    <div class="d-flex">
        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-info me-2">Manage</a>
        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>

        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
