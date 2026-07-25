@extends('layouts.admin')
@section('title', $user->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person text-primary"></i> {{ $user->name }}</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">User profile and activity</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom">
            <div class="card-body text-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width:64px;height:64px;background:var(--primary-50);color:var(--primary);font-size:1.5rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                @php $roleColors = ['admin' => 'danger', 'staff' => 'warning', 'delivery' => 'info', 'customer' => 'success']; @endphp
                <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }} rounded-pill px-3 py-1">{{ ucfirst($user->role) }}</span>
                <span class="badge bg-{{ ($user->status ?? 'active') === 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1 ms-1">{{ ucfirst($user->status ?? 'active') }}</span>

                <div class="mt-4 text-start">
                    <div class="modal-detail-row"><div class="modal-detail-label">Phone</div><div class="modal-detail-value">{{ $user->phone ?? 'N/A' }}</div></div>
                    <div class="modal-detail-row"><div class="modal-detail-label">Joined</div><div class="modal-detail-value">{{ $user->created_at->format('M d, Y') }}</div></div>
                    <div class="modal-detail-row"><div class="modal-detail-label">Last Login</div><div class="modal-detail-value">{{ $user->last_login_at ?? 'N/A' }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @if($user->customer)
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-cart-check me-2"></i> Customer Orders ({{ $user->customer->orders->count() }})</div>
            <div class="card-body p-0">
                @if($user->customer->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead><tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($user->customer->orders->take(10) as $order)
                            <tr>
                                <td class="fw-semibold">#{{ $order->id }}</td>
                                <td class="text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'warning') }} rounded-pill">{{ ucfirst($order->order_status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">No orders yet.</div>
                @endif
            </div>
        </div>
        @endif

        @if($user->role === 'delivery' && $user->deliveries->count() > 0)
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-truck me-2"></i> Delivery History ({{ $user->deliveries->count() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead><tr><th>Delivery #</th><th>Order</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                            @foreach($user->deliveries->take(10) as $delivery)
                            <tr>
                                <td class="fw-semibold">SHP-{{ str_pad($delivery->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>#{{ $delivery->order_id }}</td>
                                <td><span class="badge bg-{{ $delivery->delivery_status === 'delivered' ? 'success' : 'warning' }} rounded-pill">{{ str_replace('_', ' ', ucfirst($delivery->delivery_status)) }}</span></td>
                                <td class="text-muted">{{ $delivery->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
