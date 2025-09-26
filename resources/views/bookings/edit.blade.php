@extends('layout.master')

@section('title', 'Edit Booking #' . $booking->id)

@section('content')
    {{-- Use the 'update' route and specify the 'PUT' method --}}
    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Left Column: Main Form --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Booking Contract #{{ $booking->id }}</h4>
                    </div>
                    <div class="card-body">
                        {{-- STEP 1: CUSTOMER DETAILS --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary me-2">1</div>
                            <h5 class="mb-0">Customer Details</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name', $booking->customer->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Customer Phone</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $booking->customer->phone) }}" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="customer_address" class="form-label">Address</label>
                                <textarea class="form-control" id="customer_address" name="customer_address" rows="2">{{ old('customer_address', $booking->customer->address) }}</textarea>
                            </div>
                        </div>
                        <hr>

                        {{-- STEP 2: EVENT DETAILS --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary me-2">2</div>
                            <h5 class="mb-0">Event Details</h5>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="event_type" class="form-label">Event Type</label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    {{-- Pre-select the saved event type --}}
                                    <option value="Wedding" {{ $booking->event_type == 'Wedding' ? 'selected' : '' }}>Wedding (বিয়ে)</option>
                                    <option value="Gaye Holud" {{ $booking->event_type == 'Gaye Holud' ? 'selected' : '' }}>Gaye Holud (গায়ে হলুদ)</option>
                                    <option value="Birthday" {{ $booking->event_type == 'Birthday' ? 'selected' : '' }}>Birthday (জন্মদিন)</option>
                                    <option value="Aqiqah" {{ $booking->event_type == 'Aqiqah' ? 'selected' : '' }}>Aqiqah (আকিকা)</option>
                                    <option value="Mezban" {{ $booking->event_type == 'Mezban' ? 'selected' : '' }}>Mezban (মেজবান)</option>
                                    <option value="Seminar" {{ $booking->event_type == 'Seminar' ? 'selected' : '' }}>Seminar (সেমিনার)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="receipt_no" class="form-label">Manual Receipt No. (Optional)</label>
                                <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{ old('receipt_no', $booking->receipt_no) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="guests_count" class="form-label">Guests</label>
                                <input type="number" class="form-control" id="guests_count" name="guests_count" value="{{ old('guests_count', $booking->guests_count) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="tables_count" class="form-label">Tables</label>
                                <input type="number" class="form-control" id="tables_count" name="tables_count" value="{{ old('tables_count', $booking->tables_count) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="boys_count" class="form-label">Servers (Boys)</label>
                                <input type="number" class="form-control" id="boys_count" name="boys_count" value="{{ old('boys_count', $booking->boys_count) }}">
                            </div>
                        </div>
                        <hr>

                        {{-- STEP 3: FINANCIALS --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary me-2">3</div>
                            <h5 class="mb-0">Financials</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="total_amount" class="form-label">Total Amount (মোট)</label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" value="{{ old('total_amount', $booking->total_amount) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="advance_amount" class="form-label">Advance (অগ্রিম)</label>
                                <input type="number" class="form-control" id="advance_amount" name="advance_amount" step="0.01" value="{{ old('advance_amount', $booking->advance_amount) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="due_amount" class="form-label">Due (বাকী)</label>
                                <input type="number" class="form-control" id="due_amount" name="due_amount" step="0.01" value="{{ old('due_amount', $booking->due_amount) }}" readonly style="background-color: #e9ecef;">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="notes_in_words" class="form-label">In Words (কথায়) <small class="text-muted-light">- auto-generated if left blank</small></label>
                                <input type="text" class="form-control" id="notes_in_words" name="notes_in_words" value="{{ old('notes_in_words', $booking->notes_in_words) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Date Selection and Summary --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary me-2">4</div>
                            <h5 class="mb-0">Booking Dates</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col-12 mb-2">
                                <label for="event_date_input" class="form-label">Event Date</label>
                                <input type="text" class="form-control" id="event_date_input" placeholder="Select a date...">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="time_slot_input" class="form-label">Time Slot</label>
                                <select class="form-select" id="time_slot_input">
                                    <option value="Day">Day</option>
                                    <option value="Night">Night</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="button" id="add-date-btn" class="btn btn-success w-100">
                                    <i class="link-icon" data-lucide="plus-circle" style="width: 16px; height: 16px;"></i> Add Date
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered text-center">
                            <thead class="table-light"><tr><th>Date</th><th>Slot</th><th>Action</th></tr></thead>
                            <tbody id="dates-table-body">
                            {{-- Loop through existing dates and display them --}}
                            @foreach($booking->bookingDates as $date)
                                <tr>
                                    <td>{{ $date->event_date }}<input type="hidden" name="booking_dates[date][]" value="{{ $date->event_date }}"></td>
                                    <td>{{ $date->time_slot }}<input type="hidden" name="booking_dates[slot][]" value="{{ $date->time_slot }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-date-btn"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Update Booking Contract</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-scripts')
    {{-- Include SweetAlert for better alerts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            if ($('#event_type').length) {
                $('#event_type').select2();
            }

            const datePicker = flatpickr("#event_date_input", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $('#add-date-btn').on('click', function() {
                let eventDate = $('#event_date_input').val();
                let timeSlot = $('#time_slot_input').val();
                let timeSlotText = $('#time_slot_input option:selected').text();

                if (!eventDate) {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Please select a date first!' });
                    return;
                }

                let newRow = `
            <tr>
                <td>${eventDate}<input type="hidden" name="booking_dates[date][]" value="${eventDate}"></td>
                <td>${timeSlotText}<input type="hidden" name="booking_dates[slot][]" value="${timeSlot}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-date-btn"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button></td>
            </tr>`;
                $('#dates-table-body').append(newRow);
                lucide.createIcons(); // Re-render Lucide icons
                datePicker.clear(); // Clear the input for the next entry
            });

            $(document).on('click', '.remove-date-btn', function() {
                $(this).closest('tr').remove();
            });

            function calculateDue() {
                let total = parseFloat($('#total_amount').val()) || 0;
                let advance = parseFloat($('#advance_amount').val()) || 0;
                let due = total - advance;
                $('#due_amount').val(due.toFixed(2));
            }

            // Initial calculation on page load
            calculateDue();

            $('#total_amount, #advance_amount').on('input', calculateDue);
        });
    </script>
@endpush
