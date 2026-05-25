@extends('layouts.app')
@section('title', $ward->name)
@section('page_title','Ward Details')
@section('breadcrumb','Home / Wards / '.$ward->name)
@section('content')
<div class="page-header">
    <h4 class="page-title">{{ $ward->name }} <small>{{ $ward->ward_type }}</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('wards.edit', $ward) }}" class="btn-hms-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('wards.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3 text-center">
            @php $available = $ward->beds->where('status','available')->count(); @endphp
            <div class="col-md-4"><div class="fw-700 fs-3" style="color:var(--primary);">{{ $ward->beds->count() }}</div><div class="text-muted small">Total Beds</div></div>
            <div class="col-md-4"><div class="fw-700 fs-3 text-success">{{ $available }}</div><div class="text-muted small">Available</div></div>
            <div class="col-md-4"><div class="fw-700 fs-3 text-danger">{{ $ward->beds->count() - $available }}</div><div class="text-muted small">Occupied</div></div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="card-title">Bed Status</h6></div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($ward->beds as $bed)
            <div class="col-md-3 col-sm-4 col-6">
                <div class="p-3 rounded text-center" style="border:2px solid {{ $bed->status==='available' ? '#10b981' : ($bed->status==='occupied' ? '#e63946' : '#f59e0b') }};background:{{ $bed->status==='available' ? '#d1fae5' : ($bed->status==='occupied' ? '#fee2e2' : '#fef3c7') }};">
                    <i class="bi bi-hospital fs-3" style="color:{{ $bed->status==='available' ? '#10b981' : ($bed->status==='occupied' ? '#e63946' : '#f59e0b') }};"></i>
                    <div class="fw-600 small mt-1">{{ $bed->bed_number }}</div>
                    <div style="font-size:11px;color:#6b7280;">{{ ucfirst($bed->status) }}</div>
                    @if($bed->status === 'occupied' && $bed->currentAdmission)
                    <div style="font-size:10px;margin-top:4px;color:#4a5568;">{{ $bed->currentAdmission->patient->full_name ?? 'Patient' }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
