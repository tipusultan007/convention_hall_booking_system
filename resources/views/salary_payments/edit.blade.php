@extends('layout.master')
@section('title', 'Edit Salary Payment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Edit Payment Record</h4></div>
            <div class="card-body">
                <form action="{{ route('salary-payments.update', $salaryPayment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" id="payment_amount" name="payment_amount" class="form-control" value="{{ old('payment_amount', $salaryPayment->payment_amount) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" class="form-control" value="{{ old('payment_date', \Carbon\Carbon::parse($salaryPayment->payment_date)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $salaryPayment->notes) }}</textarea>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('salaries.show', $salaryPayment->monthly_salary_id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
