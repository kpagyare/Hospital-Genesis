@extends('layouts.app')
@section('title','Revenue Report')
@section('page_title','Revenue Report')
@section('breadcrumb','Home / Reports / Revenue')
@section('content')
<div class="page-header">
    <h4 class="page-title">Revenue Report</h4>
    <a href="{{ route('reports.export', 'revenue') }}?{{ http_build_query(request()->all()) }}" class="btn-hms-primary" target="_blank">
        <i class="bi bi-file-pdf me-1"></i>Export PDF
    </a>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label small">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="paid"       {{ request('status')=='paid'      ?'selected':'' }}>Paid</option>
                    <option value="unpaid"     {{ request('status')=='unpaid'    ?'selected':'' }}>Unpaid</option>
                    <option value="partial"    {{ request('status')=='partial'   ?'selected':'' }}>Partial</option>
                    <option value="cancelled"  {{ request('status')=='cancelled' ?'selected':'' }}>Cancelled</option>
                </select></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary btn-sm">Filter</button>
                <a href="{{ route('reports.revenue') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-4 text-success">${{ number_format($stats['collected'], 0) }}</div>
                <div class="text-muted small">Collected</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-4 text-warning">${{ number_format($stats['outstanding'], 0) }}</div>
                <div class="text-muted small">Outstanding</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-4" style="color:var(--primary);">${{ number_format($stats['total_invoiced'], 0) }}</div>
                <div class="text-muted small">Total Invoiced</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-4 text-danger">${{ number_format($stats['expenses'], 0) }}</div>
                <div class="text-muted small">Total Expenses</div>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Monthly Revenue</h6></div>
    <div class="card-body"><canvas id="revenueChart" height="100"></canvas></div>
</div>
<div class="card">
    <div class="card-header"><h6 class="card-title">Invoice List</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Invoice #</th><th>Patient</th><th>Date</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($invoices as $inv)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $inv->invoice_number }}</span></td>
                        <td class="small">{{ $inv->patient->full_name ?? 'N/A' }}</td>
                        <td class="small">{{ $inv->invoice_date->format('d M Y') }}</td>
                        <td class="small fw-600">${{ number_format($inv->total_amount, 2) }}</td>
                        <td class="small text-success">${{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="small text-danger">${{ number_format($inv->total_amount - $inv->paid_amount, 2) }}</td>
                        <td>{!! $inv->status_badge !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $invoices->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const months  = @json(array_keys($monthly));
const revenue = @json(array_values($monthly));
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: { labels: months, datasets: [{ label: 'Revenue ($)', data: revenue, backgroundColor: 'rgba(10,35,66,0.75)', borderRadius: 6 }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
