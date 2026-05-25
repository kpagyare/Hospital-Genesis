<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoice->invoice_number }}</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
    .header { background: #0a2342; color: #fff; padding: 20px; margin-bottom: 20px; }
    .hospital-name { font-size: 22px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th { background: #f5f5f5; padding: 8px; border: 1px solid #ddd; text-align: left; }
    td { padding: 8px; border: 1px solid #ddd; }
    .total-row { font-weight: bold; font-size: 15px; }
    .text-right { text-align: right; }
</style>
</head>
<body>
<div class="header">
    <div class="hospital-name">{{ $settings->hospital_name ?? 'Hospital Management System' }}</div>
    <div>{{ $settings->hospital_address ?? '' }} | {{ $settings->hospital_phone ?? '' }}</div>
</div>

<div style="display:flex;justify-content:space-between;margin-bottom:20px;">
    <div>
        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
        <strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}<br>
        <strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$invoice->status)) }}
    </div>
    <div>
        <strong>Bill To:</strong><br>
        {{ $invoice->patient->full_name ?? 'N/A' }}<br>
        {{ $invoice->patient->phone ?? '' }}<br>
        {{ $invoice->patient->patient_id ?? '' }}
    </div>
</div>

<table>
    <thead><tr><th>Description</th><th>Type</th><th class="text-right">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Total</th></tr></thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td>{{ ucfirst($item->item_type) }}</td>
            <td class="text-right">{{ $item->quantity }}</td>
            <td class="text-right">${{ number_format($item->unit_price,2) }}</td>
            <td class="text-right">${{ number_format($item->total,2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="4" class="text-right">Subtotal</td><td class="text-right">${{ number_format($invoice->subtotal,2) }}</td></tr>
        @if($invoice->discount > 0)<tr><td colspan="4" class="text-right">Discount</td><td class="text-right">-${{ number_format($invoice->discount,2) }}</td></tr>@endif
        @if($invoice->tax > 0)<tr><td colspan="4" class="text-right">Tax</td><td class="text-right">${{ number_format($invoice->tax,2) }}</td></tr>@endif
        <tr class="total-row"><td colspan="4" class="text-right">TOTAL</td><td class="text-right">${{ number_format($invoice->total_amount,2) }}</td></tr>
        <tr><td colspan="4" class="text-right">Paid</td><td class="text-right">${{ number_format($invoice->paid_amount,2) }}</td></tr>
        <tr class="total-row"><td colspan="4" class="text-right">Balance Due</td><td class="text-right">${{ number_format($invoice->due_amount,2) }}</td></tr>
    </tfoot>
</table>
@if($invoice->notes)<p><strong>Notes:</strong> {{ $invoice->notes }}</p>@endif
<div style="margin-top:40px;text-align:center;color:#666;font-size:12px;">{{ $settings->footer_text ?? 'Thank you for choosing our hospital.' }}</div>
</body>
</html>
