@extends('layouts.app')
@section('title','Edit Doctor')
@section('page_title','Edit Doctor')
@section('breadcrumb','Home / Doctors / Edit')

@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Doctor <small>{{ $doctor->doctor_id }}</small></h4>
    <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<form action="{{ route('doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name',$doctor->first_name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name',$doctor->last_name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone',$doctor->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" value="{{ old('specialization',$doctor->specialization) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification',$doctor->qualification) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Experience (Yrs)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years',$doctor->experience_years) }}" class="form-control" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Consultation Fee</label>
                        <input type="number" name="consultation_fee" value="{{ old('consultation_fee',$doctor->consultation_fee) }}" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="male"   {{ old('gender',$doctor->gender)=='male'  ?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender',$doctor->gender)=='female'?'selected':'' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active"   {{ old('status',$doctor->status)=='active'  ?'selected':'' }}>Active</option>
                            <option value="on_leave" {{ old('status',$doctor->status)=='on_leave'?'selected':'' }}>On Leave</option>
                            <option value="inactive" {{ old('status',$doctor->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Available Days</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, old('available_days',$doctor->available_days??[])) ? 'checked':'' }}>
                                <label class="form-check-label small" for="day_{{ $day }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Available From</label>
                        <input type="time" name="available_from" value="{{ old('available_from',$doctor->available_from) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Available To</label>
                        <input type="time" name="available_to" value="{{ old('available_to',$doctor->available_to) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio',$doctor->bio) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $doctor->photo_url }}" id="photoPreview" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);margin-bottom:12px;">
                <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none" onchange="document.getElementById('photoPreview').src=URL.createObjectURL(this.files[0])">
                <label for="photoInput" class="btn-hms-primary d-block" style="cursor:pointer;"><i class="bi bi-camera me-1"></i>Change Photo</label>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn-hms-primary w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Update Doctor</button>
                <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
