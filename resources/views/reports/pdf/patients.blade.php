<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Patient Report</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a202c; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 3px solid #0a2342; padding-bottom: 12px; margin-bottom: 20px; }
    .header h1 { font-size: 20px; color: #0a2342; margin: 0 0 4px; }
    .header p { margin: 2px 0; color: #555; font-size: 10px; }
    .report-title { font-size: 15px; font-weight: bold; color: #0a2342; margin-bottom: 16px; text-align: center; }
    .stats { display: flex; gap: 12px; margin-bottom: 20px; }
    .stat-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; text-align: center; }
    .stat-num { font-size: 20px; font-weight: bold; color: #0a2342; }
    .stat-label { font-size: 9px; color: #6b7280; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    thead tr { background: #0a2342; color: #fff; }
    th { padding: 8px 6px; text-align: left; font-size: 10px; }
    td { padding: 6px; border-bottom: 1px solid #f0f0f0; font-size: 10px; }
    tr:nth-child(even) { background: #f8fafc; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; font-size: 9px; color: #9ca3af; text-align: center; }
    .filters { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 16px; font-size: 10px; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $settings->hospital_name ?? 'Hospital Management System' }}</h1>
    <p>{{ $settings->address ?? '' }}</p>
    <p>{{ $settings->phone ?? '' }} | {{ $settings->email ?? '' }}</p>
</div>
<div class="report-title">PATIENT REPORT</div>
@if(request('from') || request('to') || request('blood_group'))
<div class="filters">
    <strong>Filters:</strong>
    @if(request('from')) From: {{ request('from') }} @endif
    @if(request('to')) To: {{ request('to') }} @endif
    @if(request('blood_group')) Blood Group: {{ request('blood_group') }} @endif
</div>
@endif
<table>
    <tr>
        <td style="width:25%;vertical-align:top;">
            <div class="stat-box"><div class="stat-num">{{ $stats['total'] }}</div><div class="stat-label">Total Patients</div></div>
        </td>
        <td style="width:25%;vertical-align:top;">
            <div class="stat-box"><div class="stat-num" style="color:#10b981;">{{ $stats['active'] }}</div><div class="stat-label">Active</div></div>
        </td>
        <td style="width:25%;vertical-align:top;">
            <div class="stat-box"><div class="stat-num" style="color:#3b82f6;">{{ $stats['male'] }}</div><div class="stat-label">Male</div></div>
        </td>
        <td style="width:25%;vertical-align:top;">
            <div class="stat-box"><div class="stat-num" style="color:#e63946;">{{ $stats['female'] }}</div><div class="stat-label">Female</div></div>
        </td>
    </tr>
</table>
<br>
<table>
    <thead>
        <tr><th>Patient ID</th><th>Name</th><th>Age / Gender</th><th>Blood Group</th><th>Phone</th><th>Registered</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($patients as $p)
        <tr>
            <td>{{ $p->patient_id }}</td>
            <td>{{ $p->full_name }}</td>
            <td>{{ $p->age ?? '—' }} yrs / {{ $p->gender ?? '—' }}</td>
            <td>{{ $p->blood_group ?? '—' }}</td>
            <td>{{ $p->phone ?? '—' }}</td>
            <td>{{ $p->created_at->format('d M Y') }}</td>
            <td><span class="badge {{ $p->status=='active'?'badge-success':'badge-secondary' }}">{{ ucfirst($p->status) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:20px;">No patients found.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">
    Generated on {{ date('d M Y, H:i') }} | {{ $settings->hospital_name ?? 'HMS' }}
</div>
</body>
</html>
