@extends('layouts.app')
@section('title','Edit Appointment')
@section('page_title','Edit Appointment')
@section('breadcrumb','Home / Appointments / Edit')

@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Appointment <small>{{ $appointment->appointment_id }}</small></h4>
    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-8">
<form action="{{ route('appointments.update', $appointment) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select" required>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id',$appointment->patient_id)==$p->id ? 'selected':'' }}>{{ $p->full_name }} ({{ $p->patient_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select" required>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id',$appointment->doctor_id)==$d->id ? 'selected':'' }}>{{ $d->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="appointment_date" value="{{ old('appointment_date',$appointment->appointment_date->format('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Time <span class="text-danger">*</span></label>
                <input type="time" name="appointment_time" value="{{ old('appointment_time',$appointment->appointment_time) }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    @foreach(['regular','emergency','follow_up'] as $t)
                    <option value="{{ $t }}" {{ old('type',$appointment->type)==$t ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                    <option value="{{ $s }}" {{ old('status',$appointment->status)==$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fee ($)</label>
                <input type="number" name="fee" value="{{ old('fee',$appointment->fee) }}" class="form-control" min="0" step="0.01">
            </div>
            <div class="col-12">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" value="{{ old('reason',$appointment->reason) }}" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes',$appointment->notes) }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-body border-top">
        <div class="d-flex gap-2">
            <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Update</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
        </div>
    </div>
</div>
</form>
</div></div>
@endsection
