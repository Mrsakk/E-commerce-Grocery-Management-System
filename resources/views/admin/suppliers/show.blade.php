@extends('layouts.admin')
@section('title', 'Supplier: ' . $supplier->supplier_name)
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-building text-primary"></i> {{ $supplier->supplier_name }}</h4>
        <p>Supplier details and purchase history</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-person-lines-fill me-2"></i> Contact Info</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Contact</div><div class="modal-detail-value">{{ $supplier->contact_person ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Phone</div><div class="modal-detail-value">{{ $supplier->phone ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Email</div><div class="modal-detail-value">{{ $supplier->email ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Address</div><div class="modal-detail-value">{{ $supplier->address ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Status</div><div class="modal-detail-value"><span class="badge-status bg-{{ $supplier->status == 'active' ? 'success' : 'secondary' }} text-white">{{ ucfirst($supplier->status) }}</span></div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i> Products ({{ $supplier->products->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-custom">
                    <thead><tr><th>Product</th><th>Supply Price</th><th>Lead Time</th></tr></thead>
                    <tbody>
                        @forelse($supplier->products as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->product_name }}</td>
                                <td>${{ number_format($product->pivot->supply_price, 2) ?? 'N/A' }}</td>
                                <td>{{ $product->pivot->lead_time_days ?? 'N/A' }} days</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No products linked.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-cart-plus me-2"></i> Purchase Orders ({{ $supplier->purchaseOrders->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-custom">
                    <thead><tr><th>#</th><th>Order No</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($supplier->purchaseOrders as $po)
                            <tr>
                                <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $po->id }}</span></td>
                                <td><span class="fw-semibold">{{ $po->order_number }}</span></td>
                                <td class="fw-bold">${{ number_format($po->total_amount, 2) }}</td>
                                <td><span class="badge-status bg-{{ $po->status == 'received' ? 'success' : ($po->status == 'ordered' ? 'info' : 'warning') }} text-white">{{ ucfirst($po->status) }}</span></td>
                                <td><span class="text-muted">{{ $po->created_at->format('d/m/Y') }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No purchase orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
