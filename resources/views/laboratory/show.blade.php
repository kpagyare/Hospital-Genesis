@extends('layouts.app')
@section('title','Lab Result')
@section('page_title','Lab Result Details')
@section('breadcrumb','Home / Laboratory / '.$laboratory->result_id)
@section('content')
<div class="page-header">
    <h4 class="page-title">Lab Result <small>{{ $laboratory->result_id }}</small></h4>
    <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Test Information</h6></div>
            <div class="card-body small">
                <div class="mb-2"><strong>Result ID:</strong> <span class="badge bg-light text-dark border">{{ $laboratory->result_id }}</span></div>
                <div class="mb-2"><strong>Patient:</strong> {{ $laboratory->patient->full_name ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Doctor:</strong> {{ $laboratory->doctor->full_name ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Test:</strong> {{ $laboratory->labTest->name ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Date:</strong> {{ $laboratory->test_date->format('d M Y') }}</div>
                <div class="mb-2"><strong>Normal Range:</strong> {{ $laboratory->labTest->normal_range ?? 'N/A' }} {{ $laboratory->labTest->unit ?? '' }}</div>
                <div class="mb-2"><strong>Status:</strong>
                    <span class="badge {{ match($laboratory->status) { 'completed'=>'bg-success','in_progress'=>'bg-info','cancelled'=>'bg-danger',default=>'bg-warning text-dark' } }}">
                        {{ ucfirst(str_replace('_',' ',$laboratory->status)) }}
                    </span>
                </div>
                @if($laboratory->report_file)
                <div class="mt-3">
                    <a href="{{ asset('storage/'.$laboratory->report_file) }}" target="_blank" class="btn btn-sm btn-outline-success" style="border-radius:7px;"><i class="bi bi-file-earmark-pdf me-1"></i>View Report</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Update Result</h6></div>
            <div class="card-body">
                <form action="{{ route('laboratory.update', $laboratory) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Result Value</label>
                            <input type="text" name="result_value" value="{{ old('result_value',$laboratory->result_value) }}" class="form-control" placeholder="e.g. 5.6 mmol/L, Negative">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                @foreach(['pending','in_progress','completed','cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status',$laboratory->status)==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="4" placeholder="Clinical remarks, interpretation...">{{ old('remarks',$laboratory->remarks) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Upload Report (PDF/Image)</label>
                            <input type="file" name="report_file" class="form-control" accept=".pdf,.jpg,.png">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Update Result</button>
                    </div>
                </form>
            </div>
        </div>
        @if($laboratory->remarks)
        <div class="card">
            <div class="card-header"><h6 class="card-title">Remarks</h6></div>
            <div class="card-body"><p class="text-muted small mb-0">{{ $laboratory->remarks }}</p></div>
        </div>
        @endif
    </div>
</div>
@endsection
