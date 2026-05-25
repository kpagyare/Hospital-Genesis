@extends('layouts.app')
@section('title','Appointments')
@section('page_title','Appointment Management')
@section('breadcrumb','Home / Appointments')

@section('content')
<div class="page-header">
    <h4 class="page-title">All Appointments <small>{{ $appointments->total() }} total</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.calendar') }}" class="btn btn-outline-primary" style="border-radius:8px;font-size:13px;"><i class="bi bi-calendar3 me-1"></i>Calendar</a>
        <a href="{{ route('appointments.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Book Appointment</a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search patient, doctor...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="doctor_id" class="form-select">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ request('doctor_id')==$doc->id ? 'selected':'' }}>{{ $doc->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($appointments->isEmpty())
            <div class="empty-state"><i class="bi bi-calendar-x"></i><h6>No appointments found</h6></div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $apt)
                    <tr>
                        <td><span class="badge bg-light text-dark border small">{{ $apt->appointment_id }}</span></td>
                        <td>
                            <div class="table-name">{{ $apt->patient->full_name ?? 'N/A' }}</div>
                            <div class="table-sub">{{ $apt->patient->patient_id ?? '' }}</div>
                        </td>
                        <td>
                            <div class="table-name">{{ $apt->doctor->full_name ?? 'N/A' }}</div>
                            <div class="table-sub">{{ $apt->doctor->specialization ?? '' }}</div>
                        </td>
                        <td>
                            <div class="fw-600 small">{{ $apt->appointment_date->format('d M Y') }}</div>
                            <div class="table-sub">{{ $apt->appointment_time }}</div>
                        </td>
                        <td><span class="badge bg-info">{{ ucfirst($apt->type) }}</span></td>
                        <td class="small fw-600">${{ number_format($apt->fee,2) }}</td>
                        <td>{!! $apt->status_badge !!}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('appointments.show',$apt) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('appointments.edit',$apt) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('appointments.destroy',$apt) }}" method="POST" onsubmit="return confirm('Delete appointment?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $appointments->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
