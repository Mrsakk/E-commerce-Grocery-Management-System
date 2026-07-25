@extends('layouts.admin')
@section('title', 'Manage Inventory')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-boxes text-warning"></i> Inventory Levels</h4>
        <p>Monitor and adjust stock levels for all products</p>
    </div>
    <a href="{{ route('admin.inventory.low_stock') }}" class="btn btn-warning btn-sm text-white">
        <i class="bi bi-exclamation-triangle"></i> Low Stock Alerts
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">MONITORED ITEMS</div>
                        <div class="stat-number" style="color:var(--primary);">{{ $inventories->total() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50);color:var(--primary);"><i class="bi bi-boxes"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">LOW STOCK</div>
                        <div class="stat-number" style="color:var(--red-500);">{{ $inventories->filter(function($i) { return $i->qty_in_stock <= $i->reorder_level; })->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--red-50);color:var(--red-500);"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">HEALTHY STOCK</div>
                        @php $lowCount = $inventories->filter(function($i) { return $i->qty_in_stock <= $i->reorder_level; })->count(); $totalCount = $inventories->count(); $stability = $totalCount > 0 ? round((($totalCount - $lowCount) / $totalCount) * 100) : 100; @endphp
                        <div class="stat-number" style="color:var(--blue-500);">{{ $stability }}%</div>
                    </div>
                    <div class="stat-icon" style="background:var(--blue-50);color:var(--blue-500);"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Stock Status Monitor</div>
        <span class="text-muted small">Showing {{ $inventories->firstItem() ?? 0 }}-{{ $inventories->lastItem() ?? 0 }} of {{ $inventories->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Reorder</th>
                        <th>Condition</th>
                        <th>Last Update</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inv)
                        @php 
                            $qty = $inv->qty_in_stock;
                            $reorder = $inv->reorder_level;
                            $isLow = $qty <= $reorder;
                        @endphp
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $inv->id }}</span></td>
                            <td>
                                <span class="fw-semibold">{{ $inv->product->product_name ?? 'Deleted' }}</span>
                                @if($inv->product->brand ?? false)
                                    <small class="text-muted d-block" style="font-size:0.7rem;">{{ $inv->product->brand }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 fw-semibold" style="font-size:0.75rem;">{{ $inv->product->category->category_name ?? 'N/A' }}</span></td>
                            <td>
                                <div style="max-width:100px;">
                                    <span class="fw-bold" style="color:var(--gray-900);">{{ $qty }}</span>
                                    @if($inv->product->unit ?? false) <small class="text-muted">{{ $inv->product->unit }}</small> @endif
                                    <div class="progress mt-1" style="height:4px;border-radius:10px;">
                                        @php $pct = min(($qty / max($reorder * 2, 10)) * 100, 100); @endphp
                                        <div class="progress-bar {{ $qty == 0 ? 'bg-danger' : ($isLow ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-semibold" style="color:var(--gray-600);">{{ $reorder }}</span></td>
                            <td>
                                @if($qty == 0)
                                    <span class="badge bg-danger rounded-pill px-2 py-1">Out of Stock</span>
                                @elseif($isLow)
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Low Stock</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-2 py-1">Healthy</span>
                                @endif
                            </td>
                            <td><span class="text-muted" style="font-size:0.8rem;">{{ $inv->last_updated ? $inv->last_updated->format('d/m/Y') : 'N/A' }}</span></td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.inventory.stock_in', $inv->id) }}" class="btn-action btn-view" title="Stock In">
                                        <i class="bi bi-plus-circle"></i>
                                    </a>
                                    <a href="{{ route('admin.inventory.stock_out', $inv->id) }}" class="btn-action btn-delete" title="Stock Out">
                                        <i class="bi bi-dash-circle"></i>
                                    </a>
                                    <a href="{{ route('admin.inventory.edit', $inv->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-boxes d-block"></i>
                                    <h5>No Inventory Items</h5>
                                    <p>Inventory will appear once products are created.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($inventories->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $inventories->links() }}</div>
    @endif
</div>
@endsection
