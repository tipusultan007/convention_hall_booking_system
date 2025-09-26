@extends('layout.master')
@section('title', 'Manage Salaries')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h4>Generate Salaries for a Month</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('salaries.generate') }}" method="POST" class="row align-items-end">
            @csrf
            <div class="col-md-4">
                <label for="month" class="form-label">Select Month & Year</label>
                <input type="month" id="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </div>
        </form>
        <small class="form-text text-muted">This will generate salary records for all active workers for the selected month if they don't already exist.</small>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>All Salary Records</h4>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Worker</th>
                    <th>Salary Month</th>
                    <th class="text-end">Total Salary</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salaries as $salary)
                    <tr>
                        <td>{{ $salary->worker->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($salary->salary_month)->format('F, Y') }}</td>
                        <td class="text-end">{{ number_format($salary->total_salary, 2) }}</td>
                        <td class="text-end text-success">{{ number_format($salary->paid_amount, 2) }}</td>
                        <td class="text-end fw-bold text-danger">{{ number_format($salary->due_amount, 2) }}</td>
                        <td>
                            <span class="badge
                                @if($salary->status == 'Paid') bg-success
                                @elseif($salary->status == 'Partially Paid') bg-warning text-dark
                                @else bg-danger @endif">
                                {{ $salary->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('salaries.show', $salary->id) }}" class="btn btn-sm btn-info me-2">Manage Payments</a>
                                <a href="{{ route('salaries.edit', $salary->id) }}" class="btn btn-sm btn-secondary">Adjust</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No salary records found. Generate salaries for a month to begin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
