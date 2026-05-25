@extends('layouts.app')
@section('title','Wards')
@section('page_title','Ward & Bed Management')
@section('breadcrumb','Home / Wards')
@section('content')
<div class="page-header">
    <h4 class="page-title">Wards & Beds</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('wards.admissions') }}" class="btn btn-outline-primary" style="border-radius:8px;font-size:13px;"><i class="bi bi-hospital me-1"></i>Admissions</a>
        <a href="{{ route('wards.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Add Ward</a>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-building"></i></div>
            <div class="stat-info"><div class="stat-label">Total Beds</div><div class="stat-value">{{ $totalBeds }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green"><div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info"><div class="stat-label">Available</div><div class="stat-value">{{ $availableBeds }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card red"><div class="stat-icon"><i class="bi bi-person-check"></i></div>
            <div class="stat-info"><div class="stat-label">Occupied</div><div class="stat-value">{{ $occupiedBeds }}</div></div></div>
    </div>
</div>
<div class="row g-4">
    @forelse($wards as $ward)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h6 class="fw-700 mb-1" style="color:var(--primary);">{{ $ward->name }}</h6>
                        <span class="badge bg-light text-dark border small">{{ $ward->ward_type ?? 'General' }}</span>
                    </div>
                    <span class="badge {{ $ward->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($ward->status) }}</span>
                </div>
                <div class="row g-0 text-center border rounded-2 overflow-hidden mb-3">
                    <div class="col-4 p-2 border-end">
                        <div class="fw-700" style="color:var(--primary);">{{ $ward->beds_count }}</div>
                        <div style="font-size:11px;color:#9ca3af;">Total</div>
                    </div>
                    <div class="col-4 p-2 border-end">
                        <div class="fw-700 text-success">{{ $ward->available_beds_count }}</div>
                        <div style="font-size:11px;color:#9ca3af;">Available</div>
                    </div>
                    <div class="col-4 p-2">
                        <div class="fw-700 text-danger">{{ $ward->beds_count - $ward->available_beds_count }}</div>
                        <div style="font-size:11px;color:#9ca3af;">Occupied</div>
                    </div>
                </div>
                <div class="small text-muted mb-3">Charge: ${{ number_format($ward->bed_charge_per_day,2) }}/day</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('wards.show', $ward) }}" class="btn btn-sm btn-outline-primary flex-fill" style="border-radius:7px;font-size:12px;"><i class="bi bi-eye me-1"></i>View Beds</a>
                    <a href="{{ route('wards.edit', $ward) }}" class="btn btn-sm btn-outline-warning" style="border-radius:7px;font-size:12px;"><i class="bi bi-pencil"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="empty-state card py-5"><i class="bi bi-building"></i><h6>No wards found</h6></div></div>
    @endforelse
</div>
<div class="mt-4">{{ $wards->links() }}</div>
@endsection
