@extends('layouts.admin')
@section('title', 'Add Coupon')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-plus-circle text-success"></i> Add Coupon</h4>
        <p>Create a new discount coupon</p>
    </div>
</div>
<div class="form-card" style="max-width:720px;">
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Discount Type</label>
                <select name="discount_type" class="form-select">
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount ($)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Discount (cap)</label>
                <input type="number" step="0.01" name="max_discount" class="form-control" value="{{ old('max_discount') }}" placeholder="Optional">
            </div>
            <div class="col-md-4">
                <label class="form-label">Min Order Amount</label>
                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', 0) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Usage Limit</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" placeholder="Unlimited if empty">
            </div>
            <div class="col-md-4">
                <label class="form-label">Applies To</label>
                <select name="applies_to" class="form-select">
                    <option value="all">All Products</option>
                    <option value="category">Category</option>
                    <option value="product">Product</option>
                    <option value="delivery">Delivery Fee</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Date <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Save Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
