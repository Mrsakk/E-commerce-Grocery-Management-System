@extends('layouts.admin')
@section('title', 'Coupons')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-tag text-success"></i> Coupons & Promotions</h4>
        <p>Create and manage discount coupons for your customers</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg"></i> Add Coupon
    </a>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Coupons</div>
        <span class="text-muted small">{{ $coupons->count() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Uses</th>
                        <th>Period</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $c)
                        @php
                            $now = now();
                            $expired = $now->gt($c->end_date);
                        @endphp
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $c->id }}</span></td>
                            <td><span class="fw-bold text-uppercase" style="letter-spacing:1px;">{{ $c->code }}</span></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 fw-semibold" style="font-size:0.75rem;">{{ ucfirst($c->discount_type) }}</span></td>
                            <td><span class="fw-bold" style="color:var(--gray-900);">{{ $c->discount_type == 'percentage' ? $c->discount_value . '%' : '$' . number_format($c->discount_value, 2) }}</span></td>
                            <td>${{ number_format($c->min_order_amount, 2) }}</td>
                            <td><span class="fw-semibold">{{ $c->used_count }}{{ $c->usage_limit ? '/' . $c->usage_limit : '' }}</span></td>
                            <td><small class="text-muted">{{ $c->start_date->format('d/m/Y') }} - {{ $c->end_date->format('d/m/Y') }}</small></td>
                            <td>
                                <span class="badge-status bg-{{ $expired ? 'secondary' : ($c->status == 'active' ? 'success' : 'danger') }} text-white">
                                    <i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> {{ $expired ? 'Expired' : ucfirst($c->status) }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-tag d-block"></i>
                                    <h5>No Coupons Found</h5>
                                    <p>Create your first discount coupon to attract customers.</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Coupon
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($coupons, 'links') && $coupons->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
