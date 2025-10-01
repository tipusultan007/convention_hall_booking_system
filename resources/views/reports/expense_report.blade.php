@extends('layout.master')
@section('title', 'Expense by Category Report')

@section('content')
    {{-- Date Filter Form --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Expense by Category Report</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.expense_by_category') }}" method="GET" class="row align-items-end">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: The Pie Chart --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5>Visual Breakdown</h5>
                </div>
                <div class="card-body">
                    @if($totalExpenses > 0)
                        {{-- The canvas element where Chart.js will draw the chart --}}
                        <canvas id="expensePieChart" width="400" height="400"></canvas>
                    @else
                        <div class="text-center p-5">
                            <p class="text-muted-light">No expense data to display for this period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: The Summary Table --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5>Summary Table</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Total Spent</th>
                            <th class="text-end">% of Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($expensesByCategory as $category => $amount)
                            <tr>
                                <td>{{ $category }}</td>
                                <td class="text-end">৳{{ number_format($amount, 2) }}</td>
                                <td class="text-end">
                                    {{-- Calculate percentage, handle division by zero --}}
                                    @if($totalExpenses > 0)
                                        {{ number_format(($amount / $totalExpenses) * 100, 2) }}%
                                    @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No expenses found for this period.</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">৳{{ number_format($totalExpenses, 2) }}</td>
                            <td class="text-end">100.00%</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    {{-- Load the Chart.js library from a CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize Flatpickr for the date fields
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#start_date", { dateFormat: "Y-m-d" });
            flatpickr("#end_date", { dateFormat: "Y-m-d" });

            // --- Chart.js Initialization ---
            const ctx = document.getElementById('expensePieChart');

            // The controller passes the data as a JSON string
            const chartData = JSON.parse(@json($chartData));

            if (ctx && chartData.data.length > 0) {
                new Chart(ctx, {
                    type: 'doughnut', // 'pie' is also an option
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Amount Spent',
                            data: chartData.data,
                            backgroundColor: [ // Add some default colors
                                '#4A7AB8', '#A98B5C', '#d9534f', '#f9c74f',
                                '#5bc0de', '#5cb85c', '#6c757d', '#343a40'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Expenses by Category'
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
