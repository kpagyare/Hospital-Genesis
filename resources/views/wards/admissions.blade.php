@extends('layouts.app')
@section('title','Admissions')
@section('page_title','Patient Admissions')
@section('breadcrumb','Home / Wards / Admissions')
@section('content')
<div class="page-header">
    <h4 class="page-title">Patient Admissions</h4>
    <a href="{{ route('wards.admit') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Admit Patient</a>
</div>
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex gap-2 align-items-end">
            <select name="status" class="form-select" style="max-width:200px;">
                <option value="">All Admissions</option>
                <option value="admitted"   {{ request('status')=='admitted'  ?'selected':'' }}>Currently Admitted</option>
                <option value="discharged" {{ request('status')=='discharged'?'selected':'' }}>Discharged</option>
            </select>
            <button type="submit" class="btn-hms-primary">Filter</button>
            <a href="{{ route('wards.admissions') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 16px;font-size:13px;">Clear</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Admission ID</th><th>Patient</th><th>Doctor</th><th>Ward/Bed</th><th>Admitted</th><th>Discharged</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($admissions as $adm)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $adm->admission_id }}</span></td>
                        <td><div class="table-name">{{ $adm->patient->full_name ?? 'N/A' }}</div></td>
                        <td class="small text-muted">{{ $adm->doctor->full_name ?? 'N/A' }}</td>
                        <td class="small">{{ $adm->bed->ward->name ?? 'N/A' }} / {{ $adm->bed->bed_number ?? 'N/A' }}</td>
                        <td class="small">{{ $adm->admission_date->format('d M Y') }}</td>
                        <td class="small">{{ $adm->discharge_date ? $adm->discharge_date->format('d M Y') : '—' }}</td>
                        <td><span class="badge {{ $adm->status=='admitted'?'bg-danger':'bg-success' }}">{{ ucfirst($adm->status) }}</span></td>
                        <td>
                            @if($adm->status === 'admitted')
                            <button type="button" class="btn btn-sm btn-outline-success" style="border-radius:6px;font-size:12px;" data-bs-toggle="modal" data-bs-target="#dischargeModal{{ $adm->id }}">
                                <i class="bi bi-box-arrow-right me-1"></i>Discharge
                            </button>
                            <!-- Discharge Modal -->
                            <div class="modal fade" id="dischargeModal{{ $adm->id }}" tabindex="-1">
                                <div class="modal-dialog"><div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header"><h6 class="modal-title">Discharge Patient</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form action="{{ route('wards.discharge', $adm) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted small">Patient: <strong>{{ $adm->patient->full_name }}</strong></p>
                                            <div class="mb-3"><label class="form-label">Discharge Date</label>
                                                <input type="date" name="discharge_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                                            <div class="mb-3"><label class="form-label">Discharge Notes</label>
                                                <textarea name="discharge_notes" class="form-control" rows="3" placeholder="Discharge summary..."></textarea></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-hms-primary">Confirm Discharge</button>
                                        </div>
                                    </form>
                                </div></div>
                            </div>
                            @else
                                <span class="text-muted small">{{ $adm->days_stayed }} days</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No admissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $admissions->links() }}</div>
    </div>
</div>
@endsection
