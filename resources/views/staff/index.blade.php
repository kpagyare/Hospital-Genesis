@extends('layouts.app')
@section('title','Staff')
@section('page_title','Staff Management')
@section('breadcrumb','Home / Staff')
@section('content')
<div class="page-header">
    <h4 class="page-title">Staff Members</h4>
    <a href="{{ route('staff.create') }}" class="btn-hms-primary"><i class="bi bi-person-plus me-1"></i>Add Staff</a>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><div class="search-bar"><i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search staff..."></div></div>
            <div class="col-md-3"><select name="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department')==$dept?'selected':'' }}>{{ $dept }}</option>
                @endforeach
            </select></div>
            <div class="col-md-2"><select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active"     {{ request('status')=='active'    ?'selected':'' }}>Active</option>
                <option value="on_leave"   {{ request('status')=='on_leave'  ?'selected':'' }}>On Leave</option>
                <option value="terminated" {{ request('status')=='terminated'?'selected':'' }}>Terminated</option>
            </select></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Staff</th><th>ID</th><th>Department</th><th>Position</th><th>Role</th><th>Salary</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
                <tbody>
                    @forelse($staff as $s)
                    <tr>
                        <td><div class="d-flex align-items-center gap-3">
                            @if($s->photo)<img src="{{ asset('storage/'.$s->photo) }}" class="table-avatar">@else<div class="table-avatar d-flex align-items-center justify-content-center" style="background:#e2e8f0;"><i class="bi bi-person text-muted"></i></div>@endif
                            <div><div class="table-name">{{ $s->full_name }}</div><div class="table-sub">{{ $s->email }}</div></div>
                        </div></td>
                        <td><span class="badge bg-light text-dark border">{{ $s->staff_id }}</span></td>
                        <td class="small text-muted">{{ $s->department ?? '—' }}</td>
                        <td class="small">{{ $s->position }}</td>
                        <td><span class="badge bg-light text-dark border small">{{ $s->user?->role_label ?? '—' }}</span></td>
                        <td class="small">${{ number_format($s->salary,0) }}</td>
                        <td><span class="badge {{ $s->status=='active' ? 'bg-success' : ($s->status=='on_leave' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ ucfirst(str_replace('_',' ',$s->status)) }}</span></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('staff.show', $s) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('staff.edit', $s) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('staff.destroy', $s) }}" method="POST" onsubmit="return confirm('Remove staff?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $staff->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
