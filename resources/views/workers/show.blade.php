@extends('layout.master')
@section('title', 'Salary History: ' . $worker->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('workers.index') }}" class="btn btn-secondary">&larr; Back to All Workers</a>
</div>

{{-- Worker Details Card --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Worker Details</h4>
        <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-warning">Edit Worker</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <p class="mb-1"><strong>Name:</strong></p>
                <p>{{ $worker->name }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Designation:</strong></p>
                <p>{{ $worker->designation ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Phone:</strong></p>
                <p>{{ $worker->phone ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Current Monthly Salary:</strong></p>
                <p>৳{{ number_format($worker->monthly_salary, 2) }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Status:</strong></p>
                @if($worker->is_active)
                    <p><span class="badge bg-success">Active</span></p>
                @else
                    <p><span class="badge bg-secondary">Inactive</span></p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Salary History Card --}}
<div class="card">
    <div class="card-header">
        <h4>Salary History</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Salary Month</th>
                        <th class="text-end">Total Salary</th>
                        <th class="text-end">Amount Paid</th>
                        <th class="text-end">Amount Due</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($worker->monthlySalaries as $salary)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($salary->salary_month)->format('F, Y') }}</td>
                            <td class="text-end">৳{{ number_format($salary->total_salary, 2) }}</td>
                            <td class="text-end text-success">৳{{ number_format($salary->paid_amount, 2) }}</td>
                            <td class="text-end fw-bold text-danger">৳{{ number_format($salary->due_amount, 2) }}</td>
                            <td>
                                <span class="badge
                                    @if($salary->status == 'Paid') bg-success
                                    @elseif($salary->status == 'Partially Paid') bg-warning text-dark
                                    @else bg-danger @endif">
                                    {{ $salary->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('salaries.show', $salary->id) }}" class="btn btn-sm btn-info">Manage Payments</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No salary records found for this worker.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
