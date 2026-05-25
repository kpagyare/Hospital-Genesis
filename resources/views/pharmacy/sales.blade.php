@extends('layouts.app')
@section('title','Medicine Sales')
@section('page_title','Medicine Sales')
@section('breadcrumb','Home / Pharmacy / Sales')
@section('content')
<div class="page-header">
    <h4 class="page-title">Medicine Sales</h4>
</div>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-bag-plus me-2"></i>New Sale</h6></div>
            <div class="card-body">
                <form action="{{ route('pharmacy.sales.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Patient (Optional)</label>
                        <select name="patient_id" class="form-select">
                            <option value="">-- Walk-in / No Patient --</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount ($)</label>
                        <input type="number" name="discount" value="0" min="0" step="0.01" class="form-control">
                    </div>

                    <!-- Items -->
                    <div id="saleItems">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="form-label mb-0">Items</label>
                            <button type="button" onclick="addSaleItem()" class="btn btn-sm btn-outline-primary" style="border-radius:7px;font-size:11px;"><i class="bi bi-plus"></i> Add</button>
                        </div>
                        <div id="saleItemsContainer">
                            <div class="row g-2 mb-2 sale-item">
                                <div class="col-7">
                                    <select name="items[0][medicine_id]" class="form-select form-select-sm" required>
                                        <option value="">Select Medicine</option>
                                        @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->stock_quantity }} left) ${{ $med->selling_price }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input type="number" name="items[0][quantity]" class="form-control form-control-sm" placeholder="Qty" min="1" value="1" required>
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <button type="button" onclick="removeSaleItem(this)" class="btn btn-sm btn-outline-danger p-1" style="border-radius:4px;"><i class="bi bi-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-hms-primary w-100 mt-3"><i class="bi bi-bag-check me-1"></i>Complete Sale</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Recent Sales</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Sale ID</th><th>Patient</th><th>Date</th><th>Total</th><th>Method</th></tr></thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $sale->sale_id }}</span></td>
                                <td class="small">{{ $sale->patient->full_name ?? 'Walk-in' }}</td>
                                <td class="small">{{ $sale->sale_date->format('d M Y') }}</td>
                                <td class="small fw-600 text-success">${{ number_format($sale->paid_amount,2) }}</td>
                                <td><span class="badge bg-info">{{ ucfirst(str_replace('_',' ',$sale->payment_method)) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No sales yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">{{ $sales->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let saleItemCount = 1;
const medicinesHtml = `@foreach($medicines as $med)<option value="{{ $med->id }}">{{ $med->name }} (${{ $med->selling_price }})</option>@endforeach`;
function addSaleItem() {
    const i = saleItemCount++;
    document.getElementById('saleItemsContainer').insertAdjacentHTML('beforeend',
        `<div class="row g-2 mb-2 sale-item">
            <div class="col-7"><select name="items[${i}][medicine_id]" class="form-select form-select-sm" required><option value="">Select Medicine</option>${medicinesHtml}</select></div>
            <div class="col-4"><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm" placeholder="Qty" min="1" value="1" required></div>
            <div class="col-1 d-flex align-items-center"><button type="button" onclick="removeSaleItem(this)" class="btn btn-sm btn-outline-danger p-1" style="border-radius:4px;"><i class="bi bi-x"></i></button></div>
        </div>`
    );
}
function removeSaleItem(btn) {
    const container = document.getElementById('saleItemsContainer');
    if (container.querySelectorAll('.sale-item').length > 1) btn.closest('.sale-item').remove();
}
</script>
@endpush
