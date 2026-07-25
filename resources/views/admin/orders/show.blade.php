@extends('layouts.admin')
@section('title', 'Order #' . $order->id)
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-cart-check text-success"></i> Order #{{ $order->id }}</h4>
        <p>Order details, items, and fulfillment</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Orders</a>
    <a href="{{ route('admin.orders.print_invoice', $order->id) }}" class="btn btn-success btn-sm" target="_blank"><i class="bi bi-printer me-1"></i> Print Invoice</a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i> Order Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($order->details as $detail)
                                <tr>
                                    <td class="fw-semibold">{{ $detail->product->product_name }}</td>
                                    <td>${{ number_format($detail->unit_price, 2) }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td class="fw-bold">${{ number_format($detail->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="3" class="text-end">Total:</th><th class="fw-bold" style="color:var(--primary);">${{ number_format($order->total_amount, 2) }}</th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i> Order Info</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Customer</div><div class="modal-detail-value">{{ $order->customer->user->name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Email</div><div class="modal-detail-value">{{ $order->customer->user->email ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Phone</div><div class="modal-detail-value">{{ $order->customer->user->phone ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Address</div><div class="modal-detail-value">{{ $order->delivery_address }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Payment</div><div class="modal-detail-value">{{ strtoupper($order->payment_method) }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Pay Status</div><div class="modal-detail-value"><span class="badge-status bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} text-white">{{ ucfirst($order->payment_status) }}</span></div></div>
                @if($order->note)<div class="modal-detail-row"><div class="modal-detail-label">Note</div><div class="modal-detail-value">{{ $order->note }}</div></div>@endif
            </div>
        </div>

        @if($order->latitude && $order->longitude)
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-geo-alt me-2"></i> Delivery Location</div>
            <div class="card-body p-2">
                <div id="adminOrderMap" style="height: 200px; border-radius: 10px;"></div>
                <div class="d-flex justify-content-between align-items-center mt-2 px-2 pb-1">
                    <small class="text-muted" style="font-size:0.72rem;">{{ number_format($order->latitude, 6) }}, {{ number_format($order->longitude, 6) }}</small>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn btn-xs btn-outline-primary">
                        <i class="bi bi-sign-turn-right me-1"></i> Maps
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i> Update Status</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="order_status" class="form-select mb-2">
                        @foreach(['pending','confirmed','packing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->order_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i> Update Status</button>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-truck me-2"></i> Assign Delivery</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.assign_delivery', $order->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="delivery_staff_id" class="form-select mb-2" required>
                        <option value="">Select Staff</option>
                        @foreach($deliveryStaff as $staff)
                            <option value="{{ $staff->id }}" {{ $order->delivery && $order->delivery->delivery_staff_id == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ $staff->phone }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-info w-100"><i class="bi bi-truck me-1"></i> Assign</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($order->delivery)
    <div class="card card-custom mt-4">
        <div class="card-header"><i class="bi bi-truck me-2"></i> Delivery Info</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Tracking:</strong> {{ $order->delivery->tracking_no ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Staff:</strong> {{ $order->delivery->staff->name ?? 'Not assigned' }}</div>
                <div class="col-md-3"><strong>Status:</strong> <span class="badge-status bg-info text-white">{{ str_replace('_', ' ', ucfirst($order->delivery->delivery_status)) }}</span></div>
                <div class="col-md-3"><strong>Date:</strong> {{ $order->delivery->delivery_date ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
@endif

@if($order->latitude && $order->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('adminOrderMap', { zoomControl: true, attributionControl: false }).setView([{{ $order->latitude }}, {{ $order->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $order->latitude }}, {{ $order->longitude }}]).addTo(map).bindPopup('{!! e($order->delivery_address) !!}').openPopup();
    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endif
@endsection
