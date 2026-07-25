@extends('layouts.admin')
@section('title', 'Assign Delivery')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-truck text-success"></i> Assign Delivery</h4>
        <p>Assign a delivery agent to an order</p>
    </div>
    <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.deliveries.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Order <span class="text-danger">*</span></label>
                <select name="order_id" class="form-select @error('order_id') is-invalid @enderror" required>
                    <option value="">Select Order</option>
                    @foreach($ordersWithoutDelivery as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            #{{ $order->id }} - {{ $order->customer?->user?->name ?? 'Guest' }} (៛{{ number_format($order->total_amount, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('order_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Delivery Agent <span class="text-danger">*</span></label>
                <select name="delivery_staff_id" class="form-select @error('delivery_staff_id') is-invalid @enderror" required>
                    <option value="">Select Agent</option>
                    @foreach($deliveryStaff as $staff)
                        <option value="{{ $staff->id }}" {{ old('delivery_staff_id') == $staff->id ? 'selected' : '' }}>
                            {{ $staff->name }} ({{ $staff->phone }})
                        </option>
                    @endforeach
                </select>
                @error('delivery_staff_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Tracking Number</label>
                <input type="text" name="tracking_no" class="form-control @error('tracking_no') is-invalid @enderror" value="{{ old('tracking_no') }}" placeholder="Optional">
                @error('tracking_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Assign Delivery</button>
            <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
