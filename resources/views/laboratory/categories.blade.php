@extends('layouts.app')
@section('title','Lab Categories')
@section('page_title','Lab Test Categories')
@section('breadcrumb','Home / Laboratory / Categories')
@section('content')
<div class="page-header">
    <h4 class="page-title">Lab Test Categories</h4>
    <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-plus-circle me-2"></i>Add Category</h6></div>
            <form action="{{ route('laboratory.categories.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-3"><label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name')is-invalid@enderror" placeholder="e.g. Hematology" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Optional description...">{{ old('description') }}</textarea></div>
            </div>
            <div class="card-body border-top">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Add Category</button>
            </div>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title">All Categories</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Category</th><th>Description</th><th>Tests</th><th class="text-center">Actions</th></tr></thead>
                        <tbody>
                            @forelse($categories as $cat)
                            <tr>
                                <td class="fw-600 small">{{ $cat->name }}</td>
                                <td class="small text-muted">{{ $cat->description ?? '—' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $cat->tests_count ?? $cat->labTests->count() }}</span></td>
                                <td class="text-center">
                                    <form action="{{ route('laboratory.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-5">No categories yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
