@extends('layouts.app')
@section('title','Add Doctor')
@section('page_title','Add New Doctor')
@section('breadcrumb','Home / Doctors / Add')

@section('content')
<div class="page-header">
    <h4 class="page-title">Add New Doctor</h4>
    <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Doctor Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name')is-invalid@enderror" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name')is-invalid@enderror" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email')is-invalid@enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" value="{{ old('specialization') }}" class="form-control @error('specialization')is-invalid@enderror" placeholder="e.g. Cardiology" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}" class="form-control" placeholder="e.g. MBBS, MD">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years',0) }}" class="form-control" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Consultation Fee ($)</label>
                        <input type="number" name="consultation_fee" value="{{ old('consultation_fee',0) }}" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="male"   {{ old('gender')=='male'   ? 'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Available Days</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, old('available_days',[])) ? 'checked':'' }}>
                                <label class="form-check-label small" for="day_{{ $day }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Available From</label>
                        <input type="time" name="available_from" value="{{ old('available_from','08:00') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Available To</label>
                        <input type="time" name="available_to" value="{{ old('available_to','17:00') }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio / About</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief description about the doctor...">{{ old('bio') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div id="photoPreview" style="width:120px;height:120px;border-radius:50%;border:3px dashed #e2e8f0;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;overflow:hidden;">
                    <i class="bi bi-person" style="font-size:48px;color:#cbd5e0;"></i>
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none" onchange="previewPhoto(this)">
                <label for="photoInput" class="btn-hms-primary" style="cursor:pointer;"><i class="bi bi-camera me-1"></i>Upload Photo</label>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="card-title">Login Credentials</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password')is-invalid@enderror" placeholder="Min. 6 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <p class="text-muted small">The doctor will use their email and this password to login.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn-hms-primary w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Save Doctor</button>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Cancel</a>
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
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width:120px;height:120px;object-fit:cover;border-radius:50%;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
