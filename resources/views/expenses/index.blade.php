@extends('layout.master')

@section('title', 'Manage Expenses')

@section('content')
<div class="row">
    {{-- Column for the Expense Creation Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Log New Expense</h4>
            </div>
            <div class="card-body">
                {{-- The form will submit to the existing store route --}}
                <form action="{{ route('expenses.store') }}" method="POST">
                    {{-- We can reuse our existing form partial --}}
                    {{-- The $categories variable is now available from our controller --}}
                    @include('expenses._form', ['buttonText' => 'Log Expense'])
                </form>
            </div>
        </div>
    </div>

    {{-- Column for the List of Expenses --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{-- The create button is no longer needed here --}}
                <h4>All Logged Expenses</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th class="text-end">Amount</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-secondary">{{ $expense->category->name }}</span></td>
                                    <td class="text-end fw-bold">{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ Str::limit($expense->description, 30) }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No expenses logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
