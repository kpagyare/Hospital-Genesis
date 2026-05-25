@extends('layouts.app')
@section('title','Billing')
@section('page_title','Billing & Accounts')
@section('breadcrumb','Home / Billing')

@section('content')
<div class="page-header">
    <h4 class="page-title">Invoices & Billing</h4>
    <a href="{{ route('billing.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>New Invoice</a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($totalRevenue,0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-info">
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-value">${{ number_format($monthlyRevenue,0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card red">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-info">
                <div class="stat-label">Pending Amount</div>
                <div class="stat-value">${{ number_format($pendingAmount,0) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by invoice number or patient...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['draft','sent','partially_paid','paid','overdue','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($invoices->isEmpty())
            <div class="empty-state"><i class="bi bi-receipt"></i><h6>No invoices found</h6></div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $inv->invoice_number }}</span></td>
                        <td>
                            <div class="table-name">{{ $inv->patient->full_name ?? 'N/A' }}</div>
                            <div class="table-sub">{{ $inv->patient->patient_id ?? '' }}</div>
                        </td>
                        <td class="small">{{ $inv->invoice_date->format('d M Y') }}</td>
                        <td class="small fw-600">${{ number_format($inv->total_amount,2) }}</td>
                        <td class="small text-success">${{ number_format($inv->paid_amount,2) }}</td>
                        <td class="small {{ $inv->due_amount > 0 ? 'text-danger fw-600' : '' }}">${{ number_format($inv->due_amount,2) }}</td>
                        <td>{!! $inv->status_badge !!}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('billing.show', $inv) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('billing.print', $inv) }}" class="btn btn-sm btn-outline-success" style="border-radius:6px;" title="Print" target="_blank"><i class="bi bi-printer"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $invoices->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
