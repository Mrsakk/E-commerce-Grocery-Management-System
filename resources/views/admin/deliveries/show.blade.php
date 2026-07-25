@extends('layouts.admin')
@section('title', 'Delivery SHP-' . str_pad($delivery->id, 6, '0', STR_PAD_LEFT))
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-truck text-info"></i> Delivery SHP-{{ str_pad($delivery->id, 6, '0', STR_PAD_LEFT) }}</h4>
        <p>Order #{{ $delivery->order_id }} &mdash; {{ $delivery->order->customer->user->name ?? 'Guest' }}</p>
    </div>
    <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i> Delivery Details</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Customer</div><div class="modal-detail-value">{{ $delivery->order->customer->user->name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Address</div><div class="modal-detail-value">{{ $delivery->order->delivery_address }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Staff</div><div class="modal-detail-value">{{ $delivery->staff->name ?? 'N/A' }} {{ $delivery->staff ? '(' . $delivery->staff->phone . ')' : '' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Tracking No</div><div class="modal-detail-value font-monospace fw-semibold">{{ $delivery->tracking_no ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Status</div><div class="modal-detail-value"><span class="badge-status bg-{{ $delivery->delivery_status == 'delivered' ? 'success' : ($delivery->delivery_status == 'failed' ? 'danger' : 'warning') }} text-white">{{ str_replace('_', ' ', ucfirst($delivery->delivery_status)) }}</span></div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Date</div><div class="modal-detail-value">{{ $delivery->delivery_date ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Received By</div><div class="modal-detail-value">{{ $delivery->received_by ?? 'N/A' }}</div></div>
                @if($delivery->failed_delivery_reason)
                <div class="modal-detail-row"><div class="modal-detail-label">Failed Reason</div><div class="modal-detail-value text-danger">{{ $delivery->failed_delivery_reason }}</div></div>
                @endif
            </div>
        </div>

        <div class="card card-custom mt-3">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i> Update Delivery</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:0.8rem;">Update Tracking Number</label>
                        <form action="{{ route('admin.deliveries.update_tracking', $delivery->id) }}" method="POST" class="d-flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="tracking_no" class="form-control form-control-sm" value="{{ $delivery->tracking_no }}" placeholder="Tracking number" required>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i></button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:0.8rem;">Update Status</label>
                        <form action="{{ route('admin.deliveries.update_status', $delivery->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="d-flex gap-2">
                                <select name="delivery_status" class="form-select form-select-sm" required>
                                    @foreach(['assigned', 'on_the_way', 'delivered', 'failed'] as $status)
                                        <option value="{{ $status }}" {{ $delivery->delivery_status === $status ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', ucfirst($status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:0.8rem;">Failed Delivery Reason</label>
                        <form action="{{ route('admin.deliveries.update_failed_reason', $delivery->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="d-flex gap-2">
                                <input type="text" name="failed_delivery_reason" class="form-control form-control-sm"
                                       value="{{ $delivery->failed_delivery_reason }}" placeholder="Enter reason if delivery failed...">
                                <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-check-lg"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        @if($delivery->order->latitude && $delivery->order->longitude)
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-geo-alt me-2"></i> Customer Location</div>
            <div class="card-body p-2">
                <div id="adminDeliveryMap" style="height: 200px; border-radius: 10px;"></div>
                <div class="d-flex justify-content-between align-items-center mt-2 px-2 pb-1">
                    <small class="text-muted" style="font-size:0.72rem;">{{ number_format($delivery->order->latitude, 6) }}, {{ number_format($delivery->order->longitude, 6) }}</small>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->order->latitude }},{{ $delivery->order->longitude }}" target="_blank" class="btn btn-xs btn-outline-primary">
                        <i class="bi bi-sign-turn-right me-1"></i> Maps
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="card card-custom">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-geo-alt fs-3 d-block mb-2"></i>
                <small>No location data available.</small>
            </div>
        </div>
        @endif
    </div>
</div>

@if($delivery->order->latitude && $delivery->order->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('adminDeliveryMap', { zoomControl: true, attributionControl: false }).setView([{{ $delivery->order->latitude }}, {{ $delivery->order->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $delivery->order->latitude }}, {{ $delivery->order->longitude }}]).addTo(map).bindPopup('{!! e($delivery->order->delivery_address) !!}').openPopup();
    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endif
@endsection
