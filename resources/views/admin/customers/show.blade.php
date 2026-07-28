@extends('layouts.admin')
@section('title', 'Customer: ' . ($customer->user?->name ?? 'N/A'))
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-person-circle text-primary"></i> Customer: {{ $customer->user?->name ?? 'N/A' }}</h4>
        <p>Customer profile and order history</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-person me-2"></i> Profile</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Name</div><div class="modal-detail-value">{{ $customer->user?->name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Email</div><div class="modal-detail-value">{{ $customer->user?->email ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Phone</div><div class="modal-detail-value">{{ $customer->user->phone ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Address</div><div class="modal-detail-value">{{ $customer->address ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">City</div><div class="modal-detail-value">{{ $customer->city ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Joined</div><div class="modal-detail-value">{{ $customer->created_at->format('d M Y') }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i> Order History ({{ $customer->orders->count() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead><tr><th>#</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th>Payment</th></tr></thead>
                        <tbody>
                            @forelse($customer->orders as $order)
                                <tr>
                                    <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $order->id }}</span></td>
                                    <td><span class="text-muted">{{ $order->created_at->format('d/m/Y') }}</span></td>
                                    <td>{{ $order->details->count() }}</td>
                                    <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td><span class="badge-status bg-{{ $order->order_status == 'delivered' ? 'success' : 'warning' }} text-white">{{ ucfirst($order->order_status) }}</span></td>
                                    <td><span class="badge-status bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }} text-white">{{ ucfirst($order->payment_status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
