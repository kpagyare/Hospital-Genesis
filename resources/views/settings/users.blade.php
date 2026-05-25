@extends('layouts.app')
@section('title','User Management')
@section('page_title','User Management')
@section('breadcrumb','Home / Settings / Users')
@section('content')
<div class="page-header">
    <h4 class="page-title">User Management</h4>
    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><div class="search-bar"><i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search users..."></div></div>
            <div class="col-md-3"><select name="role" class="form-select">
                <option value="">All Roles</option>
                @foreach(['super_admin','doctor','nurse','receptionist','pharmacist','lab_staff','accountant','patient'] as $r)
                <option value="{{ $r }}" {{ request('role')==$r?'selected':'' }}>{{ ucwords(str_replace('_',' ',$r)) }}</option>
                @endforeach
            </select></div>
            <div class="col-md-2"><select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
            </select></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('settings.users') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>User</th><th>Role</th><th>Phone</th><th>Joined</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td><div class="d-flex align-items-center gap-3">
                            @if($u->photo)
                            <img src="{{ $u->photo_url }}" class="table-avatar">
                            @else
                            <div class="table-avatar d-flex align-items-center justify-content-center" style="background:#e2e8f0;"><i class="bi bi-person text-muted"></i></div>
                            @endif
                            <div>
                                <div class="table-name">{{ $u->name }}</div>
                                <div class="table-sub">{{ $u->email }}</div>
                            </div>
                        </div></td>
                        <td><span class="badge bg-light text-dark border">{{ $u->role_label }}</span></td>
                        <td class="small text-muted">{{ $u->phone ?? '—' }}</td>
                        <td class="small">{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($u->id !== auth()->id())
                            <form action="{{ route('settings.users.toggle', $u) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" style="border-radius:6px;font-size:12px;" onclick="return confirm('{{ $u->is_active ? 'Deactivate' : 'Activate' }} this user?')">
                                    <i class="bi bi-{{ $u->is_active ? 'person-x' : 'person-check' }} me-1"></i>{{ $u->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">You</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
