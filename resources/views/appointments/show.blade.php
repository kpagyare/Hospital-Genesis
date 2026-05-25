@extends('layouts.app')
@section('title','Appointment Details')
@section('page_title','Appointment Details')
@section('breadcrumb','Home / Appointments / '.$appointment->appointment_id)

@section('content')
<div class="page-header">
    <h4 class="page-title">Appointment <small>{{ $appointment->appointment_id }}</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn-hms-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Appointment Info</h6></div>
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">ID</span>
                    <span class="badge bg-light text-dark border">{{ $appointment->appointment_id }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Date</span>
                    <span class="small fw-600">{{ $appointment->appointment_date->format('d M Y') }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Time</span>
                    <span class="small fw-600">{{ $appointment->appointment_time }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Type</span>
                    <span class="badge bg-info">{{ ucfirst($appointment->type) }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Status</span>
                    {!! $appointment->status_badge !!}
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Fee</span>
                    <span class="small fw-600">${{ number_format($appointment->fee,2) }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small">Paid</span>
                    <span class="badge {{ $appointment->is_paid ? 'bg-success' : 'bg-warning text-dark' }}">{{ $appointment->is_paid ? 'Yes' : 'No' }}</span>
                </div>
                @if($appointment->reason)
                <div class="mt-3 p-3 rounded" style="background:#f8fafc;">
                    <div class="small fw-600 mb-1 text-primary">Reason for Visit</div>
                    <div class="small text-muted">{{ $appointment->reason }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Update Status -->
        <div class="card">
            <div class="card-header"><h6 class="card-title">Update Status</h6></div>
            <div class="card-body">
                <form action="{{ route('appointments.status', $appointment) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                        <option value="{{ $s }}" {{ $appointment->status==$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-hms-primary w-100">Update Status</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Patient & Doctor -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2"></i>Patient</h6></div>
                    <div class="card-body text-center">
                        <img src="{{ $appointment->patient->photo_url ?? asset('assets/images/default-patient.png') }}" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);margin-bottom:8px;">
                        <div class="fw-600">{{ $appointment->patient->full_name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $appointment->patient->patient_id ?? '' }}</div>
                        @if($appointment->patient)
                        <a href="{{ route('patients.show', $appointment->patient) }}" class="btn btn-sm btn-outline-primary mt-2" style="border-radius:7px;font-size:12px;">View Profile</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header"><h6 class="card-title"><i class="bi bi-person-badge me-2"></i>Doctor</h6></div>
                    <div class="card-body text-center">
                        <img src="{{ $appointment->doctor->photo_url ?? asset('assets/images/default-doctor.png') }}" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);margin-bottom:8px;">
                        <div class="fw-600">{{ $appointment->doctor->full_name ?? 'N/A' }}</div>
                        <div class="text-accent small">{{ $appointment->doctor->specialization ?? '' }}</div>
                        @if($appointment->doctor)
                        <a href="{{ route('doctors.show', $appointment->doctor) }}" class="btn btn-sm btn-outline-primary mt-2" style="border-radius:7px;font-size:12px;">View Profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($appointment->notes)
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title">Notes</h6></div>
            <div class="card-body"><p class="text-muted small mb-0">{{ $appointment->notes }}</p></div>
        </div>
        @endif

        <!-- Prescription -->
        @if($appointment->prescription)
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-prescription2 me-2"></i>Prescription — {{ $appointment->prescription->prescription_id }}</h6></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Qty</th></tr></thead>
                    <tbody>
                        @foreach($appointment->prescription->items as $item)
                        <tr>
                            <td class="small fw-600">{{ $item->medicine->name ?? 'N/A' }}</td>
                            <td class="small">{{ $item->dosage }}</td>
                            <td class="small">{{ $item->frequency }}</td>
                            <td class="small">{{ $item->duration_days }}d</td>
                            <td class="small">{{ $item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
