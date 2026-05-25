@extends('layouts.app')
@section('title','Edit Ward')
@section('page_title','Edit Ward')
@section('breadcrumb','Home / Wards / Edit')
@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Ward</h4>
    <a href="{{ route('wards.show', $ward) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
<form action="{{ route('wards.update', $ward) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name',$ward->name) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Ward Type</label>
                <select name="ward_type" class="form-select">
                    @foreach(['General','ICU','Maternity','Pediatric','Surgical','Emergency','Isolation'] as $t)
                    <option value="{{ $t }}" {{ old('ward_type',$ward->ward_type)==$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Bed Charge/Day ($)</label>
                <input type="number" name="bed_charge_per_day" value="{{ old('bed_charge_per_day',$ward->bed_charge_per_day) }}" class="form-control" min="0" step="0.01"></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   {{ $ward->status=='active'  ?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $ward->status=='inactive'?'selected':'' }}>Inactive</option>
                </select></div>
            <div class="col-12"><label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description',$ward->description) }}</textarea></div>
        </div>
    </div>
    <div class="card-body border-top">
        <button type="submit" class="btn-hms-primary me-2"><i class="bi bi-check-circle me-1"></i>Update Ward</button>
        <a href="{{ route('wards.show', $ward) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
