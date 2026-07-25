@extends('layouts.admin')
@section('title', 'Edit Coupon')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-pencil-square text-primary"></i> Edit Coupon</h4>
        <p>Update: {{ $coupon->code }}</p>
    </div>
</div>
<div class="form-card" style="max-width:720px;">
    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $coupon->code) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Discount Type</label>
                <select name="discount_type" class="form-select">
                    <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Discount</label>
                <input type="number" step="0.01" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Min Order Amount</label>
                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Usage Limit</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Applies To</label>
                <select name="applies_to" class="form-select">
                    <option value="all" {{ $coupon->applies_to == 'all' ? 'selected' : '' }}>All Products</option>
                    <option value="category" {{ $coupon->applies_to == 'category' ? 'selected' : '' }}>Category</option>
                    <option value="product" {{ $coupon->applies_to == 'product' ? 'selected' : '' }}>Product</option>
                    <option value="delivery" {{ $coupon->applies_to == 'delivery' ? 'selected' : '' }}>Delivery Fee</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $coupon->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $coupon->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Date <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $coupon->end_date->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Update Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
