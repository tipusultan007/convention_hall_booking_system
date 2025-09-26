@extends('layout.master')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        {{-- Upcoming Bookings --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ $upcomingBookingsCount }}</h3>
                            <p class="card-text">Upcoming (Next 7 Days)</p>
                        </div>
                        <div class="fs-1 opacity-75">
                            {{-- Lucide Icon for Upcoming Events --}}
                            <i data-lucide="calendar-check-2"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('bookings.index') }}" class="card-footer text-white text-decoration-none">
                    View Details <i data-lucide="arrow-right-circle" class="icon-sm"></i>
                </a>
            </div>
        </div>

        {{-- Outstanding Dues --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">৳{{ number_format($totalOutstandingDues, 2) }}</h3>
                            <p class="card-text">Total Outstanding Dues</p>
                        </div>
                        <div class="fs-1 opacity-75">
                            {{-- Lucide Icon for Invoices/Dues --}}
                            <i data-lucide="receipt"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('bookings.index') }}" class="card-footer text-white text-decoration-none">
                    View Details <i data-lucide="arrow-right-circle" class="icon-sm"></i>
                </a>
            </div>
        </div>

        {{-- Bookings This Month --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ $bookingsThisMonth }}</h3>
                            <p class="card-text">Bookings This Month</p>
                        </div>
                        <div class="fs-1 opacity-75">
                            {{-- Lucide Icon for New Items --}}
                            <i data-lucide="calendar-plus"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('reports.profit_loss') }}" class="card-footer text-white text-decoration-none">
                    View Report <i data-lucide="arrow-right-circle" class="icon-sm"></i>
                </a>
            </div>
        </div>

        {{-- Revenue This Month --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">৳{{ number_format($revenueThisMonth, 2) }}</h3>
                            <p class="card-text">Total Revenue This Month</p>
                        </div>
                        <div class="fs-1 opacity-75">
                            {{-- Lucide Icon for Money/Revenue --}}
                            <i data-lucide="banknote"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('reports.profit_loss') }}" class="card-footer text-white text-decoration-none">
                    View Report <i data-lucide="arrow-right-circle" class="icon-sm"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Booking Calendar</h4>
        </div>
        <div class="card-body">
            {{-- This div is where the calendar will be rendered --}}
            <div id="bookingCalendar"></div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{-- FullCalendar Core JS and Plugins --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('bookingCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Start with month view
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek' // Buttons to change view
                },
                height: 'auto', // Adjust height automatically

                // Fetch events from our API endpoint
                events: '/api/booking-events',

                // Handle what happens when an event is clicked
                eventClick: function (info) {
                    // 'info.event.url' will hold the link to the booking's show page
                    if (info.event.url) {
                        window.location.href = info.event.url; // Redirect to the booking details page
                    }
                }
            });

            calendar.render();
        });
    </script>
@endpush
