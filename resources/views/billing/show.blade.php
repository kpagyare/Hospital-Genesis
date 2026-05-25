@extends('layouts.app')
@section('title', 'Invoice '.$invoice->invoice_number)
@section('page_title','Invoice Details')
@section('breadcrumb','Home / Billing / '.$invoice->invoice_number)

@section('content')
<div class="page-header">
    <h4 class="page-title">Invoice <small>{{ $invoice->invoice_number }}</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('billing.print', $invoice) }}" target="_blank" class="btn btn-outline-success" style="border-radius:8px;font-size:13px;"><i class="bi bi-printer me-1"></i>Print PDF</a>
        <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Invoice Card -->
        <div class="card">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-6">
                        <h5 class="fw-700" style="color:var(--primary);">INVOICE</h5>
                        <span class="badge bg-light text-dark border fs-6">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="col-6 text-end">
                        {!! $invoice->status_badge !!}
                        <div class="small text-muted mt-1">Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
                        @if($invoice->due_date)
                        <div class="small text-muted">Due: {{ $invoice->due_date->format('d M Y') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="small text-muted mb-1">Bill To:</div>
                        <div class="fw-600">{{ $invoice->patient->full_name ?? 'N/A' }}</div>
                        <div class="small text-muted">{{ $invoice->patient->patient_id ?? '' }}</div>
                        <div class="small text-muted">{{ $invoice->patient->phone ?? '' }}</div>
                    </div>
                    <div class="col-6 text-end">
                        @php $settings = \App\Models\Setting::first(); @endphp
                        <div class="fw-600">{{ $settings->hospital_name ?? config('app.name') }}</div>
                        <div class="small text-muted">{{ $settings->hospital_phone ?? '' }}</div>
                        <div class="small text-muted">{{ $settings->hospital_email ?? '' }}</div>
                    </div>
                </div>
                <table class="table table-sm">
                    <thead><tr><th>Description</th><th>Type</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="small"><span class="badge bg-light text-dark border">{{ ucfirst($item->item_type) }}</span></td>
                            <td class="text-center small">{{ $item->quantity }}</td>
                            <td class="text-end small">${{ number_format($item->unit_price,2) }}</td>
                            <td class="text-end small fw-600">${{ number_format($item->total,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="4" class="text-end small">Subtotal</td><td class="text-end small">${{ number_format($invoice->subtotal,2) }}</td></tr>
                        @if($invoice->discount > 0)<tr><td colspan="4" class="text-end small text-success">Discount</td><td class="text-end small text-success">-${{ number_format($invoice->discount,2) }}</td></tr>@endif
                        @if($invoice->tax > 0)<tr><td colspan="4" class="text-end small">Tax</td><td class="text-end small">${{ number_format($invoice->tax,2) }}</td></tr>@endif
                        <tr class="fw-700"><td colspan="4" class="text-end">TOTAL</td><td class="text-end" style="color:var(--primary);">${{ number_format($invoice->total_amount,2) }}</td></tr>
                        <tr><td colspan="4" class="text-end text-success small">Paid</td><td class="text-end text-success small">${{ number_format($invoice->paid_amount,2) }}</td></tr>
                        <tr><td colspan="4" class="text-end fw-600 {{ $invoice->due_amount > 0 ? 'text-danger' : '' }}">Balance Due</td><td class="text-end fw-600 {{ $invoice->due_amount > 0 ? 'text-danger' : '' }}">${{ number_format($invoice->due_amount,2) }}</td></tr>
                    </tfoot>
                </table>
                @if($invoice->notes)
                <div class="mt-3 p-3 rounded" style="background:#f8fafc;"><strong class="small">Notes:</strong><p class="small text-muted mb-0">{{ $invoice->notes }}</p></div>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header"><h6 class="card-title">Payment History</h6></div>
            <div class="card-body p-0">
                @if($invoice->payments->isEmpty())
                    <div class="empty-state py-4"><i class="bi bi-cash"></i><h6>No payments yet</h6></div>
                @else
                <table class="table mb-0">
                    <thead><tr><th>ID</th><th>Date</th><th>Amount</th><th>Method</th><th>Received By</th></tr></thead>
                    <tbody>
                        @foreach($invoice->payments as $pay)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $pay->payment_id }}</span></td>
                            <td class="small">{{ $pay->payment_date->format('d M Y') }}</td>
                            <td class="small fw-600 text-success">${{ number_format($pay->amount,2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst(str_replace('_',' ',$pay->payment_method)) }}</span></td>
                            <td class="small">{{ $pay->receivedBy->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Payment -->
    @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title text-success"><i class="bi bi-cash-stack me-2"></i>Record Payment</h6></div>
            <div class="card-body">
                <form action="{{ route('billing.payment', $invoice) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount" class="form-control" value="{{ $invoice->due_amount }}" min="0.01" max="{{ $invoice->due_amount }}" step="0.01" required>
                        </div>
                        <div class="form-text">Max: ${{ number_format($invoice->due_amount,2) }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="insurance">Insurance</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference</label>
                        <input type="text" name="transaction_reference" class="form-control" placeholder="Transaction ref (optional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn-hms-accent w-100"><i class="bi bi-check-circle me-1"></i>Record Payment</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
