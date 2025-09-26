@extends('layout.master')
@section('title', 'Borrowed Funds')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h4>Record New Borrowed Fund</h4></div>
                <div class="card-body">
                    <form action="{{ route('borrowed-funds.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="lender_id" class="form-label">Lender/Source</label>
                            <select id="lender_id" name="lender_id" class="form-select" required>
                                <option value="">Select a source</option>
                                @foreach($lenders as $lender)
                                    <option value="{{ $lender->id }}">{{ $lender->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount_borrowed" class="form-label">Amount Borrowed</label>
                            <input type="number" step="0.01" id="amount_borrowed" name="amount_borrowed" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_borrowed" class="form-label">Date</label>
                            <input type="text" id="date_borrowed" name="date_borrowed" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <input type="text" id="purpose" name="purpose" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>All Borrowed Funds History</h4></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr><th>Source</th><th>Purpose</th><th class="text-end">Due</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($borrowedFunds as $fund)
                            <tr>
                                <td>
                                    <strong>{{ $fund->lender->name }}</strong><br>
                                    <small class="text-muted-light">{{ $fund->date_borrowed->format('M d, Y') }}</small>
                                </td>
                                <td>{{ $fund->purpose }}</td>
                                <td class="text-end fw-bold text-danger">৳{{ number_format($fund->due_amount, 2) }}</td>
                                <td>
                                    <span class="badge @if($fund->status == 'Repaid') bg-success @elseif($fund->status == 'Partially Repaid') bg-warning text-dark @else bg-danger @endif">
                                        {{ $fund->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('borrowed-funds.show', $fund->id) }}" class="btn btn-sm btn-info me-2">Repay</a>
                                        <a href="{{ route('borrowed-funds.edit', $fund->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No fund records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#lender_id').select2({ theme: "bootstrap-5" });
            flatpickr("#date_borrowed", { altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", defaultDate: "today" });
        });
    </script>
@endpush
