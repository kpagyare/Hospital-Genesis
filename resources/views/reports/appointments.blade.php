@extends('layouts.app')
@section('title','Appointment Report')
@section('page_title','Appointment Report')
@section('breadcrumb','Home / Reports / Appointments')
@section('content')
<div class="page-header">
    <h4 class="page-title">Appointment Report</h4>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label small">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="scheduled"  {{ request('status')=='scheduled' ?'selected':'' }}>Scheduled</option>
                    <option value="completed"  {{ request('status')=='completed' ?'selected':'' }}>Completed</option>
                    <option value="cancelled"  {{ request('status')=='cancelled' ?'selected':'' }}>Cancelled</option>
                    <option value="no_show"    {{ request('status')=='no_show'   ?'selected':'' }}>No Show</option>
                </select></div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary btn-sm">Filter</button>
                <a href="{{ route('reports.appointments') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3" style="color:var(--primary);">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-success">{{ $stats['completed'] }}</div>
                <div class="text-muted small">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-primary">{{ $stats['scheduled'] }}</div>
                <div class="text-muted small">Scheduled</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-700 fs-3 text-danger">{{ $stats['cancelled'] }}</div>
                <div class="text-muted small">Cancelled</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Status Breakdown</h6></div>
            <div class="card-body"><canvas id="statusChart" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Top Doctors by Appointments</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Doctor</th><th>Specialization</th><th class="text-end">Count</th></tr></thead>
                        <tbody>
                            @foreach($topDoctors as $d)
                            <tr>
                                <td class="small fw-600">{{ $d->full_name }}</td>
                                <td class="small text-muted">{{ $d->specialization }}</td>
                                <td class="text-end"><span class="badge bg-light text-dark border">{{ $d->appointments_count }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="card-title">Appointment List</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Date & Time</th><th>Type</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($appointments as $a)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $a->appointment_id }}</span></td>
                        <td class="small">{{ $a->patient->full_name ?? 'N/A' }}</td>
                        <td class="small text-muted">{{ $a->doctor->full_name ?? 'N/A' }}</td>
                        <td class="small">{{ $a->appointment_date->format('d M Y') }} {{ $a->appointment_time }}</td>
                        <td class="small">{{ ucfirst($a->type) }}</td>
                        <td>{!! $a->status_badge !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No appointments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $appointments->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Scheduled','Completed','Cancelled','No Show'],
        datasets: [{ data: [{{ $stats['scheduled'] }},{{ $stats['completed'] }},{{ $stats['cancelled'] }},{{ $stats['no_show'] }}],
            backgroundColor: ['#3b82f6','#10b981','#e63946','#f59e0b'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
});
</script>
@endpush
