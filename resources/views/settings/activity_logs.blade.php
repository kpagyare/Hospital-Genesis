@extends('layouts.app')
@section('title','Activity Logs')
@section('page_title','Activity Logs')
@section('breadcrumb','Home / Settings / Activity Logs')
@section('content')
<div class="page-header">
    <h4 class="page-title">Activity Logs</h4>
    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search logs..."></div>
            <div class="col-md-3"><select name="module" class="form-select form-select-sm">
                <option value="">All Modules</option>
                @foreach(['Auth','Patients','Doctors','Appointments','Billing','Pharmacy','Laboratory','Wards','Staff','Settings'] as $mod)
                <option value="{{ $mod }}" {{ request('module')==$mod?'selected':'' }}>{{ $mod }}</option>
                @endforeach
            </select></div>
            <div class="col-md-3"><input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary btn-sm">Filter</button>
                <a href="{{ route('settings.activity_logs') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Module</th><th>Description</th><th>IP</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted" style="white-space:nowrap;">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="small">
                            @if($log->user)
                            <div class="fw-600">{{ $log->user->name }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $log->user->role_label }}</div>
                            @else
                            <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $actionColor = match(strtolower($log->action)) {
                                    'create','store','login' => 'bg-success',
                                    'update','edit' => 'bg-warning text-dark',
                                    'delete','destroy','logout' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $actionColor }}">{{ ucfirst($log->action) }}</span>
                        </td>
                        <td><span class="badge bg-light text-dark border small">{{ $log->module }}</span></td>
                        <td class="small text-muted">{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No activity logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $logs->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
