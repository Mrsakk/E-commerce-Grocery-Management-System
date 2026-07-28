@extends('layouts.admin')
@section('title', 'Manage Orders')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-cart-check text-success"></i> Orders Control</h4>
        <p>Track and manage customer orders</p>
    </div>
</div>

@php 
    $currentStatus = request('status');
    $statuses = [
        '' => 'All Orders',
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled'
    ];
@endphp
<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($statuses as $key => $label)
        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => $key])) }}" 
           class="btn btn-xs rounded-pill border {{ ($currentStatus === $key || ($key === '' && !$currentStatus)) ? 'btn-success text-white' : 'bg-white text-muted' }}"
           style="font-size:0.78rem; font-weight:600; border-color: {{ ($currentStatus === $key || ($key === '' && !$currentStatus)) ? 'var(--primary)' : 'var(--gray-200)' }};">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="collapse {{ request()->anyFilled(['payment_method', 'date_from', 'date_to']) ? 'show' : '' }} mb-4" id="advancedFilters">
    <div class="card card-custom">
        <div class="card-body p-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="col-md-4">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                        <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Credit / Debit Card</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-success w-100 py-2"><i class="bi bi-search"></i> Apply</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100 py-2">Clear</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Orders List</div>
        <span class="text-muted small">Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th class="d-none d-sm-table-cell">Date</th>
                        <th class="d-none d-md-table-cell">Items</th>
                        <th>Total</th>
                        <th class="d-none d-md-table-cell">Payment</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $statusColors = [
                            'pending' => 'bg-warning text-dark',
                            'confirmed' => 'bg-info text-white',
                            'processing' => 'bg-primary text-white',
                            'packing' => 'bg-primary text-white',
                            'shipped' => 'bg-secondary text-white',
                            'delivered' => 'bg-success text-white',
                            'cancelled' => 'bg-danger text-white'
                        ]; 
                    @endphp
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $order->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:30px;height:30px;background:var(--primary-50);color:var(--primary);font-size:0.78rem;">
                                        {{ substr($order->customer?->user?->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color:var(--gray-900);">{{ $order->customer?->user?->name ?? 'Guest' }}</div>
                                        <small class="text-muted" style="font-size:0.7rem;">{{ $order->customer?->phone ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell"><span class="text-muted" style="font-size:0.82rem;">{{ $order->created_at->format('d/m/Y') }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold" style="font-size:0.75rem;">{{ $order->details->count() }} items</span></td>
                            <td><span class="fw-bold" style="color:var(--gray-900);">${{ number_format($order->total_amount, 2) }}</span></td>
                            <td class="d-none d-md-table-cell">
                                <div>
                                    <span class="badge-status bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} text-white">{{ ucfirst($order->payment_status) }}</span>
                                    <small class="text-muted d-block" style="font-size:0.7rem;">{{ strtoupper($order->payment_method) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $statusColors[$order->order_status] ?? 'bg-secondary text-white' }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action btn-view" title="View Order">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-cart-x d-block"></i>
                                    <h5>No Orders Found</h5>
                                    <p>No orders match the current filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
