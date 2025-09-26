@extends('layout.master')

@section('title', 'Profit & Loss Report')

@section('content')
{{-- Date Filter Form --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Select Date Range</h4>
        <a href="{{ route('reports.profit_loss.pdf', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
            class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Export to PDF
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.profit_loss') }}" method="GET" class="row align-items-end">
            <div class="col-md-5"><label for="start_date" class="form-label">Start Date</label><input type="text"
                    class="form-control"
                    id="start_date"
                    name="start_date"
                    value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-5"><label for="end_date" class="form-label">End Date</label><input type="text"
                    class="form-control"
                    id="end_date"
                    name="end_date"
                    value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Generate Report</button>
            </div>
        </form>
    </div>
</div>

{{-- Financial Summary Cards --}}
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Revenue</div>
            <div class="card-body">
                <h4 class="card-title">৳ {{ number_format($totalRevenue, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Total Expenses</div>
            <div class="card-body">
                <h4 class="card-title">৳ {{ number_format($totalExpenses, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white {{ $netProfit >= 0 ? 'bg-primary' : 'bg-warning' }} mb-3">
            <div class="card-header">Net Profit / Loss</div>
            <div class="card-body">
                <h4 class="card-title">৳ {{ number_format($netProfit, 2) }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- Main Breakdowns in a Two-Column Layout --}}
<div class="row">
    {{-- Left Column for all INCOMING funds --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Revenue Breakdown</h5>
            </div>
            <div class="card-body">
                {{-- Booking Revenue Table --}}
                <h6 class="mb-3">Booking Payments Received</h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Customer</th>
                            <th class="text-end">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookingPaymentDetails as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>{{ $payment->booking->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ number_format($payment->payment_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted-light">No booking payments received in this period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Booking Payments:</td>
                            <td class="text-end fw-bold">৳ {{ number_format($bookingPaymentDetails->sum('payment_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <hr class="my-4">

                {{-- Other Income Table --}}
                <h6 class="mb-3">Other Income</h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($otherIncomeDetails as $transaction)
                        {{-- <-- Change variable name --}}
                        <tr>
                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            <td>{{ $transaction->transactionable->category->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted-light">No other income in this period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Other Income:</td>
                            <td class="text-end fw-bold">
                                ৳ {{ number_format($otherIncomeDetails->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

    {{-- Right Column for all OUTGOING funds --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Expense Breakdown</h5>
            </div>
            <div class="card-body">
                {{-- General Expenses Table --}}
                <h6 class="mb-3">General Expenses</h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expensesDetails as $transaction)
                        {{-- <-- Change variable name --}}
                        <tr>
                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            <td>{{ $transaction->transactionable->category->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted-light">No general expenses in this
                                period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total General Expenses:</td>
                            <td class="text-end fw-bold">৳ {{ number_format($expensesDetails->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <hr class="my-4">

                {{-- Salary Payments Table --}}
                <h6 class="mb-3">Salary Payments</h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Worker</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaryPaymentsDetails as $transaction)
                        {{-- <-- Change variable name --}}
                        <tr>
                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            <td>{{ $transaction->transactionable->monthlySalary->worker->name ?? 'N/A' }}</td>
                            <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted-light">No salary payments in this
                                period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Salary Payments:</td>
                            <td class="text-end fw-bold">
                                ৳ {{ number_format($salaryPaymentsDetails->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
{{-- Initialize Flatpickr for the date fields --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#start_date", {
            dateFormat: "Y-m-d"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d"
        });
    });
</script>
@endpush