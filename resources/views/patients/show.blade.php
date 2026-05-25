@extends('layouts.app')
@section('title', $patient->full_name)
@section('page_title', 'Patient Profile')
@section('breadcrumb', 'Home / Patients / '.$patient->full_name)

@section('content')
<div class="page-header">
    <h4 class="page-title">Patient Profile <small>{{ $patient->patient_id }}</small></h4>
    <div class="d-flex gap-2">
        <a href="{{ route('patients.edit', $patient) }}" class="btn-hms-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4 col-xl-3">
        <div class="card text-center">
            <div class="card-body py-4">
                <img src="{{ $patient->photo_url }}" alt="Photo" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);margin-bottom:12px;">
                <h5 class="fw-700" style="color:var(--primary);">{{ $patient->full_name }}</h5>
                <p class="text-muted small">{{ $patient->patient_id }}</p>
                <span class="badge {{ $patient->status === 'active' ? 'bg-success' : 'bg-secondary' }} mb-3">{{ ucfirst($patient->status) }}</span>

                <div class="border-top pt-3 text-start">
                    @if($patient->blood_group)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-droplet-fill text-danger"></i>
                        <span class="small"><strong>Blood Group:</strong> {{ $patient->blood_group }}</span>
                    </div>
                    @endif
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-gender-ambiguous text-primary"></i>
                        <span class="small"><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</span>
                    </div>
                    @if($patient->age)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-calendar text-primary"></i>
                        <span class="small"><strong>Age:</strong> {{ $patient->age }} years</span>
                    </div>
                    @endif
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-telephone text-success"></i>
                        <span class="small">{{ $patient->phone }}</span>
                    </div>
                    @if($patient->email)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-envelope text-warning"></i>
                        <span class="small">{{ $patient->email }}</span>
                    </div>
                    @endif
                    @if($patient->city || $patient->country)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-geo-alt text-danger"></i>
                        <span class="small">{{ implode(', ', array_filter([$patient->city, $patient->country])) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        @if($patient->emergency_contact_name)
        <div class="card">
            <div class="card-header"><h6 class="card-title text-danger"><i class="bi bi-telephone-plus me-1"></i>Emergency Contact</h6></div>
            <div class="card-body">
                <p class="mb-1 fw-600 small">{{ $patient->emergency_contact_name }}</p>
                <p class="mb-1 text-muted small">{{ $patient->emergency_contact_phone }}</p>
                @if($patient->emergency_contact_relation)
                <span class="badge bg-light text-dark border">{{ $patient->emergency_contact_relation }}</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Details Tabs -->
    <div class="col-lg-8 col-xl-9">
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs border-0 px-4 pt-3" id="patientTabs">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#medical">Medical History</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#appointments">Appointments ({{ $patient->appointments->count() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#prescriptions">Prescriptions ({{ $patient->prescriptions->count() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#labresults">Lab Results ({{ $patient->labResults->count() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#invoices">Billing ({{ $patient->invoices->count() }})</a></li>
                </ul>
            </div>
            <div class="tab-content p-4">
                <!-- Medical History -->
                <div class="tab-pane fade show active" id="medical">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-600 text-primary mb-2"><i class="bi bi-journal-medical me-1"></i>Medical History</h6>
                            <p class="small text-muted">{{ $patient->medical_history ?: 'No medical history recorded.' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-600 text-danger mb-2"><i class="bi bi-exclamation-triangle me-1"></i>Known Allergies</h6>
                            <p class="small text-muted">{{ $patient->allergies ?: 'No known allergies.' }}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="fw-600 mb-2"><i class="bi bi-geo-alt me-1"></i>Full Address</h6>
                            <p class="small text-muted">{{ implode(', ', array_filter([$patient->address, $patient->city, $patient->state, $patient->country])) ?: 'Not provided.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Appointments -->
                <div class="tab-pane fade" id="appointments">
                    @if($patient->appointments->isEmpty())
                        <div class="empty-state"><i class="bi bi-calendar-x"></i><h6>No appointments found</h6></div>
                    @else
                    <table class="table">
                        <thead><tr><th>ID</th><th>Doctor</th><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($patient->appointments->sortByDesc('appointment_date') as $apt)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $apt->appointment_id }}</span></td>
                                <td class="small">{{ $apt->doctor->full_name ?? 'N/A' }}</td>
                                <td class="small">{{ $apt->appointment_date->format('d M Y') }}</td>
                                <td class="small">{{ $apt->appointment_time }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($apt->type) }}</span></td>
                                <td>{!! $apt->status_badge !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                <!-- Prescriptions -->
                <div class="tab-pane fade" id="prescriptions">
                    @if($patient->prescriptions->isEmpty())
                        <div class="empty-state"><i class="bi bi-prescription2"></i><h6>No prescriptions found</h6></div>
                    @else
                    <table class="table">
                        <thead><tr><th>ID</th><th>Doctor</th><th>Date</th><th>Medicines</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($patient->prescriptions->sortByDesc('prescription_date') as $pre)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $pre->prescription_id }}</span></td>
                                <td class="small">{{ $pre->doctor->full_name ?? 'N/A' }}</td>
                                <td class="small">{{ $pre->prescription_date->format('d M Y') }}</td>
                                <td class="small">{{ $pre->items->count() }} item(s)</td>
                                <td><span class="badge {{ $pre->status==='dispensed' ? 'bg-success' : ($pre->status==='cancelled' ? 'bg-danger' : 'bg-warning') }}">{{ ucfirst($pre->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                <!-- Lab Results -->
                <div class="tab-pane fade" id="labresults">
                    @if($patient->labResults->isEmpty())
                        <div class="empty-state"><i class="bi bi-eyedropper"></i><h6>No lab results found</h6></div>
                    @else
                    <table class="table">
                        <thead><tr><th>ID</th><th>Test</th><th>Date</th><th>Result</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($patient->labResults->sortByDesc('test_date') as $res)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $res->result_id }}</span></td>
                                <td class="small">{{ $res->labTest->name ?? 'N/A' }}</td>
                                <td class="small">{{ $res->test_date->format('d M Y') }}</td>
                                <td class="small">{{ $res->result_value ?? '—' }}</td>
                                <td><span class="badge {{ $res->status==='completed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($res->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                <!-- Invoices -->
                <div class="tab-pane fade" id="invoices">
                    @if($patient->invoices->isEmpty())
                        <div class="empty-state"><i class="bi bi-receipt"></i><h6>No invoices found</h6></div>
                    @else
                    <table class="table">
                        <thead><tr><th>Invoice #</th><th>Date</th><th>Total</th><th>Paid</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach($patient->invoices->sortByDesc('invoice_date') as $inv)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $inv->invoice_number }}</span></td>
                                <td class="small">{{ $inv->invoice_date->format('d M Y') }}</td>
                                <td class="small fw-600">${{ number_format($inv->total_amount, 2) }}</td>
                                <td class="small">${{ number_format($inv->paid_amount, 2) }}</td>
                                <td>{!! $inv->status_badge !!}</td>
                                <td><a href="{{ route('billing.show', $inv) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;"><i class="bi bi-eye"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
