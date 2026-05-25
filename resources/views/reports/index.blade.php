@extends('layouts.app')
@section('title','Reports')
@section('page_title','Reports')
@section('breadcrumb','Home / Reports')
@section('content')
<div class="page-header">
    <h4 class="page-title">Reports & Analytics</h4>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100" style="cursor:pointer;" onclick="location.href='{{ route('reports.patients') }}'">
            <div class="card-body text-center py-5">
                <div style="width:70px;height:70px;border-radius:50%;background:rgba(10,35,66,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-people fs-2" style="color:var(--primary);"></i>
                </div>
                <h5 class="mb-2">Patient Report</h5>
                <p class="text-muted small mb-3">Patient registrations, demographics, blood group distribution, and status overview.</p>
                <a href="{{ route('reports.patients') }}" class="btn-hms-primary">View Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="cursor:pointer;" onclick="location.href='{{ route('reports.revenue') }}'">
            <div class="card-body text-center py-5">
                <div style="width:70px;height:70px;border-radius:50%;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-currency-dollar fs-2" style="color:#10b981;"></i>
                </div>
                <h5 class="mb-2">Revenue Report</h5>
                <p class="text-muted small mb-3">Invoice totals, payment collection, outstanding balances, and expense tracking.</p>
                <a href="{{ route('reports.revenue') }}" class="btn-hms-primary">View Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="cursor:pointer;" onclick="location.href='{{ route('reports.appointments') }}'">
            <div class="card-body text-center py-5">
                <div style="width:70px;height:70px;border-radius:50%;background:rgba(230,57,70,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-calendar-check fs-2" style="color:var(--accent);"></i>
                </div>
                <h5 class="mb-2">Appointment Report</h5>
                <p class="text-muted small mb-3">Appointment counts by doctor, department, status, and date range analysis.</p>
                <a href="{{ route('reports.appointments') }}" class="btn-hms-primary">View Report</a>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mt-0">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-bar-chart me-2"></i>Quick Summary</h6></div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-md-3 col-6">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ \App\Models\Patient::count() }}</div>
                        <div class="text-muted small">Total Patients</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-700 fs-3 text-success">${{ number_format(\App\Models\Invoice::where('status','paid')->sum('total_amount'), 0) }}</div>
                        <div class="text-muted small">Total Revenue</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-700 fs-3" style="color:var(--accent);">{{ \App\Models\Appointment::count() }}</div>
                        <div class="text-muted small">Total Appointments</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="fw-700 fs-3 text-warning">{{ \App\Models\Invoice::where('status','unpaid')->count() }}</div>
                        <div class="text-muted small">Unpaid Invoices</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
