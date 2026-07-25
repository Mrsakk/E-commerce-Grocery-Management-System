@extends('layouts.admin')
@section('title', 'Manage Products')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-box text-success"></i> Products Catalog</h4>
        <p>Manage your grocery product inventory</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" onclick="showUploadModal('{{ route('admin.products.store') }}', 'Bulk Upload Products', 'product', 0)">
            <i class="bi bi-cloud-upload"></i> Upload
        </button>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg"></i> Add New Product
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">TOTAL PRODUCTS</div>
                        <div class="stat-number" style="color:var(--primary);">{{ $products->total() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50); color:var(--primary);"><i class="bi bi-box"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">ACTIVE ITEMS</div>
                        <div class="stat-number" style="color:var(--blue-500);">{{ $products->where('status', 'active')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--blue-50); color:var(--blue-500);"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">LOW STOCK</div>
                        <div class="stat-number" style="color:var(--accent);">{{ $products->filter(function($p) { return $p->inventory && $p->inventory->qty_in_stock <= $p->inventory->reorder_level; })->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--amber-50); color:var(--accent);"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">OUT OF STOCK</div>
                        <div class="stat-number" style="color:var(--red-500);">{{ $products->filter(function($p) { return $p->inventory && $p->inventory->qty_in_stock == 0; })->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--red-50); color:var(--red-500);"><i class="bi bi-x-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Grocery Products</div>
        <span class="text-muted small">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} items</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Details</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Price</th>
                        <th class="d-none d-sm-table-cell">Stock Status</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $product->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" 
                                         class="rounded-3 border" style="width: 44px; height: 44px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold d-block" style="color:var(--gray-900);">{{ $product->product_name }}</span>
                                        <span class="text-muted" style="font-size:0.72rem;">
                                            @if($product->brand) Brand: {{ $product->brand }} @endif
                                            @if($product->unit) ({{ $product->unit }}) @endif
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 fw-semibold" style="font-size:0.75rem; border-color:rgba(100,116,139,0.15) !important;">
                                    {{ $product->category->category_name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="color:var(--gray-900);">${{ number_format($product->price, 2) }}</span>
                                @if($product->unit)
                                    <small class="text-muted">/ {{ $product->unit }}</small>
                                @endif
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @if($product->inventory)
                                    @php 
                                        $qty = $product->inventory->qty_in_stock;
                                        $reorder = $product->inventory->reorder_level;
                                        $stockClass = $qty == 0 ? 'bg-danger' : ($qty <= $reorder ? 'bg-warning text-dark' : 'bg-success text-white');
                                    @endphp
                                    <div style="max-width: 110px;">
                                        <span class="badge {{ $stockClass }} py-1 px-2 fw-bold" style="font-size:0.7rem;">{{ $qty }} in stock</span>
                                        <div class="progress mt-1" style="height: 4px; border-radius: 10px;">
                                            @php $pct = min(($qty / max($reorder * 2, 10)) * 100, 100); @endphp
                                            <div class="progress-bar {{ $qty == 0 ? 'bg-danger' : ($qty <= $reorder ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $pct }}%;"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary bg-opacity-15 text-secondary">No Inventory</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge-status {{ $product->status == 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                    <i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-btns justify-content-end">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn-action btn-view" title="View Product">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action btn-edit" title="Edit Product">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="delete-product-{{ $product->id }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Delete Product"
                                            onclick="confirmDelete('delete-product-{{ $product->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-box d-block"></i>
                                    <h5>No Products Found</h5>
                                    <p>Start by adding your first grocery product.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
