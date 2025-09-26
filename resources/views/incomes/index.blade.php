@extends('layout.master')
@section('title', 'Other Income')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h4>Log New Income</h4></div>
                <div class="card-body">
                    <form action="{{ route('incomes.store') }}" method="POST">
                        @include('incomes._form', [
                    'categories' => $categories,
                    'buttonText' => 'Log Income'
                ])
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>All Other Income</h4></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                        <tr><th>Date</th><th>Category</th><th class="text-end">Amount</th><th>Description</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($incomes as $income)
                            <tr>
                                <td>{{ $income->income_date->format('M d, Y') }}</td>
                                <td><span class="badge bg-secondary">{{ $income->category->name }}</span></td>
                                <td class="text-end fw-bold text-success">৳{{ number_format($income->amount, 2) }}</td>
                                <td>{{ Str::limit($income->description, 50) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('incomes.edit', $income->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                        <form action="{{ route('incomes.destroy', $income->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No income logged yet.</td></tr>
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
            $('#income_category_id').select2({ theme: "bootstrap-5" });
            flatpickr("#income_date", { altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", defaultDate: "today" });
        });
    </script>
@endpush
