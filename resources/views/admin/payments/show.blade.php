@extends('layouts.admin')
@section('title', 'Payment TXN-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT))
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-credit-card text-primary"></i> Payment TXN-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</h4>
        <p>Order #{{ $payment->order_id }} &mdash; {{ $payment->order?->customer?->user?->name ?? 'Guest' }}</p>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="form-card" style="max-width:640px;">
    <div class="modal-detail-row"><div class="modal-detail-label">Customer</div><div class="modal-detail-value">{{ $payment->order?->customer?->user?->name ?? 'N/A' }}</div></div>
    <div class="modal-detail-row"><div class="modal-detail-label">Amount</div><div class="modal-detail-value fw-bold" style="color:var(--primary);font-size:1.1rem;">${{ number_format($payment->amount, 2) }}</div></div>
    <div class="modal-detail-row"><div class="modal-detail-label">Method</div><div class="modal-detail-value">{{ strtoupper($payment->payment_method) }}</div></div>
    <div class="modal-detail-row"><div class="modal-detail-label">Status</div><div class="modal-detail-value"><span class="badge-status bg-{{ $payment->payment_status == 'paid' ? 'success' : 'warning' }} text-white">{{ ucfirst($payment->payment_status) }}</span></div></div>
    <div class="modal-detail-row"><div class="modal-detail-label">Transaction Ref</div><div class="modal-detail-value">{{ $payment->transaction_ref ?? 'N/A' }}</div></div>
    <div class="modal-detail-row"><div class="modal-detail-label">Payment Date</div><div class="modal-detail-value">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'Pending' }}</div></div>
    
    @if($payment->payment_status != 'paid')
        <div class="mt-4 d-flex gap-2">
            <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Confirm Payment</button>
            </form>

            @if($payment->payment_method === 'cod')
            <form action="{{ route('admin.payments.mark_cod_paid', $payment->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary" onclick="return confirm('Mark this COD payment as paid?')">
                    <i class="bi bi-cash me-1"></i> Mark COD as Paid
                </button>
            </form>
            @endif

            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-lg me-1"></i> Reject
            </button>
        </div>
    @endif

    @if($payment->slip_image)
    <div class="mt-4">
        <div class="info-label mb-2">Payment Slip</div>
        @if(str_starts_with($payment->slip_image, 'data:'))
            <img src="{{ $payment->slip_image }}" alt="Payment Slip"
                 class="img-fluid rounded" style="max-height: 300px; border: 1px solid var(--gray-200);">
        @else
            <img src="{{ asset('images/slips/' . $payment->slip_image) }}" alt="Payment Slip"
                 class="img-fluid rounded" style="max-height: 300px; border: 1px solid var(--gray-200);">
        @endif
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--radius-md);">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Reject Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required
                                  placeholder="Enter reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
