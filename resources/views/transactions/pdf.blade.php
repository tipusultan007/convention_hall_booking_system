<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Ledger</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 0; color: #555; }
        .ledger-table { width: 100%; border-collapse: collapse; }
        .ledger-table th, .ledger-table td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: middle; }
        .ledger-table thead tr { background-color: #f2f2f2; }
        .text-end { text-align: right !important; }
        .fw-bold { font-weight: bold; }
        .text-danger { color: #d9534f; }
        .text-success { color: #198754; }
        /* A class to prevent long descriptions from breaking the layout */
        .description-cell { max-width: 250px; word-wrap: break-word; }
    </style>
</head>
<body>
<div class="header">
    <h2>Transaction Ledger</h2>
    <p>For the period: <strong>{{ $startDate->format('F d, Y') }}</strong> to <strong>{{ $endDate->format('F d, Y') }}</strong></p>
</div>

<table class="ledger-table">
    <thead>
    <tr>
        <th width="12%">Date</th>
        <th>Description</th>
        <th width="15%" class="text-end">Debit (Outgoing)</th>
        <th width="15%" class="text-end">Credit (Incoming)</th>
        <th width="18%" class="text-end">Balance</th>
    </tr>
    </thead>
    <tbody>
    {{-- Opening Balance Row --}}
    <tr style="background-color: #f9f9f9;">
        <td colspan="4" class="fw-bold">Opening Balance</td>
        <td class="text-end fw-bold">৳{{ number_format($openingBalance, 2) }}</td>
    </tr>

    {{-- Loop through all transactions for the period --}}
    @foreach ($transactions as $transaction)
        <tr>
            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
            <td class="description-cell">{{ $transaction->description }}</td>
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
            <td class="text-end fw-bold">৳{{ number_format($transaction->balance, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    {{-- Closing Balance Row --}}
    <tr style="background-color: #f2f2f2;">
        <td colspan="4" class="text-end fw-bold">Closing Balance</td>
        <td class="text-end fw-bold">৳{{ number_format($closingBalance, 2) }}</td>
    </tr>
    </tfoot>
</table>
</body>
</html>
