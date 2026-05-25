@extends('layouts.app')
@section('title', $staff->full_name)
@section('page_title','Staff Profile')
@section('breadcrumb','Home / Staff / '.$staff->full_name)
@section('content')
<div class="page-header">
    <h4 class="page-title">Staff Profile</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('staff.edit', $staff) }}" class="btn-hms-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                @if($staff->photo)
                <img src="{{ asset('storage/'.$staff->photo) }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                @else
                <div style="width:100px;height:100px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;margin:0 auto;border:3px solid var(--primary);">
                    <i class="bi bi-person fs-1 text-muted"></i>
                </div>
                @endif
                <h5 class="mt-3 mb-1">{{ $staff->full_name }}</h5>
                <div class="text-muted small">{{ $staff->position }}</div>
                <div class="mt-2">
                    <span class="badge bg-light text-dark border me-1">{{ $staff->staff_id }}</span>
                    <span class="badge {{ $staff->status=='active' ? 'bg-success' : ($staff->status=='on_leave' ? 'bg-warning text-dark' : 'bg-danger') }}">
                        {{ ucfirst(str_replace('_',' ',$staff->status)) }}
                    </span>
                </div>
                @if($staff->user)
                <div class="mt-2"><span class="badge bg-light text-dark border small">{{ $staff->user->role_label }}</span></div>
                @endif
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header"><h6 class="card-title">Contact</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-envelope text-muted"></i><span class="small">{{ $staff->email }}</span></div>
                @if($staff->phone)
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-telephone text-muted"></i><span class="small">{{ $staff->phone }}</span></div>
                @endif
                @if($staff->address)
                <div class="d-flex align-items-start gap-2"><i class="bi bi-geo-alt text-muted mt-1"></i><span class="small">{{ $staff->address }}</span></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-briefcase me-2"></i>Employment Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Department</div>
                        <div class="fw-600">{{ $staff->department ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Position</div>
                        <div class="fw-600">{{ $staff->position }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Salary</div>
                        <div class="fw-600">${{ number_format($staff->salary, 0) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Join Date</div>
                        <div class="fw-600">{{ $staff->join_date ? $staff->join_date->format('d M Y') : '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Gender</div>
                        <div class="fw-600">{{ $staff->gender ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Date of Birth</div>
                        <div class="fw-600">{{ $staff->date_of_birth ? $staff->date_of_birth->format('d M Y') : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @if($staff->notes)
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-sticky me-2"></i>Notes</h6></div>
            <div class="card-body"><p class="mb-0 small text-muted">{{ $staff->notes }}</p></div>
        </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0"><i class="bi bi-shield-check me-2"></i>System Account</h6>
            </div>
            <div class="card-body">
                @if($staff->user)
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Login Email</div>
                        <div class="fw-600">{{ $staff->user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Role</div>
                        <div><span class="badge bg-light text-dark border">{{ $staff->user->role_label }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Account Status</div>
                        <div><span class="badge {{ $staff->user->is_active ? 'bg-success' : 'bg-danger' }}">{{ $staff->user->is_active ? 'Active' : 'Inactive' }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Member Since</div>
                        <div class="fw-600">{{ $staff->user->created_at->format('d M Y') }}</div>
                    </div>
                </div>
                @else
                <div class="text-muted small">No system account linked to this staff member.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
