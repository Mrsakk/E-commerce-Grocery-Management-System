@extends('layouts.admin')

@section('title', 'Stock Out')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Stock Out</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Remove stock for {{ $inventory->product?->product_name ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card-custom">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background: var(--primary-50);">
                    <div class="fw-bold text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Product</div>
                    <div class="fw-bold">{{ $inventory->product?->product_name ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: var(--gray-100);">
                    <div class="fw-bold text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Current Stock</div>
                    <div class="fw-bold fs-4">{{ $inventory->qty_in_stock }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: var(--gray-100);">
                    <div class="fw-bold text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Reorder Level</div>
                    <div class="fw-bold fs-4">{{ $inventory->reorder_level }}</div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.inventory.process_stock_out', $inventory->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="quantity" class="form-label fw-semibold">Quantity to Remove <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                       max="{{ $inventory->qty_in_stock }}" required
                       placeholder="Enter quantity" value="{{ old('quantity') }}">
                <div class="form-text">Maximum: {{ $inventory->qty_in_stock }} units</div>
            </div>
            <div class="mb-4">
                <label for="note" class="form-label fw-semibold">Note (Optional)</label>
                <textarea name="note" id="note" class="form-control" rows="3"
                          placeholder="e.g. Damaged goods, expired items, sample...">{{ old('note') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4">
                    <i class="bi bi-dash-circle me-1"></i> Remove Stock
                </button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
