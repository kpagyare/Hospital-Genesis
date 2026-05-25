@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@section('content')

<!-- ══ STATS ROW ══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Patients</div>
                <div class="stat-value">{{ number_format($stats['total_patients']) }}</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up-short"></i> Active records</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card red">
            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Doctors</div>
                <div class="stat-value">{{ number_format($stats['total_doctors']) }}</div>
                <div class="stat-change"><i class="bi bi-circle-fill text-success" style="font-size:8px;"></i> On duty</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-info">
                <div class="stat-label">Today's Appts</div>
                <div class="stat-value">{{ number_format($stats['today_appointments']) }}</div>
                <div class="stat-change">{{ $stats['pending_appointments'] }} pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-info">
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-value">${{ number_format($stats['monthly_revenue'], 0) }}</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up-short"></i> This month</div>
            </div>
        </div>
    </div>
</div>

<!-- ══ SECOND STATS ROW ══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Staff</div>
                <div class="stat-value">{{ number_format($stats['total_staff']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-calendar3"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Appointments</div>
                <div class="stat-value">{{ number_format($stats['total_appointments']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card red">
            <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-info">
                <div class="stat-label">Monthly Expenses</div>
                <div class="stat-value">${{ number_format($stats['total_expenses'], 0) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- ══ CHARTS ROW ══ -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Revenue Overview (Last 6 Months)</h6>
                <a href="{{ route('reports.revenue') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;">View Report</a>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Pie Chart -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-pie-chart me-2 text-accent"></i>Appointment Status</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height:220px;">
                    <canvas id="appointmentChart"></canvas>
                </div>
                <div class="mt-3">
                    @foreach($appointmentStats as $status => $count)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-capitalize">{{ str_replace('_',' ',$status) }}</span>
                        <span class="badge" style="background:{{ match($status) { 'pending'=>'#f59e0b','confirmed'=>'#17a2b8','completed'=>'#10b981','cancelled'=>'#e63946',default=>'#6c757d' } }};">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ TABLES ROW ══ -->
<div class="row g-4">
    <!-- Recent Appointments -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-calendar-week me-2"></i>Recent Appointments</h6>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentAppointments->isEmpty())
                    <div class="empty-state"><i class="bi bi-calendar-x"></i><h6>No Appointments</h6></div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $apt)
                            <tr>
                                <td>
                                    <div class="table-name">{{ $apt->patient->full_name ?? 'N/A' }}</div>
                                    <div class="table-sub">{{ $apt->appointment_id }}</div>
                                </td>
                                <td>
                                    <div class="table-name">{{ $apt->doctor->full_name ?? 'N/A' }}</div>
                                    <div class="table-sub">{{ $apt->doctor->specialization ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="fw-600 small">{{ $apt->appointment_date->format('d M Y') }}</div>
                                    <div class="table-sub">{{ $apt->appointment_time }}</div>
                                </td>
                                <td>{!! $apt->status_badge !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-person-plus me-2"></i>Recent Patients</h6>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentPatients->isEmpty())
                    <div class="empty-state"><i class="bi bi-person-x"></i><h6>No Patients Yet</h6></div>
                @else
                <ul class="list-group list-group-flush">
                    @foreach($recentPatients as $patient)
                    <li class="list-group-item px-4 py-3 d-flex align-items-center gap-3">
                        <img src="{{ $patient->photo_url }}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                        <div class="flex-grow-1">
                            <div class="table-name">{{ $patient->full_name }}</div>
                            <div class="table-sub">{{ $patient->patient_id }} &bull; {{ ucfirst($patient->gender) }}</div>
                        </div>
                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;">View</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($monthlyRevenue) !!},
            backgroundColor: 'rgba(10, 35, 66, 0.8)',
            borderColor: '#0a2342',
            borderWidth: 0,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f4f9' },
                ticks: { callback: v => '$' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});

// Appointment Pie Chart
const aptCtx = document.getElementById('appointmentChart').getContext('2d');
const aptData = {!! json_encode($appointmentStats) !!};
new Chart(aptCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(aptData).map(s => s.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
        datasets: [{
            data: Object.values(aptData),
            backgroundColor: ['#f59e0b','#17a2b8','#10b981','#e63946','#6c757d'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '65%',
    }
});
</script>
@endpush
