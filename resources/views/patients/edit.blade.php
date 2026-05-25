@extends('layouts.app')
@section('title','Edit Patient')
@section('page_title','Edit Patient')
@section('breadcrumb','Home / Patients / Edit')

@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Patient <small>{{ $patient->patient_id }}</small></h4>
    <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form action="{{ route('patients.update', $patient) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Personal Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name',$patient->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name',$patient->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email',$patient->email) }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone',$patient->phone) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" {{ old('gender',$patient->gender)==$g ? 'selected':'' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Unknown</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group',$patient->blood_group)==$bg ? 'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address',$patient->address) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city',$patient->city) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" name="state" value="{{ old('state',$patient->state) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country',$patient->country) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="3">{{ old('medical_history',$patient->medical_history) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Known Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2">{{ old('allergies',$patient->allergies) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name',$patient->emergency_contact_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone',$patient->emergency_contact_phone) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Emergency Relation</label>
                        <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation',$patient->emergency_contact_relation) }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Photo & Status</h6></div>
            <div class="card-body text-center">
                <img src="{{ $patient->photo_url }}" id="photoPreview" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);margin-bottom:12px;">
                <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none" onchange="previewPhoto(this)">
                <label for="photoInput" class="btn-hms-primary d-block mb-3" style="cursor:pointer;"><i class="bi bi-camera me-1"></i>Change Photo</label>
                <div class="text-start">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"   {{ $patient->status=='active'   ? 'selected':'' }}>Active</option>
                        <option value="inactive" {{ $patient->status=='inactive' ? 'selected':'' }}>Inactive</option>
                        <option value="deceased" {{ $patient->status=='deceased' ? 'selected':'' }}>Deceased</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn-hms-primary w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Update Patient</button>
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('photoPreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
