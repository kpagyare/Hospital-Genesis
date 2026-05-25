@extends('layouts.app')
@section('title','Request Lab Test')
@section('page_title','Request Lab Test')
@section('breadcrumb','Home / Laboratory / Request')
@section('content')
<div class="page-header">
    <h4 class="page-title">Request Lab Test</h4>
    <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<form action="{{ route('laboratory.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header"><h6 class="card-title"><i class="bi bi-eyedropper me-2"></i>Test Request</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Patient <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select @error('patient_id')is-invalid@enderror" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id')==$p->id?'selected':'' }}>{{ $p->full_name }} ({{ $p->patient_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Referring Doctor <span class="text-danger">*</span></label>
                <select name="doctor_id" class="form-select @error('doctor_id')is-invalid@enderror" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id')==$d->id?'selected':'' }}>{{ $d->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Lab Test <span class="text-danger">*</span></label>
                <select name="lab_test_id" class="form-select @error('lab_test_id')is-invalid@enderror" required>
                    <option value="">-- Select Test --</option>
                    @foreach($labTests as $test)
                    <option value="{{ $test->id }}" {{ old('lab_test_id')==$test->id?'selected':'' }}>{{ $test->name }} (${{ $test->price }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Test Date <span class="text-danger">*</span></label>
                <input type="date" name="test_date" value="{{ old('test_date', date('Y-m-d')) }}" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="card-body border-top">
        <button type="submit" class="btn-hms-primary me-2"><i class="bi bi-check-circle me-1"></i>Request Test</button>
        <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
