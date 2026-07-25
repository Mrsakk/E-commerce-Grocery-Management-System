@extends('layouts.admin')
@section('title', 'Deliveries')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-truck text-success"></i> Deliveries & Logistics</h4>
        <p>Track shipments and delivery status</p>
    </div>
    <a href="{{ route('admin.deliveries.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Delivery
    </a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.deliveries.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by customer name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['assigned', 'on_the_way', 'delivered', 'failed'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">TOTAL SHIPMENTS</div>
                        <div class="stat-number" style="color:var(--primary);">{{ $deliveries->total() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50);color:var(--primary);"><i class="bi bi-truck"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">IN TRANSIT</div>
                        <div class="stat-number" style="color:var(--blue-500);">{{ $deliveries->whereIn('delivery_status', ['assigned', 'on_the_way'])->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--blue-50);color:var(--blue-500);"><i class="bi bi-arrow-repeat"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">DELIVERED</div>
                        <div class="stat-number" style="color:var(--primary);">{{ $deliveries->where('delivery_status', 'delivered')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50);color:var(--primary);"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Fulfillment Log</div>
        <span class="text-muted small">Showing {{ $deliveries->firstItem() ?? 0 }}-{{ $deliveries->lastItem() ?? 0 }} of {{ $deliveries->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Delivery #</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Agent</th>
                        <th>Tracking</th>
                        <th>Status</th>
                        <th>Date</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                        @php 
                            $status = strtolower($delivery->delivery_status);
                            $statusColors = [
                                'pending' => 'bg-secondary text-white',
                                'assigned' => 'bg-warning text-dark',
                                'on_the_way' => 'bg-info text-white',
                                'delivered' => 'bg-success text-white',
                                'failed' => 'bg-danger text-white'
                            ];
                        @endphp
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">SHP-{{ str_pad($delivery->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="fw-semibold" style="color:var(--primary);">#{{ $delivery->order_id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:30px;height:30px;background:var(--primary-50);color:var(--primary);font-size:0.78rem;">
                                        {{ substr($delivery->order->customer->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color:var(--gray-900);">{{ $delivery->order->customer->user->name ?? 'Guest' }}</div>
                                        <small class="text-muted" style="font-size:0.7rem;">{{ $delivery->order->customer->phone ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($delivery->staff)
                                    <span class="fw-semibold">{{ $delivery->staff->name }}</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Unassigned</span>
                                @endif
                            </td>
                            <td><span class="font-monospace fw-semibold" style="font-size:0.78rem;">{{ $delivery->tracking_no ?? '-' }}</span></td>
                            <td>
                                <span class="badge-status {{ $statusColors[$status] ?? 'bg-secondary text-white' }}">
                                    {{ str_replace('_', ' ', ucfirst($delivery->delivery_status)) }}
                                </span>
                            </td>
                            <td><span class="text-muted" style="font-size:0.8rem;">{{ $delivery->delivery_date ?? 'Pending' }}</span></td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-truck d-block"></i>
                                    <h5>No Deliveries Found</h5>
                                    <p>Shipment logs will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($deliveries->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $deliveries->links() }}</div>
    @endif
</div>
@endsection
