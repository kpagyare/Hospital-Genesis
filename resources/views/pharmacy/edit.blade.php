@extends('layouts.app')
@section('title','Edit Medicine')
@section('page_title','Edit Medicine')
@section('breadcrumb','Home / Pharmacy / Edit')
@section('content')
<div class="page-header">
    <h4 class="page-title">Edit Medicine <small>{{ $medicine->medicine_id }}</small></h4>
    <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-9">
<form action="{{ route('pharmacy.update', $medicine) }}" method="POST">
@csrf @method('PUT')
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name',$medicine->name) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">None</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id',$medicine->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Generic Name</label>
                <input type="text" name="generic_name" value="{{ old('generic_name',$medicine->generic_name) }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Brand</label>
                <input type="text" name="brand" value="{{ old('brand',$medicine->brand) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Type</label>
                <input type="text" name="type" value="{{ old('type',$medicine->type) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Unit</label>
                <input type="text" name="unit" value="{{ old('unit',$medicine->unit) }}" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Manufacturer</label>
                <input type="text" name="manufacturer" value="{{ old('manufacturer',$medicine->manufacturer) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Purchase Price ($)</label>
                <input type="number" name="purchase_price" value="{{ old('purchase_price',$medicine->purchase_price) }}" class="form-control" min="0" step="0.01"></div>
            <div class="col-md-4"><label class="form-label">Selling Price ($)</label>
                <input type="number" name="selling_price" value="{{ old('selling_price',$medicine->selling_price) }}" class="form-control" min="0" step="0.01"></div>
            <div class="col-md-4"><label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date',$medicine->expiry_date?->format('Y-m-d')) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Stock Qty</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity',$medicine->stock_quantity) }}" class="form-control" min="0"></div>
            <div class="col-md-4"><label class="form-label">Low Stock Alert</label>
                <input type="number" name="low_stock_alert" value="{{ old('low_stock_alert',$medicine->low_stock_alert) }}" class="form-control" min="0"></div>
            <div class="col-md-4"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   {{ old('status',$medicine->status)=='active'  ?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status',$medicine->status)=='inactive'?'selected':'' }}>Inactive</option>
                </select></div>
            <div class="col-12"><label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description',$medicine->description) }}</textarea></div>
        </div>
    </div>
    <div class="card-body border-top">
        <div class="d-flex gap-2">
            <button type="submit" class="btn-hms-primary"><i class="bi bi-check-circle me-1"></i>Update Medicine</button>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Cancel</a>
        </div>
    </div>
</div>
</form>
</div></div>
@endsection
