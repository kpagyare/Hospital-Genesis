@extends('layouts.app')
@section('title','Prescriptions')
@section('page_title','Pending Prescriptions')
@section('breadcrumb','Home / Pharmacy / Prescriptions')
@section('content')
<div class="page-header">
    <h4 class="page-title">Pending Prescriptions <small>Awaiting dispensing</small></h4>
</div>
<div class="card">
    <div class="card-body p-0">
        @if($prescriptions->isEmpty())
            <div class="empty-state"><i class="bi bi-prescription2"></i><h6>No pending prescriptions</h6></div>
        @else
        @foreach($prescriptions as $pre)
        <div class="p-4 border-bottom">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <span class="badge bg-light text-dark border me-2">{{ $pre->prescription_id }}</span>
                    <strong>{{ $pre->patient->full_name ?? 'N/A' }}</strong>
                    <span class="text-muted small ms-2">by {{ $pre->doctor->full_name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted">{{ $pre->prescription_date->format('d M Y') }}</span>
                    <form action="{{ route('pharmacy.dispense', $pre) }}" method="POST" onsubmit="return confirm('Mark as dispensed?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" style="border-radius:7px;font-size:12px;"><i class="bi bi-check-circle me-1"></i>Mark Dispensed</button>
                    </form>
                </div>
            </div>
            @if($pre->diagnosis)<p class="text-muted small mb-2">Diagnosis: {{ $pre->diagnosis }}</p>@endif
            <table class="table table-sm table-bordered mb-0" style="background:#f8fafc;">
                <thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Qty</th></tr></thead>
                <tbody>
                    @foreach($pre->items as $item)
                    <tr>
                        <td class="small fw-600">{{ $item->medicine->name ?? 'N/A' }}</td>
                        <td class="small">{{ $item->dosage }}</td>
                        <td class="small">{{ $item->frequency }}</td>
                        <td class="small">{{ $item->duration_days }} days</td>
                        <td class="small">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
        <div class="px-4 py-3">{{ $prescriptions->links() }}</div>
        @endif
    </div>
</div>
@endsection
