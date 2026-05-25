@extends('layouts.app')
@section('title','Settings')
@section('page_title','Settings')
@section('breadcrumb','Home / Settings')
@section('content')
<div class="page-header">
    <h4 class="page-title">Hospital Settings</h4>
</div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-hospital me-2"></i>Hospital Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Hospital Name</label>
                        <input type="text" name="hospital_name" value="{{ old('hospital_name', $settings->hospital_name ?? '') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $settings->email ?? '') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $settings->phone ?? '') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Website</label>
                        <input type="text" name="website" value="{{ old('website', $settings->website ?? '') }}" class="form-control" placeholder="https://"></div>
                    <div class="col-12"><label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $settings->address ?? '') }}</textarea></div>
                    <div class="col-12"><label class="form-label">Footer Text</label>
                        <input type="text" name="footer_text" value="{{ old('footer_text', $settings->footer_text ?? '') }}" class="form-control" placeholder="Footer text for reports and invoices"></div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-gear me-2"></i>System Preferences</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Currency Symbol</label>
                        <input type="text" name="currency" value="{{ old('currency', $settings->currency ?? '$') }}" class="form-control" placeholder="$"></div>
                    <div class="col-md-6"><label class="form-label">Timezone</label>
                        <select name="timezone" class="form-select">
                            @foreach(['UTC','America/New_York','America/Chicago','America/Denver','America/Los_Angeles','Europe/London','Europe/Paris','Asia/Dubai','Asia/Kolkata','Asia/Singapore','Australia/Sydney','Africa/Lagos','Africa/Accra'] as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $settings->timezone ?? 'UTC')==$tz?'selected':'' }}>{{ $tz }}</option>
                            @endforeach
                        </select></div>
                    <div class="col-md-6"><label class="form-label">Date Format</label>
                        <select name="date_format" class="form-select">
                            <option value="d M Y" {{ old('date_format', $settings->date_format ?? 'd M Y')=='d M Y'?'selected':'' }}>15 Jan 2025</option>
                            <option value="d/m/Y" {{ old('date_format', $settings->date_format ?? 'd M Y')=='d/m/Y'?'selected':'' }}>15/01/2025</option>
                            <option value="m/d/Y" {{ old('date_format', $settings->date_format ?? 'd M Y')=='m/d/Y'?'selected':'' }}>01/15/2025</option>
                            <option value="Y-m-d" {{ old('date_format', $settings->date_format ?? 'd M Y')=='Y-m-d'?'selected':'' }}>2025-01-15</option>
                        </select></div>
                    <div class="col-md-6"><label class="form-label">Items Per Page</label>
                        <select name="per_page" class="form-select">
                            @foreach([10, 15, 25, 50, 100] as $pp)
                            <option value="{{ $pp }}" {{ old('per_page', $settings->per_page ?? 15)==$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body border-top">
                <button type="submit" class="btn-hms-primary me-2"><i class="bi bi-check-circle me-1"></i>Save Settings</button>
            </div>
        </div>
        </form>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-image me-2"></i>Hospital Logo</h6></div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if(!empty($settings->logo))
                    <img src="{{ asset('storage/'.$settings->logo) }}" style="max-width:150px;max-height:80px;object-fit:contain;">
                    @else
                    <div style="width:150px;height:80px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                        <i class="bi bi-hospital fs-3 text-muted"></i>
                    </div>
                    @endif
                </div>
                <form action="{{ route('settings.logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="logo" class="form-control form-control-sm mb-2" accept="image/*" required>
                <button type="submit" class="btn btn-outline-secondary btn-sm w-100" style="border-radius:8px;"><i class="bi bi-upload me-1"></i>Upload Logo</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-link-45deg me-2"></i>Quick Links</h6></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="border-radius:0 0 12px 12px;">
                    <a href="{{ route('settings.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-muted"></i><span class="small">My Profile</span><i class="bi bi-chevron-right ms-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('settings.users') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                        <i class="bi bi-people text-muted"></i><span class="small">User Management</span><i class="bi bi-chevron-right ms-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('settings.activity_logs') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-muted"></i><span class="small">Activity Logs</span><i class="bi bi-chevron-right ms-auto text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
