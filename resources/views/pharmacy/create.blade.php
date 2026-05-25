@extends('layouts.app')
@section('title','Add Medicine')
@section('page_title','Add Medicine')
@section('breadcrumb','Home / Pharmacy / Add')

@section('content')
<div class="page-header">
    <h4 class="page-title">Add New Medicine</h4>
    <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-9">
<form action="{{ route('pharmacy.store') }}" method="POST">
@csrf
<div class="card">
    <div class="card-header"><h6 class="card-title">Medicine Details</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name')is-invalid@enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Generic Name</label>
                <input type="text" name="generic_name" value="{{ old('generic_name') }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Select Type</option>
                    @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Powder','Other'] as $t)
                    <option value="{{ $t }}" {{ old('type')==$t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Unit <span class="text-danger">*</span></label>
                <select name="unit" class="form-select">
                    @foreach(['piece','strip','bottle','vial','tube','mg','ml'] as $u)
                    <option value="{{ $u }}" {{ old('unit','piece')==$u ? 'selected':'' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Manufacturer</label>
                <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Purchase Price ($) <span class="text-danger">*</span></label>
                <input type="number" name="purchase_price" value="{{ old('purchase_price',0) }}" class="form-control" min="0" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Selling Price ($) <span class="text-danger">*</span></label>
                <input type="number" name="selling_price" value="{{ old('selling_price',0) }}" class="form-control" min="0" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity',0) }}" class="form-control" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Low Stock Alert</label>
                <input type="number" name="low_stock_alert" value="{{ old('low_stock_alert',10) }}" class="form-control" min="0">
                <div class="form-text">Alert when stock falls below this</div>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional description...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-body border-top">
        <div class="d-flex gap-2">
            <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Save Medicine</button>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
        </div>
    </div>
</div>
</form>
</div></div>
@endsection
