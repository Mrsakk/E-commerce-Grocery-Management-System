@extends('layouts.admin')
@section('title', 'Coupon Details')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-tag text-success"></i> Coupon Details</h4>
        <p>{{ $coupon->code }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:160px;">Code</td><td class="fw-bold">{{ $coupon->code }}</td></tr>
                    <tr><td class="text-muted">Discount Type</td><td class="text-capitalize">{{ $coupon->discount_type }}</td></tr>
                    <tr><td class="text-muted">Discount Value</td><td class="fw-bold text-success">{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : '$'.number_format($coupon->discount_value, 2) }}</td></tr>
                    <tr><td class="text-muted">Min Order Amount</td><td>{{ $coupon->min_order_amount ? '$'.number_format($coupon->min_order_amount, 2) : '—' }}</td></tr>
                    <tr><td class="text-muted">Max Discount</td><td>{{ $coupon->max_discount ? '$'.number_format($coupon->max_discount, 2) : '—' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:160px;">Applies To</td><td class="text-capitalize">{{ $coupon->applies_to }}</td></tr>
                    <tr><td class="text-muted">Usage Limit</td><td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td></tr>
                    <tr><td class="text-muted">Start Date</td><td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">End Date</td><td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Status</td><td><span class="badge bg-{{ $coupon->status == 'active' ? 'success' : 'secondary' }} text-white">{{ ucfirst($coupon->status) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection