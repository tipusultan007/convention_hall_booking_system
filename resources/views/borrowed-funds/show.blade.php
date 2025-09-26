@extends('layout.master')
@section('title', 'Manage Repayments')
@section('content')
    <div class="mb-3"><a href="{{ route('borrowed-funds.index') }}" class="btn btn-secondary">&larr; Back to Fund List</a></div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Repayment for: "{{ $borrowedFund->purpose }}"</h4>
                    <small class="text-muted-light">From: <strong>{{ $borrowedFund->lender->name }}</strong> on {{ $borrowedFund->date_borrowed->format('M d, Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="row text-center bg-light pt-3 pb-2 rounded mb-4">
                        <div class="col-4"><p class="text-muted mb-0">Total Borrowed</p><h5>৳{{ number_format($borrowedFund->amount_borrowed, 2) }}</h5></div>
                        <div class="col-4 text-success"><p class="text-muted mb-0">Total Repaid</p><h5>৳{{ number_format($borrowedFund->amount_repaid, 2) }}</h5></div>
                        <div class="col-4 text-danger"><p class="text-muted mb-0">Amount Due</p><h5 class="fw-bold">৳{{ number_format($borrowedFund->due_amount, 2) }}</h5></div>
                    </div>
                    <h5>Repayment History</h5>
                    <table class="table">
                        <thead><tr><th>Date</th><th class="text-end">Amount</th><th>Notes</th><th>Action</th></tr></thead>
                        <tbody>
                        @forelse($borrowedFund->repayments->sortByDesc('repayment_date') as $repayment)
                            <tr>
                                <td>{{ $repayment->repayment_date->format('M d, Y') }}</td>
                                <td class="text-end">{{ number_format($repayment->repayment_amount, 2) }}</td>
                                <td>{{ $repayment->notes }}</td>
                                <td>
                                    <form action="{{ route('fund-repayments.destroy', $repayment->id) }}" method="POST" onsubmit="return confirm('Delete this repayment?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No repayments recorded yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h4>Record a Repayment</h4></div>
                <div class="card-body">
                    <form action="{{ route('borrowed-funds.repayments.store', $borrowedFund->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="repayment_amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" name="repayment_amount" class="form-control" max="{{ $borrowedFund->due_amount }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="repayment_date" class="form-label">Date</label>
                            <input type="text" id="repayment_date" name="repayment_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" @if($borrowedFund->due_amount <= 0) disabled @endif>
                            @if($borrowedFund->due_amount <= 0) Fully Repaid @else Add Repayment @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        flatpickr("#repayment_date", { altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", defaultDate: "today" });
    </script>
@endpush
