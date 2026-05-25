@extends('layouts.app')
@section('title','Create Invoice')
@section('page_title','Create Invoice')
@section('breadcrumb','Home / Billing / Create')

@section('content')
<div class="page-header">
    <h4 class="page-title">Create New Invoice</h4>
    <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<form action="{{ route('billing.store') }}" method="POST" id="invoiceForm">
@csrf
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Invoice Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select @error('patient_id')is-invalid@enderror" required>
                            <option value="">-- Select Patient --</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id')==$p->id ? 'selected':'' }}>{{ $p->full_name }} ({{ $p->patient_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Appointment (Optional)</label>
                        <select name="appointment_id" class="form-select">
                            <option value="">-- Select Appointment --</option>
                            @foreach($appointments as $apt)
                            <option value="{{ $apt->id }}">{{ $apt->appointment_id }} — {{ $apt->patient->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title">Invoice Items</h6>
                <button type="button" onclick="addItem()" class="btn btn-sm btn-outline-primary" style="border-radius:7px;font-size:12px;"><i class="bi bi-plus me-1"></i>Add Item</button>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Description</th><th>Type</th><th>Qty</th><th>Unit Price</th><th>Total</th><th></th></tr></thead>
                    <tbody id="itemsContainer">
                        <tr id="item-0">
                            <td><input type="text" name="items[0][description]" class="form-control" placeholder="Service description" required></td>
                            <td>
                                <select name="items[0][item_type]" class="form-select">
                                    <option value="consultation">Consultation</option>
                                    <option value="medicine">Medicine</option>
                                    <option value="lab_test">Lab Test</option>
                                    <option value="bed">Bed Charge</option>
                                    <option value="other">Other</option>
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" class="form-control item-qty" value="1" min="1" required onchange="calcRow(0)"></td>
                            <td><input type="number" name="items[0][unit_price]" class="form-control item-price" value="0" min="0" step="0.01" required onchange="calcRow(0)"></td>
                            <td><input type="text" name="items[0][total]" class="form-control item-total" value="0.00" readonly style="background:#f8fafc;"></td>
                            <td><button type="button" onclick="removeItem(0)" class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-x"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Summary</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Subtotal</span>
                    <span class="fw-600" id="displaySubtotal">$0.00</span>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Discount ($)</label>
                    <input type="number" name="discount" id="discountInput" value="0" min="0" step="0.01" class="form-control" onchange="calcTotal()">
                </div>
                <div class="mb-3">
                    <label class="form-label small">Tax ($)</label>
                    <input type="number" name="tax" id="taxInput" value="0" min="0" step="0.01" class="form-control" onchange="calcTotal()">
                </div>
                <div class="border-top pt-3 d-flex justify-content-between">
                    <span class="fw-700">Total</span>
                    <span class="fw-700 text-primary fs-5" id="displayTotal">$0.00</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control mb-3" rows="3" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                <button type="submit" class="btn-hms-primary w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Create Invoice</button>
                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let itemCount = 1;
function addItem() {
    const i = itemCount++;
    const row = `<tr id="item-${i}">
        <td><input type="text" name="items[${i}][description]" class="form-control" placeholder="Description" required></td>
        <td><select name="items[${i}][item_type]" class="form-select"><option value="consultation">Consultation</option><option value="medicine">Medicine</option><option value="lab_test">Lab Test</option><option value="bed">Bed Charge</option><option value="other">Other</option></select></td>
        <td><input type="number" name="items[${i}][quantity]" class="form-control item-qty" value="1" min="1" required onchange="calcRow(${i})"></td>
        <td><input type="number" name="items[${i}][unit_price]" class="form-control item-price" value="0" min="0" step="0.01" required onchange="calcRow(${i})"></td>
        <td><input type="text" name="items[${i}][total]" class="form-control item-total" value="0.00" readonly style="background:#f8fafc;"></td>
        <td><button type="button" onclick="removeItem(${i})" class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-x"></i></button></td>
    </tr>`;
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', row);
}
function removeItem(i) {
    const row = document.getElementById('item-' + i);
    if (row && document.querySelectorAll('#itemsContainer tr').length > 1) {
        row.remove(); calcTotal();
    }
}
function calcRow(i) {
    const row = document.getElementById('item-' + i);
    if (!row) return;
    const qty   = parseFloat(row.querySelector('.item-qty').value) || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const total = qty * price;
    row.querySelector('.item-total').value = total.toFixed(2);
    calcTotal();
}
function calcTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-total').forEach(el => { subtotal += parseFloat(el.value) || 0; });
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const tax      = parseFloat(document.getElementById('taxInput').value) || 0;
    const total    = subtotal - discount + tax;
    document.getElementById('displaySubtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('displayTotal').textContent = '$' + total.toFixed(2);
}
</script>
@endpush
