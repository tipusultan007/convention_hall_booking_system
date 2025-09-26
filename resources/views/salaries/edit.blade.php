@extends('layout.master')
@section('title', 'Adjust Salary')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Adjust Salary for {{ $monthlySalary->worker->name }}</h4></div>
            <div class="card-body">
                <form action="{{ route('salaries.update', $monthlySalary->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Worker</label>
                        <p class="form-control-plaintext">{{ $monthlySalary->worker->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salary Month</label>
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($monthlySalary->salary_month)->format('F, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="total_salary" class="form-label">Total Salary (for this month)</label>
                        <input type="number" step="0.01" id="total_salary" name="total_salary" class="form-control" value="{{ old('total_salary', $monthlySalary->total_salary) }}" required>
                        <small class="form-text text-muted">Use this to add a bonus or make a deduction.</small>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Salary Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
