@extends('layouts.app')
@section('title','Add Patient')
@section('page_title','Add New Patient')
@section('breadcrumb','Home / Patients / Add Patient')

@section('content')
<div class="page-header">
    <h4 class="page-title">Add New Patient</h4>
    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back to Patients
    </a>
</div>

<form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">

    <!-- Personal Information -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-person me-2"></i>Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="Enter first name" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="Enter last name" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="patient@email.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 234 567 8900" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male"   {{ old('gender')=='male'   ? 'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>Female</option>
                            <option value="other"  {{ old('gender')=='other'  ? 'selected':'' }}>Other</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Unknown</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group')==$bg ? 'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Enter full address">{{ old('address') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="form-control" placeholder="City">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" class="form-control" placeholder="State">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country','Ghana') }}" class="form-control" placeholder="Country">
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-heart-pulse me-2"></i>Medical Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="3" placeholder="Previous diseases, surgeries, chronic conditions...">{{ old('medical_history') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Known Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2" placeholder="Drug allergies, food allergies, etc.">{{ old('allergies') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-telephone-plus me-2 text-danger"></i>Emergency Contact</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="form-control" placeholder="Full name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="form-control" placeholder="Phone number">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Relation</label>
                        <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" class="form-control" placeholder="e.g. Spouse, Parent">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Upload -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-image me-2"></i>Patient Photo</h6>
            </div>
            <div class="card-body text-center">
                <div id="photoPreview" style="width:130px;height:130px;border-radius:50%;border:3px dashed #e2e8f0;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;overflow:hidden;background:#f8fafc;">
                    <i class="bi bi-person" style="font-size:48px;color:#cbd5e0;"></i>
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none" onchange="previewPhoto(this)">
                <label for="photoInput" class="btn-hms-primary" style="cursor:pointer;">
                    <i class="bi bi-camera me-1"></i>Upload Photo
                </label>
                <p class="text-muted small mt-2">JPG, PNG. Max 2MB</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn-hms-primary w-100 mb-2">
                    <i class="bi bi-check-circle me-1"></i> Save Patient
                </button>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">
                    Cancel
                </a>
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
        reader.onload = e => {
            const prev = document.getElementById('photoPreview');
            prev.innerHTML = `<img src="${e.target.result}" style="width:130px;height:130px;object-fit:cover;border-radius:50%;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
