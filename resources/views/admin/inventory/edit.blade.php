@extends('layouts.admin')
@section('title', 'Update Stock')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-boxes text-warning"></i> Update Stock</h4>
        <p>{{ $inventory->product->product_name ?? 'N/A' }}</p>
    </div>
</div>
<div class="form-card" style="max-width:500px;">
    <form action="{{ route('admin.inventory.update', $inventory->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Quantity in Stock <span class="text-danger">*</span></label>
            <input type="number" name="qty_in_stock" class="form-control" value="{{ old('qty_in_stock', $inventory->qty_in_stock) }}" required min="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
            <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', $inventory->reorder_level) }}" required min="0">
            <small class="text-muted">When stock reaches this level, it will be flagged as low stock.</small>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Update Stock</button>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
