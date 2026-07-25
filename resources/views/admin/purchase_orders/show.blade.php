@extends('layouts.admin')
@section('title', 'PO: ' . $purchaseOrder->order_number)
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-cart-plus text-success"></i> {{ $purchaseOrder->order_number }}</h4>
        <p>Purchase order from {{ $purchaseOrder->supplier->supplier_name ?? 'N/A' }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($purchaseOrder->status, ['pending', 'ordered', 'partial']))
            <span class="badge-status bg-warning text-dark" style="font-size:0.82rem;"><i class="bi bi-clock me-1"></i> Awaiting Receipt</span>
        @elseif($purchaseOrder->status == 'received')
            <span class="badge-status bg-success text-white" style="font-size:0.82rem;"><i class="bi bi-check-circle me-1"></i> Fully Received</span>
        @endif
        <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i> Items</div>
            <div class="card-body p-0">
                <form action="{{ route('admin.purchase_orders.receive', $purchaseOrder->id) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead><tr><th>Product</th><th>Ordered</th><th>Unit Cost</th><th>Subtotal</th><th>Received</th>@if(in_array($purchaseOrder->status, ['pending', 'ordered', 'partial']))<th>Receive Qty</th>@endif</tr></thead>
                            <tbody>
                                @foreach($purchaseOrder->items as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->product->product_name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->unit_cost, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                                        <td>{{ $item->received_qty }}</td>
                                        @if(in_array($purchaseOrder->status, ['pending', 'ordered', 'partial']))
                                            <td style="width:100px">
                                                <input type="number" name="items[{{ $item->id }}][received_qty]" class="form-control form-control-sm"
                                                    value="{{ $item->received_qty }}" min="0" max="{{ $item->quantity }}">
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr><th colspan="3" class="text-end">Total:</th><th class="fw-bold" style="color:var(--primary);">${{ number_format($purchaseOrder->total_amount, 2) }}</th><th></th></tr>
                            </tfoot>
                        </table>
                    </div>
                    @if(in_array($purchaseOrder->status, ['pending', 'ordered', 'partial']))
                        <div class="px-3 pb-3">
                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i> Receive Stock</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i> Details</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Supplier</div><div class="modal-detail-value">{{ $purchaseOrder->supplier->supplier_name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Status</div><div class="modal-detail-value"><span class="badge-status bg-{{ $purchaseOrder->status == 'received' ? 'success' : ($purchaseOrder->status == 'ordered' ? 'info' : 'warning') }} text-white">{{ ucfirst($purchaseOrder->status) }}</span></div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Ordered By</div><div class="modal-detail-value">{{ $purchaseOrder->orderedBy->name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Received By</div><div class="modal-detail-value">{{ $purchaseOrder->receivedBy->name ?? 'Not received' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Received At</div><div class="modal-detail-value">{{ $purchaseOrder->received_at ? $purchaseOrder->received_at->format('d/m/Y H:i') : '-' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Note</div><div class="modal-detail-value">{{ $purchaseOrder->note ?? '-' }}</div></div>
            </div>
        </div>
        @if(in_array($purchaseOrder->status, ['pending']))
            <div class="card card-custom">
                <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i> Actions</div>
                <div class="card-body">
                    <form action="{{ route('admin.purchase_orders.update_status', $purchaseOrder->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select mb-2">
                            <option value="ordered">Mark as Ordered</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i> Update Status</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
