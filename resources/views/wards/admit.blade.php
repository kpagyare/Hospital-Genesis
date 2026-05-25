@extends('layouts.app')
@section('title','Admit Patient')
@section('page_title','Admit Patient')
@section('breadcrumb','Home / Wards / Admissions / Admit')
@section('content')
<div class="page-header">
    <h4 class="page-title">Admit New Patient</h4>
    <a href="{{ route('wards.admissions') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<form action="{{ route('wards.admissions.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header"><h6 class="card-title"><i class="bi bi-hospital me-2"></i>Admission Details</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select @error('patient_id')is-invalid@enderror" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id')==$p->id?'selected':'' }}>{{ $p->full_name }} ({{ $p->patient_id }})</option>
                    @endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Doctor <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select @error('doctor_id')is-invalid@enderror" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id')==$d->id?'selected':'' }}>{{ $d->full_name }} — {{ $d->specialization }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Bed <span class="text-danger">*</span></label>
                <select name="bed_id" class="form-select @error('bed_id')is-invalid@enderror" required>
                    <option value="">-- Select Available Bed --</option>
                    @foreach($availableBeds as $bed)
                    <option value="{{ $bed->id }}" {{ old('bed_id')==$bed->id?'selected':'' }}>{{ $bed->ward->name ?? '' }} — {{ $bed->bed_number }} (${{ $bed->charge_per_day }}/day)</option>
                    @endforeach
                </select>
                @if($availableBeds->isEmpty())<div class="text-danger small mt-1">No available beds. Please free up a bed first.</div>@endif</div>
            <div class="col-md-6"><label class="form-label">Admission Date <span class="text-danger">*</span></label>
                <input type="date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}" class="form-control" required></div>
            <div class="col-12"><label class="form-label">Initial Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" placeholder="Initial diagnosis or reason for admission...">{{ old('diagnosis') }}</textarea></div>
        </div>
    </div>
    <div class="card-body border-top">
        <button type="submit" class="btn-hms-primary me-2" {{ $availableBeds->isEmpty() ? 'disabled' : '' }}><i class="bi bi-check-circle me-1"></i>Admit Patient</button>
        <a href="{{ route('wards.admissions') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
