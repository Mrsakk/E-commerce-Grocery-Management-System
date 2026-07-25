@extends('layouts.admin')
@section('title', 'Product Details')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-box text-success"></i> Product Details</h4>
        <p>{{ $product->product_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-5">
                        <img src="{{ $product->image_url }}" class="w-100 rounded" style="object-fit:cover;max-height:280px;" alt="{{ $product->product_name }}">
                    </div>
                    <div class="col-md-7">
                        <h5 class="fw-bold mb-1">{{ $product->product_name }}</h5>
                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }} text-white mb-3">{{ ucfirst($product->status) }}</span>
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" style="width:140px;">Category</td><td class="fw-semibold">{{ $product->category->category_name ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Price</td><td class="fw-bold text-success">${{ number_format($product->price, 2) }}</td></tr>
                            <tr><td class="text-muted">Unit</td><td>{{ $product->unit ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Brand</td><td>{{ $product->brand ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Expiry Date</td><td>{{ $product->expiry_date ? \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') : '—' }}</td></tr>
                        </table>
                    </div>
                </div>
                @if($product->description)
                    <hr>
                    <h6 class="fw-bold mb-2">Description</h6>
                    <p class="text-muted">{{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom mb-4">
            <div class="card-header"><h6 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>Stock Info</h6></div>
            <div class="card-body">
                @if($product->inventory)
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">In Stock</span><span class="fw-bold">{{ $product->inventory->qty_in_stock }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Reorder Level</span><span class="fw-bold">{{ $product->inventory->reorder_level }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Last Updated</span><span class="text-muted small">{{ $product->inventory->last_updated?->format('d/m/Y H:i') }}</span></div>
                @else
                    <p class="text-muted mb-0">No inventory record.</p>
                @endif
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header"><h6 class="fw-bold mb-0"><i class="bi bi-star text-warning me-2"></i>Reviews ({{ $product->reviews->count() }})</h6></div>
            <div class="card-body">
                @forelse($product->reviews->take(5) as $review)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold small">{{ $review->user->name ?? 'Guest' }}</span>
                            <span class="text-warning" style="font-size:0.75rem;">@for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>@endfor</span>
                        </div>
                        <p class="text-muted mb-0 small">{{ Str::limit($review->comment, 100) }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection