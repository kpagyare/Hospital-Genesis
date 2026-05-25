@extends('layouts.app')
@section('title','Laboratory')
@section('page_title','Laboratory Management')
@section('breadcrumb','Home / Laboratory')
@section('content')
<div class="page-header">
    <h4 class="page-title">Lab Tests & Results <small>{{ $pendingCount }} pending today</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('laboratory.tests') }}" class="btn btn-outline-primary" style="border-radius:8px;font-size:13px;"><i class="bi bi-list me-1"></i>Test List</a>
        <a href="{{ route('laboratory.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Request Test</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-eyedropper"></i></div>
            <div class="stat-info"><div class="stat-label">Today's Tests</div><div class="stat-value">{{ $todayCount }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card orange"><div class="stat-icon"><i class="bi bi-hourglass"></i></div>
            <div class="stat-info"><div class="stat-label">Pending</div><div class="stat-value">{{ $pendingCount }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green"><div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info"><div class="stat-label">Total Results</div><div class="stat-value">{{ $results->total() }}</div></div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><div class="search-bar"><i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search patient, test..."></div></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','in_progress','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Result ID</th><th>Patient</th><th>Test</th><th>Doctor</th><th>Date</th><th>Result</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($results as $res)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $res->result_id }}</span></td>
                        <td><div class="table-name">{{ $res->patient->full_name ?? 'N/A' }}</div></td>
                        <td class="small">{{ $res->labTest->name ?? 'N/A' }}</td>
                        <td class="small text-muted">{{ $res->doctor->full_name ?? 'N/A' }}</td>
                        <td class="small">{{ $res->test_date->format('d M Y') }}</td>
                        <td class="small">{{ $res->result_value ?? '—' }}</td>
                        <td><span class="badge {{ match($res->status) { 'completed'=>'bg-success','in_progress'=>'bg-info','cancelled'=>'bg-danger',default=>'bg-warning text-dark' } }}">{{ ucfirst(str_replace('_',' ',$res->status)) }}</span></td>
                        <td><a href="{{ route('laboratory.show', $res) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No lab results found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $results->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
