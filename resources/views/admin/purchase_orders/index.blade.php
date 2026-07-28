@extends('layouts.admin')
@section('title', 'Purchase Orders')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-cart-plus text-success"></i> Purchase Orders</h4>
        <p>Manage supplier purchase orders and restocking</p>
    </div>
    <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg"></i> New Purchase Order
    </a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-success mt-3"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Purchase Orders</div>
        <span class="text-muted small">{{ $purchaseOrders->count() }} orders</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="d-none-mobile">#</th>
                        <th>Order No</th>
                        <th>Supplier</th>
                        <th class="d-none d-md-table-cell">Items</th>
                        <th>Total</th>
                        <th class="d-none-mobile">Status</th>
                        <th class="d-none d-md-table-cell">Ordered By</th>
                        <th class="d-none-mobile">Date</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">#{{ $po->id }}</span></td>
                            <td><span class="fw-semibold">{{ $po->order_number }}</span></td>
                            <td>{{ $po->supplier->supplier_name ?? 'N/A' }}</td>
                            <td class="d-none d-md-table-cell"><span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold" style="font-size:0.75rem;">{{ $po->items->count() }} items</span></td>
                            <td><span class="fw-bold" style="color:var(--gray-900);">${{ number_format($po->total_amount, 2) }}</span></td>
                            <td class="d-none-mobile">
                                <span class="badge-status bg-{{ $po->status == 'received' ? 'success' : ($po->status == 'ordered' ? 'info' : ($po->status == 'partial' ? 'warning' : ($po->status == 'cancelled' ? 'danger' : 'secondary'))) }} text-white">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ $po->orderedBy->name ?? 'N/A' }}</span></td>
                            <td class="d-none-mobile"><span class="text-muted" style="font-size:0.82rem;">{{ $po->created_at->format('d/m/Y') }}</span></td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-cart-plus d-block"></i>
                                    <h5>No Purchase Orders</h5>
                                    <p>Create your first purchase order to restock inventory.</p>
                                    <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> New Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($purchaseOrders, 'links') && $purchaseOrders->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $purchaseOrders->links() }}</div>
    @endif
</div>
@endsection
