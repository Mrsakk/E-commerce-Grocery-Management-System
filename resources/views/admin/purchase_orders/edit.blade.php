@extends('layouts.admin')
@section('title', 'Edit Purchase Order')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-pencil text-primary"></i> Edit Purchase Order</h4>
        <p>{{ $purchaseOrder->order_number }}</p>
    </div>
    <a href="{{ route('admin.purchase-orders.show', $purchaseOrder->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.purchase-orders.update', $purchaseOrder->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select rounded-3" disabled>
                    <option value="{{ $purchaseOrder->status }}">{{ ucfirst($purchaseOrder->status) }}</option>
                </select>
                <small class="text-muted">Status is managed via the Update Status action.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Note</label>
                <textarea name="note" class="form-control rounded-3" rows="3">{{ old('note', $purchaseOrder->note) }}</textarea>
            </div>
            <button type="submit" class="btn btn-success fw-bold rounded-pill px-4"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
        </form>
    </div>
</div>
@endsection