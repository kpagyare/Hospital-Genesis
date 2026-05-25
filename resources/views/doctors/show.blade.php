@extends('layouts.app')
@section('title', $doctor->full_name)
@section('page_title','Doctor Profile')
@section('breadcrumb','Home / Doctors / '.$doctor->full_name)

@section('content')
<div class="page-header">
    <h4 class="page-title">Doctor Profile <small>{{ $doctor->doctor_id }}</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('doctors.edit', $doctor) }}" class="btn-hms-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <img src="{{ $doctor->photo_url }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);margin-bottom:12px;">
                <h5 class="fw-700" style="color:var(--primary);">{{ $doctor->full_name }}</h5>
                <p class="text-accent fw-600 small">{{ $doctor->specialization }}</p>
                <span class="badge {{ $doctor->status==='active' ? 'bg-success' : 'bg-warning text-dark' }} mb-3">{{ ucfirst(str_replace('_',' ',$doctor->status)) }}</span>
                <div class="border-top pt-3 text-start small">
                    <div class="mb-2"><i class="bi bi-award me-2 text-primary"></i><strong>Qualification:</strong> {{ $doctor->qualification ?? 'N/A' }}</div>
                    <div class="mb-2"><i class="bi bi-clock me-2 text-primary"></i><strong>Experience:</strong> {{ $doctor->experience_years }} Years</div>
                    <div class="mb-2"><i class="bi bi-currency-dollar me-2 text-success"></i><strong>Fee:</strong> ${{ number_format($doctor->consultation_fee,2) }}</div>
                    @if($doctor->phone)<div class="mb-2"><i class="bi bi-telephone me-2 text-success"></i>{{ $doctor->phone }}</div>@endif
                    @if($doctor->available_days)
                    <div class="mb-2"><i class="bi bi-calendar me-2 text-primary"></i>{{ implode(', ',$doctor->available_days) }}</div>
                    @endif
                    @if($doctor->available_from && $doctor->available_to)
                    <div><i class="bi bi-alarm me-2 text-warning"></i>{{ $doctor->available_from }} — {{ $doctor->available_to }}</div>
                    @endif
                </div>
            </div>
        </div>
        @if($doctor->bio)
        <div class="card">
            <div class="card-header"><h6 class="card-title">About</h6></div>
            <div class="card-body"><p class="text-muted small mb-0">{{ $doctor->bio }}</p></div>
        </div>
        @endif
    </div>
    <div class="col-lg-8">
        <!-- Today's Appointments -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-calendar-day me-2"></i>Today's Appointments ({{ $todayAppointments->count() }})</h6></div>
            <div class="card-body p-0">
                @if($todayAppointments->isEmpty())
                    <div class="empty-state py-4"><i class="bi bi-calendar-x"></i><h6>No appointments today</h6></div>
                @else
                <table class="table mb-0">
                    <thead><tr><th>Patient</th><th>Time</th><th>Type</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($todayAppointments as $apt)
                        <tr>
                            <td class="small fw-600">{{ $apt->patient->full_name ?? 'N/A' }}</td>
                            <td class="small">{{ $apt->appointment_time }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($apt->type) }}</span></td>
                            <td>{!! $apt->status_badge !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        <!-- Recent Appointments -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">All Appointments ({{ $doctor->appointments->count() }})</h6>
                <a href="{{ route('appointments.create') }}?doctor_id={{ $doctor->id }}" class="btn btn-sm btn-outline-primary" style="border-radius:7px;font-size:12px;">Book New</a>
            </div>
            <div class="card-body p-0">
                @if($doctor->appointments->isEmpty())
                    <div class="empty-state py-4"><i class="bi bi-calendar-x"></i><h6>No appointments yet</h6></div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>ID</th><th>Patient</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($doctor->appointments->sortByDesc('appointment_date')->take(10) as $apt)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $apt->appointment_id }}</span></td>
                                <td class="small">{{ $apt->patient->full_name ?? 'N/A' }}</td>
                                <td class="small">{{ $apt->appointment_date->format('d M Y') }}</td>
                                <td>{!! $apt->status_badge !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
