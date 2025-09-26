@extends('layout.master')
@section('title', 'General Ledger')

@section('content')
    {{-- Date Filter Form --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>General Ledger</h4>
            <a href="{{ route('transactions.pdf', request()->query()) }}" class="btn btn-danger">
                <i data-lucide="file-type-pdf" class="icon-sm me-2"></i>Export to PDF
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('transactions.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-end">Debit (Outgoing)</th>
                    <th class="text-end">Credit (Incoming)</th>
                    <th class="text-end">Balance</th>
                </tr>
                </thead>
                <tbody>
                {{-- Opening Balance Row --}}
                <tr class="table-light">
                    {{-- Use the 'currentPage' method to adjust the text --}}
                    <td colspan="4" class="fw-bold">
                        @if ($transactions->currentPage() > 1)
                            Opening Balance for this Page
                        @else
                            Opening Balance on {{ $startDate->format('M d, Y') }}
                        @endif
                    </td>
                    <td class="text-end fw-bold">৳{{ number_format($openingBalance, 2) }}</td>
                </tr>

                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-end text-danger">
                            @if ($transaction->type == 'debit')
                                ৳{{ number_format($transaction->amount, 2) }}
                            @endif
                        </td>
                        <td class="text-end text-success">
                            @if ($transaction->type == 'credit')
                                ৳{{ number_format($transaction->amount, 2) }}
                            @endif
                        </td>
                        {{-- New Running Balance Column --}}
                        <td class="text-end fw-bold">৳{{ number_format($transaction->balance, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">No transactions found for this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot class="table-dark">
                <tr>
                    <td colspan="4" class="text-end fw-bold">Closing Balance on {{ $endDate->format('M d, Y') }}</td>
                    <td class="text-end fw-bold">৳{{ number_format($closingBalance, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#start_date", { dateFormat: "Y-m-d" });
            flatpickr("#end_date", { dateFormat: "Y-m-d" });
        });
    </script>
@endpush
