@extends('layouts.app')
@section('title','Doctors')
@section('page_title','Doctor Management')
@section('breadcrumb','Home / Doctors')

@section('content')
<div class="page-header">
    <h4 class="page-title">All Doctors <small>{{ $doctors->total() }} registered</small></h4>
    <a href="{{ route('doctors.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Add Doctor</a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, ID, specialization...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="specialization" class="form-select">
                    <option value="">All Specializations</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec }}" {{ request('specialization')==$spec ? 'selected':'' }}>{{ $spec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Active</option>
                    <option value="on_leave" {{ request('status')=='on_leave' ? 'selected':'' }}>On Leave</option>
                    <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Doctors Grid -->
<div class="row g-4">
    @forelse($doctors as $doctor)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card h-100" style="transition:all .25s ease;">
            <div class="card-body text-center p-4">
                <div style="position:relative;display:inline-block;margin-bottom:12px;">
                    <img src="{{ $doctor->photo_url }}" alt="Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                    <span style="position:absolute;bottom:2px;right:2px;width:14px;height:14px;border-radius:50%;border:2px solid #fff;background:{{ $doctor->status==='active' ? '#10b981' : ($doctor->status==='on_leave' ? '#f59e0b' : '#9ca3af') }};"></span>
                </div>
                <h6 class="fw-700 mb-0" style="color:var(--primary);">{{ $doctor->full_name }}</h6>
                <p class="text-accent small mb-2">{{ $doctor->specialization }}</p>
                <p class="text-muted small mb-3">{{ $doctor->qualification ?? '' }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-light text-dark border small">{{ $doctor->doctor_id }}</span>
                    <span class="badge {{ $doctor->status==='active' ? 'bg-success' : ($doctor->status==='on_leave' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst(str_replace('_',' ',$doctor->status)) }}</span>
                </div>
                <div class="border-top pt-3">
                    <div class="row text-center g-0">
                        <div class="col-6 border-end">
                            <div class="fw-700" style="color:var(--primary);">{{ $doctor->experience_years }}</div>
                            <div style="font-size:11px;color:#9ca3af;">Yrs Exp.</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-700" style="color:var(--accent);">${{ number_format($doctor->consultation_fee,0) }}</div>
                            <div style="font-size:11px;color:#9ca3af;">Fee</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2 justify-content-center">
                    <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary" style="border-radius:7px;font-size:12px;"><i class="bi bi-eye"></i> View</a>
                    <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-warning" style="border-radius:7px;font-size:12px;"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Delete this doctor?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;font-size:12px;"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state card py-5"><i class="bi bi-person-badge"></i><h6>No Doctors Found</h6><p>Add your first doctor.</p></div>
    </div>
    @endforelse
</div>
<div class="mt-4">{{ $doctors->withQueryString()->links() }}</div>
@endsection
