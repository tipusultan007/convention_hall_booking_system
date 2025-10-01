@extends('layout.master')
@section('title', 'Salary Details: ' . $monthlySalary->worker->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('salaries.index') }}" class="btn btn-secondary">&larr; Back to Salary List</a>
</div>

{{-- Summary Cards --}}
<div class="row">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Worker</h6>
                <h4 class="card-title">{{ $monthlySalary->worker->name }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Salary Month</h6>
                <h4 class="card-title">{{ \Carbon\Carbon::parse($monthlySalary->salary_month)->format('F, Y') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <p class="mb-0 text-muted">Total Salary</p>
                        <h5>৳{{ number_format($monthlySalary->total_salary, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center text-success">
                        <p class="mb-0 text-muted">Paid</p>
                        <h5>৳{{ number_format($monthlySalary->paid_amount, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center text-danger">
                        <p class="mb-0 text-muted">Due</p>
                        <h5 class="fw-bold">৳{{ number_format($monthlySalary->due_amount, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    {{-- Add Payment Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h4>Record a Payment</h4></div>
            <div class="card-body">
                <form action="{{ route('salary-payments.payments.store', $monthlySalary->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" name="payment_amount" class="form-control" max="{{ $monthlySalary->due_amount }}" placeholder="Max: {{ $monthlySalary->due_amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" @if($monthlySalary->due_amount <= 0) disabled @endif>
                        @if($monthlySalary->due_amount <= 0) Fully Paid @else Add Payment @endif
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h4>Payment History</h4></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Date</th><th class="text-end">Amount</th><th>Notes</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($monthlySalary->payments->sortByDesc('payment_date') as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td class="text-end">{{ number_format($payment->payment_amount, 2) }}</td>
                            <td>{{ $payment->notes }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('salary-payments.edit', $payment->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                    <form action="{{ route('salary-payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Delete this payment record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No payments recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
