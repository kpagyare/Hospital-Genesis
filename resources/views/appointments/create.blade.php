@extends('layouts.app')
@section('title','Book Appointment')
@section('page_title','Book Appointment')
@section('breadcrumb','Home / Appointments / Book')

@section('content')
<div class="page-header">
    <h4 class="page-title">Book New Appointment</h4>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center">
<div class="col-lg-8">
<form action="{{ route('appointments.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header"><h6 class="card-title"><i class="bi bi-calendar-plus me-2"></i>Appointment Details</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select @error('patient_id')is-invalid@enderror" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id',request('patient_id'))==$p->id ? 'selected':'' }}>{{ $p->full_name }} ({{ $p->patient_id }})</option>
                    @endforeach
                </select>
                @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select @error('doctor_id')is-invalid@enderror" required id="doctorSelect">
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" data-fee="{{ $d->consultation_fee }}" {{ old('doctor_id',request('doctor_id'))==$d->id ? 'selected':'' }}>{{ $d->full_name }} — {{ $d->specialization }}</option>
                    @endforeach
                </select>
                @error('doctor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                <input type="date" name="appointment_date" value="{{ old('appointment_date', date('Y-m-d')) }}" class="form-control @error('appointment_date')is-invalid@enderror" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Time <span class="text-danger">*</span></label>
                <input type="time" name="appointment_time" value="{{ old('appointment_time', '09:00') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Appointment Type</label>
                <select name="type" class="form-select">
                    <option value="regular"   {{ old('type')=='regular'   ? 'selected':'' }}>Regular</option>
                    <option value="emergency" {{ old('type')=='emergency' ? 'selected':'' }}>Emergency</option>
                    <option value="follow_up" {{ old('type')=='follow_up' ? 'selected':'' }}>Follow Up</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Consultation Fee ($)</label>
                <input type="number" name="fee" id="feeInput" value="{{ old('fee',0) }}" class="form-control" min="0" step="0.01">
                <div class="form-text">Auto-filled from doctor's fee. You can override.</div>
            </div>
            <div class="col-12">
                <label class="form-label">Reason for Visit</label>
                <input type="text" name="reason" value="{{ old('reason') }}" class="form-control" placeholder="e.g. Chest pain, Fever, Annual check-up...">
            </div>
            <div class="col-12">
                <label class="form-label">Additional Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-body border-top">
        <div class="d-flex gap-2">
            <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Book Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
        </div>
    </div>
</div>
</form>
</div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('doctorSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const fee = opt.dataset.fee || 0;
    document.getElementById('feeInput').value = fee;
});
</script>
@endpush
