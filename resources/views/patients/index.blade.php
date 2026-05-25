@extends('layouts.app')
@section('title','Patients')
@section('page_title','Patient Management')
@section('breadcrumb','Home / Patients')

@section('content')
<div class="page-header">
    <div>
        <h4 class="page-title">All Patients
            <small>{{ $patients->total() }} total records</small>
        </h4>
    </div>
    <a href="{{ route('patients.create') }}" class="btn-hms-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Add New Patient
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, ID, phone...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select">
                    <option value="">All Genders</option>
                    <option value="male"   {{ request('gender')=='male'   ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>Female</option>
                    <option value="other"  {{ request('gender')=='other'  ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="deceased" {{ request('status')=='deceased' ? 'selected' : '' }}>Deceased</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 16px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Patient Table -->
<div class="card">
    <div class="card-body p-0">
        @if($patients->isEmpty())
            <div class="empty-state">
                <i class="bi bi-person-x"></i>
                <h6>No Patients Found</h6>
                <p>Add your first patient to get started.</p>
                <a href="{{ route('patients.create') }}" class="btn-hms-primary mt-3">Add Patient</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Patient ID</th>
                        <th>Phone</th>
                        <th>Gender / Age</th>
                        <th>Blood Group</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $patient->photo_url }}" alt="Photo" class="table-avatar">
                                <div>
                                    <div class="table-name">{{ $patient->full_name }}</div>
                                    <div class="table-sub">{{ $patient->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $patient->patient_id }}</span></td>
                        <td class="text-muted small">{{ $patient->phone }}</td>
                        <td class="text-muted small">{{ ucfirst($patient->gender) }} {{ $patient->age ? '/ '.$patient->age.'y' : '' }}</td>
                        <td>
                            @if($patient->blood_group)
                                <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($patient->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($patient->status === 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-dark">Deceased</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary" title="View" style="border-radius:6px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-warning" title="Edit" style="border-radius:6px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Delete this patient?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" style="border-radius:6px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-4 py-3">
            {{ $patients->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
