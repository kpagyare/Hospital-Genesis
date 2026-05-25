@extends('layouts.app')
@section('title','Expenses')
@section('page_title','Expense Management')
@section('breadcrumb','Home / Billing / Expenses')

@section('content')
<div class="page-header">
    <h4 class="page-title">Expenses <small>This month: ${{ number_format($totalExpenses,2) }}</small></h4>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-plus-circle me-2"></i>Add Expense</h6></div>
            <div class="card-body">
                <form action="{{ route('billing.expenses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title')is-invalid@enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category')is-invalid@enderror" required>
                            <option value="">Select Category</option>
                            @foreach(['Salary','Utilities','Equipment','Medicine Purchase','Maintenance','Supplies','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category')==$cat ? 'selected':'' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount" class="form-control @error('amount')is-invalid@enderror" value="{{ old('amount') }}" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional notes...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn-hms-primary w-100">Add Expense</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Date</th><th>Amount</th><th>Added By</th></tr></thead>
                        <tbody>
                            @forelse($expenses as $exp)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $exp->expense_id }}</span></td>
                                <td class="small fw-600">{{ $exp->title }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $exp->category }}</span></td>
                                <td class="small">{{ $exp->expense_date->format('d M Y') }}</td>
                                <td class="small fw-600 text-danger">${{ number_format($exp->amount,2) }}</td>
                                <td class="small text-muted">{{ $exp->createdBy->name ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">No expenses recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">{{ $expenses->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
