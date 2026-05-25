@extends('layouts.app')
@section('title','My Profile')
@section('page_title','My Profile')
@section('breadcrumb','Home / Settings / Profile')
@section('content')
<div class="page-header">
    <h4 class="page-title">My Profile</h4>
    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <div id="avatarPreview" style="width:100px;height:100px;border-radius:50%;margin:0 auto 16px;overflow:hidden;border:3px solid var(--primary);">
                    @if(auth()->user()->photo)
                    <img src="{{ auth()->user()->photo_url }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="width:100%;height:100%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                    @endif
                </div>
                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                <div class="text-muted small">{{ auth()->user()->role_label }}</div>
                <div class="mt-2"><span class="badge bg-success">Active</span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2"></i>Personal Information</h6></div>
            <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name')is-invalid@enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email')is-invalid@enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput"></div>
                </div>
            </div>
            <div class="card-body border-top">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Update Profile</button>
            </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-lock me-2"></i>Change Password</h6></div>
            <form action="{{ route('settings.password.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password')is-invalid@enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6"><label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password')is-invalid@enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required></div>
                </div>
            </div>
            <div class="card-body border-top">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-key me-1"></i>Change Password</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('avatarPreview').innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
