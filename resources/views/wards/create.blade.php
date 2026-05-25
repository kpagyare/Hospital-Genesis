@extends('layouts.app')
@section('title','Add Ward')
@section('page_title','Add New Ward')
@section('breadcrumb','Home / Wards / Add')
@section('content')
<div class="page-header">
    <h4 class="page-title">Add New Ward</h4>
    <a href="{{ route('wards.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<form action="{{ route('wards.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header"><h6 class="card-title">Ward Details</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Ward Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name')is-invalid@enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label">Ward Type</label>
                <select name="ward_type" class="form-select">
                    <option value="">Select Type</option>
                    @foreach(['General','ICU','Maternity','Pediatric','Surgical','Emergency','Isolation'] as $t)
                    <option value="{{ $t }}" {{ old('ward_type')==$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Number of Beds <span class="text-danger">*</span></label>
                <input type="number" name="total_beds" value="{{ old('total_beds',1) }}" class="form-control @error('total_beds')is-invalid@enderror" min="1" required>
                <div class="form-text">Beds will be auto-created</div></div>
            <div class="col-md-6"><label class="form-label">Bed Charge Per Day ($)</label>
                <input type="number" name="bed_charge_per_day" value="{{ old('bed_charge_per_day',0) }}" class="form-control" min="0" step="0.01"></div>
            <div class="col-12"><label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Ward description...">{{ old('description') }}</textarea></div>
        </div>
    </div>
    <div class="card-body border-top">
        <button type="submit" class="btn-hms-primary me-2"><i class="bi bi-check-circle me-1"></i>Create Ward</button>
        <a href="{{ route('wards.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
