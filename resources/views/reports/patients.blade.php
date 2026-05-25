@extends('layouts.app')
@section('title','Patient Report')
@section('page_title','Patient Report')
@section('breadcrumb','Home / Reports / Patients')
@section('content')
<div class="page-header">
    <h4 class="page-title">Patient Report</h4>
    <a href="{{ route('reports.export', 'patients') }}?{{ http_build_query(request()->all()) }}" class="btn-hms-primary" target="_blank">
        <i class="bi bi-file-pdf me-1"></i>Export PDF
    </a>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label small">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">Blood Group</label>
                <select name="blood_group" class="form-select form-select-sm">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary btn-sm">Filter</button>
                <a href="{{ route('reports.patients') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3" style="color:var(--primary);">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total Patients</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-success">{{ $stats['active'] }}</div>
                <div class="text-muted small">Active</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-primary">{{ $stats['male'] }}</div>
                <div class="text-muted small">Male</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-danger">{{ $stats['female'] }}</div>
                <div class="text-muted small">Female</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Blood Group Distribution</h6></div>
            <div class="card-body"><canvas id="bloodGroupChart" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Monthly Registrations</h6></div>
            <div class="card-body"><canvas id="monthlyChart" height="220"></canvas></div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="card-title">Patient List</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Patient ID</th><th>Name</th><th>Age/Gender</th><th>Blood Group</th><th>Phone</th><th>Registered</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($patients as $p)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $p->patient_id }}</span></td>
                        <td class="table-name">{{ $p->full_name }}</td>
                        <td class="small text-muted">{{ $p->age }} yrs / {{ $p->gender ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $p->blood_group ?? '—' }}</span></td>
                        <td class="small">{{ $p->phone ?? '—' }}</td>
                        <td class="small">{{ $p->created_at->format('d M Y') }}</td>
                        <td><span class="badge {{ $p->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No patients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $patients->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const bloodLabels = @json(array_keys($bloodGroups));
const bloodData   = @json(array_values($bloodGroups));
new Chart(document.getElementById('bloodGroupChart'), {
    type: 'doughnut',
    data: { labels: bloodLabels, datasets: [{ data: bloodData, backgroundColor: ['#0a2342','#e63946','#10b981','#f59e0b','#3b82f6','#8b5cf6','#ec4899','#6b7280'] }] },
    options: { plugins: { legend: { position: 'right' } }, cutout: '65%' }
});
const monthLabels = @json(array_keys($monthly));
const monthData   = @json(array_values($monthly));
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: { labels: monthLabels, datasets: [{ label: 'New Patients', data: monthData, backgroundColor: 'rgba(10,35,66,0.7)', borderRadius: 6 }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
