@extends('layouts.app')
@section('title','Lab Test List')
@section('page_title','Lab Test Catalog')
@section('breadcrumb','Home / Laboratory / Tests')
@section('content')
<div class="page-header">
    <h4 class="page-title">Lab Tests Catalog</h4>
    <a href="{{ route('laboratory.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Request Test</a>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title">Add Lab Test</h6></div>
            <div class="card-body">
                <form action="{{ route('laboratory.tests.store') }}" method="POST">
                @csrf
                    <div class="mb-3"><label class="form-label">Test Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name')is-invalid@enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">None</option>
                            @foreach(\App\Models\LabTestCategory::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-3"><label class="form-label">Price ($)</label>
                        <input type="number" name="price" class="form-control" value="0" min="0" step="0.01" required></div>
                    <div class="mb-3"><label class="form-label">Normal Range</label>
                        <input type="text" name="normal_range" class="form-control" placeholder="e.g. 4.0-6.0"></div>
                    <div class="mb-3"><label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. mmol/L"></div>
                    <button type="submit" class="btn-hms-primary w-100">Add Test</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Code</th><th>Test Name</th><th>Category</th><th>Price</th><th>Normal Range</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $test->test_code }}</span></td>
                            <td class="small fw-600">{{ $test->name }}</td>
                            <td class="small text-muted">{{ $test->category->name ?? '—' }}</td>
                            <td class="small">${{ number_format($test->price,2) }}</td>
                            <td class="small text-muted">{{ $test->normal_range ?? '—' }} {{ $test->unit }}</td>
                            <td><span class="badge {{ $test->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($test->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No tests added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3">{{ $tests->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
