@extends('layouts.admin')
@section('title', 'Low Stock Products')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-exclamation-triangle text-danger"></i> Low Stock Products</h4>
        <p>Products that need restocking soon</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Low Stock Alert</div>
        <span class="text-muted small">{{ $inventories->count() }} products need attention</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Stock Qty</th>
                        <th class="d-none-mobile">Reorder Level</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inv)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $inv->id }}</span></td>
                            <td><span class="fw-semibold">{{ $inv->product->product_name ?? 'N/A' }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size:0.75rem;">{{ $inv->product->category->category_name ?? 'N/A' }}</span></td>
                            <td><span class="badge bg-danger rounded-pill px-2 py-1 fw-bold">{{ $inv->qty_in_stock }}</span></td>
                            <td class="d-none-mobile"><span class="fw-semibold" style="color:var(--gray-600);">{{ $inv->reorder_level }}</span></td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-check-circle text-success d-block"></i>
                                    <h5>All Products Well Stocked</h5>
                                    <p>No products are currently below the reorder level.</p>
                                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-success btn-sm mt-2">View Inventory</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($inventories, 'links') && $inventories->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $inventories->links() }}</div>
    @endif
</div>
@endsection
