@extends('layouts.delivery')
@section('title', __('messages.my_deliveries'))
@section('content')

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-start border-4 border-info">
            <div class="stat-body">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-number">{{ $assignedDeliveries }}</div>
                <div class="stat-label text-info">{{ __('messages.assigned') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-start border-4 border-warning">
            <div class="stat-body">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-number">{{ $onTheWay }}</div>
                <div class="stat-label text-warning">{{ __('messages.on_the_way') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-start border-4 border-success">
            <div class="stat-body">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-number">{{ $delivered }}</div>
                <div class="stat-label text-success">{{ __('messages.delivered') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Deliveries Table Card --}}
<div class="table-container shadow-sm bg-white">
    <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-list-task text-primary me-2"></i>{{ __('messages.my_deliveries') }}</h5>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 120px;">{{ __('messages.order') }}</th>
                    <th>{{ __('messages.customer') }}</th>
                    <th>{{ __('messages.address') }}</th>
                    <th>{{ __('messages.tracking') }}</th>
                    <th style="width: 150px;">{{ __('messages.status') }}</th>
                    <th style="width: 130px;">{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                    <tr>
                        <td class="text-muted fw-bold">#{{ $delivery->id }}</td>
                        <td><span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold">#{{ $delivery->order_id }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $delivery->order?->customer?->user?->name ?? 'N/A' }}</div>
                            <div class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-telephone-fill text-success me-1"></i>{{ $delivery->order?->customer?->user?->phone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="text-dark fw-medium">{{ Str::limit($delivery->order?->delivery_address ?? '', 45) }}</div>
                        </td>
                        <td>
                            <code class="text-dark bg-light px-2 py-1 rounded small fw-bold" style="font-size:0.8rem;">{{ $delivery->tracking_no ?? 'N/A' }}</code>
                        </td>
                        <td>
                            <span class="badge-status status-{{ $delivery->delivery_status }}">
                                {{ __('messages.' . $delivery->delivery_status) ?? str_replace('_', ' ', ucfirst($delivery->delivery_status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('delivery.show', $delivery->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                <i class="bi bi-eye-fill me-1"></i> {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-truck fs-1 d-block mb-3 text-muted opacity-40"></i>
                            <h6 class="fw-bold text-muted mb-1">{{ __('messages.no_deliveries') }}</h6>
                            <p class="small text-muted mb-0">Assigned dispatch requests will show up in this space.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($deliveries->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>
@endsection
