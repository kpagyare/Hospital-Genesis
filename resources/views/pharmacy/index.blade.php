@extends('layouts.app')
@section('title','Pharmacy')
@section('page_title','Pharmacy Management')
@section('breadcrumb','Home / Pharmacy')

@section('content')
<div class="page-header">
    <h4 class="page-title">Medicine Inventory</h4>
    <a href="{{ route('pharmacy.create') }}" class="btn-hms-primary"><i class="bi bi-plus-circle me-1"></i>Add Medicine</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-capsule"></i></div>
            <div class="stat-info"><div class="stat-label">Total Medicines</div><div class="stat-value">{{ $medicines->total() }}</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card orange"><div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-info"><div class="stat-label">Low Stock</div><div class="stat-value">{{ $lowStock }}</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card red"><div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-info"><div class="stat-label">Out of Stock</div><div class="stat-value">{{ $outOfStock }}</div></div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <div class="search-bar"><i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search medicines...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status')=='active'  ?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-hms-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:9px 12px;font-size:13px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Medicine</th><th>Category</th><th>Type</th><th>Stock</th><th>Selling Price</th><th>Expiry</th><th>Status</th><th class="text-center">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($medicines as $med)
                    <tr>
                        <td>
                            <div class="table-name">{{ $med->name }}</div>
                            <div class="table-sub">{{ $med->medicine_id }} &bull; {{ $med->brand }}</div>
                        </td>
                        <td class="small text-muted">{{ $med->category->name ?? '—' }}</td>
                        <td class="small">{{ $med->type }}</td>
                        <td>
                            <span class="badge {{ $med->stock_quantity == 0 ? 'bg-danger' : ($med->isLowStock() ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ $med->stock_quantity }} {{ $med->unit }}
                            </span>
                        </td>
                        <td class="small fw-600">${{ number_format($med->selling_price,2) }}</td>
                        <td class="small {{ $med->isExpired() ? 'text-danger fw-600' : 'text-muted' }}">
                            {{ $med->expiry_date ? $med->expiry_date->format('M Y') : '—' }}
                        </td>
                        <td><span class="badge {{ $med->status=='active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($med->status) }}</span></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('pharmacy.edit', $med) }}" class="btn btn-sm btn-outline-warning" style="border-radius:6px;"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('pharmacy.destroy', $med) }}" method="POST" onsubmit="return confirm('Delete this medicine?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">No medicines found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $medicines->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
