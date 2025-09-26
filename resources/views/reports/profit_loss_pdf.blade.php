<!DOCTYPE html>
<html lang="bn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Profit & Loss Report</title>
    <style>
        @php
     function pdf_font_path($file) {
         return 'file:///' . str_replace('\\', '/', public_path('fonts/' . $file));
     }
 @endphp

@font-face {
            font-family: 'SolaimanLipi';
            src: url("{{ pdf_font_path('SolaimanLipi.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }


        body {
            font-family: 'SolaimanLipi', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 24px; color: #4A7AB8; } /* Brand Color */
        .header h3 { margin: 5px 0; color: #555; font-size: 16px; }
        .header p { margin: 0; }

        table { width: 100%; border-collapse: collapse; }

        .summary-table { margin-bottom: 25px; }
        .summary-table td { border: 1px solid #dee2e6; padding: 12px; text-align: center; }
        .summary-table .label { font-size: 11px; text-transform: uppercase; color: #6c757d; }
        .summary-table .value { font-size: 16px; font-weight: bold; }

        /* Main layout table for columns */
        .main-layout td { vertical-align: top; padding: 0 8px; }
        .main-layout .column-left { padding-left: 0; }
        .main-layout .column-right { padding-right: 0; }

        .section-title {
            font-size: 14px;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #4A7AB8; /* Brand Color */
            color: #4A7AB8; /* Brand Color */
            font-weight: bold;
        }

        .breakdown-table { margin-bottom: 20px; }
        .breakdown-table th, .breakdown-table td { border-bottom: 1px solid #dee2e6; padding: 8px 6px; text-align: left; }
        .breakdown-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-size: 9px;
            text-transform: uppercase;
            color: #6c757d;
        }
        .text-end { text-align: right !important; }

        .breakdown-table tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        .sub-heading {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Momota Community Center</h1>
    <h3>Profit & Loss Report</h3>
    <p>For the period: <strong>{{ $startDate->format('F d, Y') }}</strong> to <strong>{{ $endDate->format('F d, Y') }}</strong></p>
</div>

<table class="summary-table">
    <tr>
        <td class="label">Total Revenue</td>
        <td class="label">Total Expenses</td>
        <td class="label">Net Profit / Loss</td>
    </tr>
    <tr>
        <td class="value" style="color: #198754;">৳ {{ number_format($totalRevenue, 2) }}</td>
        <td class="value" style="color: #d9534f;">৳ {{ number_format($totalExpenses, 2) }}</td>
        <td class="value" style="color: {{ $netProfit >= 0 ? '#4A7AB8' : '#f9c74f' }};">৳ {{ number_format($netProfit, 2) }}</td>
    </tr>
</table>

<table class="main-layout">
    <tr>
        {{-- Left Column: Revenue --}}
        <td width="50%" class="column-left">
            <h4 class="section-title">Revenue Breakdown</h4>

            <div class="sub-heading">Booking Revenue</div>
            <table class="breakdown-table">
                <thead><tr><th>Date</th><th>Customer</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                @forelse ($bookingDetails as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $transaction->transactionable->booking->customer->name ?? 'N/A' }}</td>
                        <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; padding: 10px; color: #999;">No bookings in this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" class="text-end">Total Booking Revenue:</td>
                    <td class="text-end">৳ {{ number_format($bookingDetails->sum('amount'), 2) }}</td>
                </tr>
                </tfoot>
            </table>

            <div class="sub-heading">Other Income</div>
            <table class="breakdown-table">
                <thead><tr><th>Date</th><th>Category</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                @forelse ($otherIncomeDetails as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $transaction->transactionable->category->name ?? 'N/A' }}</td>
                        <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; padding: 10px; color: #999;">No other income in this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" class="text-end">Total Other Income:</td>
                    <td class="text-end">৳ {{ number_format($otherIncomeDetails->sum('amount'), 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </td>

        {{-- Right Column: Expenses --}}
        <td width="50%" class="column-right">
            <h4 class="section-title">Expense Breakdown</h4>

            <div class="sub-heading">General Expenses</div>
            <table class="breakdown-table">
                <thead><tr><th>Date</th><th>Category</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                @forelse ($expensesDetails as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $transaction->transactionable->category->name ?? 'N/A' }}</td>
                        <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; padding: 10px; color: #999;">No general expenses in this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" class="text-end">Total General Expenses:</td>
                    <td class="text-end">৳ {{ number_format($expensesDetails->sum('amount'), 2) }}</td>
                </tr>
                </tfoot>
            </table>

            <div class="sub-heading">Salary Payments</div>
            <table class="breakdown-table">
                <thead><tr><th>Date</th><th>Worker</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                @forelse ($salaryPaymentsDetails as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $transaction->transactionable->monthlySalary->worker->name ?? 'N/A' }}</td>
                        <td class="text-end">৳ {{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; padding: 10px; color: #999;">No salary payments in this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" class="text-end">Total Salary Payments:</td>
                    <td class="text-end">৳ {{ number_format($salaryPaymentsDetails->sum('amount'), 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
