<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Revenue Report</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a202c; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 3px solid #0a2342; padding-bottom: 12px; margin-bottom: 20px; }
    .header h1 { font-size: 20px; color: #0a2342; margin: 0 0 4px; }
    .header p { margin: 2px 0; color: #555; font-size: 10px; }
    .report-title { font-size: 15px; font-weight: bold; color: #0a2342; margin-bottom: 16px; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    thead tr { background: #0a2342; color: #fff; }
    th { padding: 8px 6px; text-align: left; font-size: 10px; }
    td { padding: 6px; border-bottom: 1px solid #f0f0f0; font-size: 10px; }
    tr:nth-child(even) { background: #f8fafc; }
    .stat-row td { border: 1px solid #e2e8f0; border-radius: 4px; text-align: center; padding: 10px 6px; }
    .stat-num { font-size: 16px; font-weight: bold; }
    .stat-label { font-size: 9px; color: #6b7280; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
    .badge-paid     { background: #d1fae5; color: #065f46; }
    .badge-unpaid   { background: #fee2e2; color: #991b1b; }
    .badge-partial  { background: #fef3c7; color: #92400e; }
    .badge-cancelled{ background: #f1f5f9; color: #475569; }
    .summary-table { margin: 16px 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; }
    .footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; font-size: 9px; color: #9ca3af; text-align: center; }
    .text-right { text-align: right; }
    .text-success { color: #10b981; }
    .text-danger  { color: #e63946; }
    .text-warning { color: #f59e0b; }
    .filters { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 16px; font-size: 10px; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $settings->hospital_name ?? 'Hospital Management System' }}</h1>
    <p>{{ $settings->address ?? '' }}</p>
    <p>{{ $settings->phone ?? '' }} | {{ $settings->email ?? '' }}</p>
</div>
<div class="report-title">REVENUE REPORT</div>
@if(request('from') || request('to') || request('status'))
<div class="filters">
    <strong>Filters:</strong>
    @if(request('from')) From: {{ request('from') }} @endif
    @if(request('to')) To: {{ request('to') }} @endif
    @if(request('status')) Status: {{ ucfirst(request('status')) }} @endif
</div>
@endif
<table class="summary-table">
    <tr class="stat-row">
        <td style="width:25%;"><div class="stat-num text-success">${{ number_format($stats['collected'], 2) }}</div><div class="stat-label">Collected</div></td>
        <td style="width:25%;"><div class="stat-num text-warning">${{ number_format($stats['outstanding'], 2) }}</div><div class="stat-label">Outstanding</div></td>
        <td style="width:25%;"><div class="stat-num" style="color:#0a2342;">${{ number_format($stats['total_invoiced'], 2) }}</div><div class="stat-label">Total Invoiced</div></td>
        <td style="width:25%;"><div class="stat-num text-danger">${{ number_format($stats['expenses'], 2) }}</div><div class="stat-label">Expenses</div></td>
    </tr>
</table>
<table>
    <thead>
        <tr><th>Invoice #</th><th>Patient</th><th>Date</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Balance</th><th>Status</th></tr>
    </thead>
    <tbody>
        @php $grandTotal = 0; $grandPaid = 0; @endphp
        @forelse($invoices as $inv)
        @php $grandTotal += $inv->total_amount; $grandPaid += $inv->paid_amount; @endphp
        <tr>
            <td>{{ $inv->invoice_number }}</td>
            <td>{{ $inv->patient->full_name ?? 'N/A' }}</td>
            <td>{{ $inv->invoice_date->format('d M Y') }}</td>
            <td class="text-right">${{ number_format($inv->total_amount, 2) }}</td>
            <td class="text-right text-success">${{ number_format($inv->paid_amount, 2) }}</td>
            <td class="text-right text-danger">${{ number_format($inv->total_amount - $inv->paid_amount, 2) }}</td>
            <td>
                <span class="badge badge-{{ $inv->status }}">{{ ucfirst($inv->status) }}</span>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:20px;">No invoices found.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background:#0a2342;color:#fff;font-weight:bold;">
            <td colspan="3">TOTAL</td>
            <td class="text-right">${{ number_format($grandTotal, 2) }}</td>
            <td class="text-right">${{ number_format($grandPaid, 2) }}</td>
            <td class="text-right">${{ number_format($grandTotal - $grandPaid, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
<div class="footer">
    Generated on {{ date('d M Y, H:i') }} | {{ $settings->hospital_name ?? 'HMS' }}
</div>
</body>
</html>
