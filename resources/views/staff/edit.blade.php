@extends('layouts.app')
@section('title','Edit Staff')
@section('page_title','Edit Staff')
@section('breadcrumb','Home / Staff / Edit')
@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Staff Member</h4>
    <a href="{{ route('staff.show', $staff) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<form action="{{ route('staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2"></i>Personal Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name',$staff->first_name) }}" class="form-control @error('first_name')is-invalid@enderror" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name',$staff->last_name) }}" class="form-control @error('last_name')is-invalid@enderror" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email',$staff->email) }}" class="form-control @error('email')is-invalid@enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone',$staff->phone) }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">-- Select --</option>
                            @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" {{ old('gender',$staff->gender)==$g?'selected':'' }}>{{ $g }}</option>
                            @endforeach
                        </select></div>
                    <div class="col-md-6"><label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address',$staff->address) }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-briefcase me-2"></i>Employment Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select @error('department')is-invalid@enderror" required>
                            <option value="">-- Select Department --</option>
                            @foreach(['Administration','Emergency','Cardiology','Neurology','Orthopedics','Pediatrics','Radiology','Laboratory','Pharmacy','Nursing','Surgery','Outpatient','Finance','IT','Housekeeping'] as $dept)
                            <option value="{{ $dept }}" {{ old('department',$staff->department)==$dept?'selected':'' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" name="position" value="{{ old('position',$staff->position) }}" class="form-control @error('position')is-invalid@enderror" required>
                        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label">System Role</label>
                        <select name="role" class="form-select">
                            <option value="">-- Keep Current --</option>
                            <option value="nurse"        {{ old('role')==='nurse'       ?'selected':'' }}>Nurse</option>
                            <option value="receptionist" {{ old('role')==='receptionist'?'selected':'' }}>Receptionist</option>
                            <option value="pharmacist"   {{ old('role')==='pharmacist'  ?'selected':'' }}>Pharmacist</option>
                            <option value="lab_staff"    {{ old('role')==='lab_staff'   ?'selected':'' }}>Lab Staff</option>
                            <option value="accountant"   {{ old('role')==='accountant'  ?'selected':'' }}>Accountant</option>
                        </select></div>
                    <div class="col-md-6"><label class="form-label">Join Date</label>
                        <input type="date" name="join_date" value="{{ old('join_date', $staff->join_date?->format('Y-m-d')) }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Salary ($)</label>
                        <input type="number" name="salary" value="{{ old('salary',$staff->salary) }}" class="form-control" min="0" step="0.01"></div>
                    <div class="col-md-6"><label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active"     {{ old('status',$staff->status)=='active'    ?'selected':'' }}>Active</option>
                            <option value="on_leave"   {{ old('status',$staff->status)=='on_leave'  ?'selected':'' }}>On Leave</option>
                            <option value="terminated" {{ old('status',$staff->status)=='terminated'?'selected':'' }}>Terminated</option>
                        </select></div>
                    <div class="col-12"><label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes',$staff->notes) }}</textarea></div>
                </div>
            </div>
            <div class="card-body border-top">
                <button type="submit" class="btn-hms-primary me-2"><i class="bi bi-check-circle me-1"></i>Update Staff</button>
                <a href="{{ route('staff.show', $staff) }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-camera me-2"></i>Photo</h6></div>
            <div class="card-body text-center">
                <div id="photoPreview" style="width:120px;height:120px;border-radius:50%;margin:0 auto 16px;overflow:hidden;border:3px solid var(--primary);">
                    @if($staff->photo)
                    <img src="{{ asset('storage/'.$staff->photo) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="width:100%;height:100%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                    @endif
                </div>
                <label class="btn btn-outline-secondary btn-sm" style="border-radius:8px;cursor:pointer;">
                    <i class="bi bi-upload me-1"></i>Change Photo
                    <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                </label>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header"><h6 class="card-title">Staff Info</h6></div>
            <div class="card-body">
                <div class="text-muted small mb-1">Staff ID</div>
                <div class="fw-600 mb-3">{{ $staff->staff_id }}</div>
                @if($staff->user)
                <div class="text-muted small mb-1">Current Role</div>
                <div><span class="badge bg-light text-dark border">{{ $staff->user->role_label }}</span></div>
                @endif
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('photoPreview').innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
