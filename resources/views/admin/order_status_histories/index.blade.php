@extends('layouts.admin')
@section('title', 'Order #' . $order->id . ' Status History')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-clock-history text-primary"></i> Order #{{ $order->id }} - Status Timeline</h4>
        <p>Track all status changes for this order</p>
    </div>
    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Order
    </a>
</div>

<div class="card card-custom">
    <div class="card-body">
        @if($histories->count() > 0)
            <div class="timeline">
                @foreach($histories as $history)
                    <div class="d-flex mb-4">
                        <div class="me-3 text-center" style="width: 40px;">
                            <div class="rounded-circle bg-{{ $history->new_status == 'delivered' ? 'success' : ($history->new_status == 'cancelled' ? 'danger' : 'primary') }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-{{ $history->new_status == 'delivered' ? 'check-lg' : ($history->new_status == 'cancelled' ? 'x-lg' : 'arrow-right') }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <strong style="color:var(--gray-900);">{{ ucfirst($history->new_status) }}</strong>
                            <span class="text-muted small ms-2">{{ $history->created_at->format('d/m/Y h:i A') }}</span>
                            <br>
                            <small class="text-muted">Changed by: {{ $history->changedBy->name ?? 'N/A' }}</small>
                            <br>
                            <small class="text-muted">From: {{ ucfirst($history->old_status) }} &rarr; To: {{ ucfirst($history->new_status) }}</small>
                            @if($history->cancel_reason)
                                <br><small class="text-danger">Reason: {{ $history->cancel_reason }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-clock-history d-block"></i>
                <h5>No History Recorded</h5>
                <p>Status changes will appear here as they happen.</p>
            </div>
        @endif
    </div>
</div>
<style>
.timeline { position: relative; padding-left: 20px; }
.timeline:before { content: ''; position: absolute; left: 39px; top: 0; bottom: 0; width: 2px; background: var(--gray-200); }
</style>
@endsection
