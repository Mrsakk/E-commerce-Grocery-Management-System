@extends('layouts.delivery')
@section('title', __('messages.deliveries') . ' #' . $delivery->id)
@section('content')

{{-- Header Row --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h4 class="fw-bold mb-0 text-dark">
        <i class="bi bi-truck text-primary me-2"></i>
        {{ __('messages.deliveries') }} #{{ $delivery->id }} — {{ __('messages.order') }} #{{ $delivery->order_id }}
    </h4>
    <a href="{{ route('delivery.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
    </a>
</div>

<div class="row g-4">
    {{-- Left Column: Order Items Table --}}
    <div class="col-lg-7">
        <div class="card card-custom shadow-sm bg-white">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-bold text-dark"><i class="bi bi-receipt me-2 text-primary"></i>{{ __('messages.order_items') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="padding: 14px 20px;">{{ __('messages.product') }}</th>
                                <th style="width: 100px;">{{ __('messages.quantity') }}</th>
                                <th style="width: 120px;">{{ __('messages.price') }}</th>
                                <th class="text-end" style="width: 130px; padding: 14px 20px;">{{ __('messages.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->order?->details ?? [] as $detail)
                                <tr>
                                    <td style="padding: 14px 20px;">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width:36px;height:36px;flex-shrink:0;">
                                                <i class="bi bi-box-seam text-primary" style="font-size:1rem;"></i>
                                            </div>
                                            <span class="text-dark fw-semibold">{{ $detail->product?->product_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1 fw-bold">{{ $detail->quantity }}</span></td>
                                    <td class="text-muted fw-bold">៛{{ number_format($detail->unit_price, 2) }}</td>
                                    <td class="text-end fw-extrabold text-dark" style="padding: 14px 20px;">៛{{ number_format($detail->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="3" class="text-end py-3" style="font-size: 0.95rem;">{{ __('messages.total') }}:</th>
                                <th class="text-end fw-extrabold text-primary py-3" style="font-size: 1.15rem; padding-right: 20px;">៛{{ number_format($delivery->order?->total_amount ?? 0, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Customer Info & Status Action --}}
    <div class="col-lg-5">
        {{-- Customer Info Card --}}
        <div class="card card-custom shadow-sm bg-white mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-bold text-dark"><i class="bi bi-person-bounding-box me-2 text-primary"></i>{{ __('messages.customer_info') }}</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4" style="background:#f8fafc; border: 1px solid var(--gray-200);">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:52px; height:52px; font-size:1.35rem; background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: 1.5px solid white;">
                        {{ substr($delivery->order?->customer?->user?->name ?? '?', 0, 1) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $delivery->order?->customer?->user?->name ?? 'N/A' }}</div>
                        <div class="text-muted small" style="font-size: 0.82rem;"><i class="bi bi-telephone-fill text-success me-1"></i>{{ $delivery->order?->customer?->user?->phone ?? 'N/A' }}</div>
                    </div>
                    @if($delivery->order?->customer?->user?->phone)
                        <a href="tel:{{ $delivery->order->customer->user->phone }}" class="btn btn-outline-success btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:38px;height:38px;padding:0;" title="Call Customer">
                            <i class="bi bi-telephone fs-5"></i>
                        </a>
                    @endif
                </div>
                
                {{-- Address detail --}}
                <div class="mb-3 d-flex gap-2">
                    <i class="bi bi-geo-alt-fill text-danger fs-5 mt-0.5"></i>
                    <div class="flex-grow-1">
                        <span class="fw-bold text-dark small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('messages.delivery_address') }}</span>
                        <div class="text-muted mt-1" style="font-size:0.92rem; line-height: 1.5;">{{ $delivery->order?->delivery_address ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Map for delivery location --}}
                @if($delivery->order?->latitude && $delivery->order?->longitude)
                <div class="mb-3">
                    <div id="deliveryStaffMap" style="height: 200px; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);"></div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" style="font-size: 0.72rem;">{{ number_format($delivery->order->latitude, 6) }}, {{ number_format($delivery->order->longitude, 6) }}</small>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->order->latitude }},{{ $delivery->order->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill fw-bold" style="font-size:0.78rem;">
                            <i class="bi bi-sign-turn-right me-1"></i>{{ __('messages.get_directions') }}
                        </a>
                    </div>
                </div>
                @endif
                
                {{-- Payment status detail --}}
                <div class="mb-2 d-flex align-items-center justify-content-between pt-3 border-top">
                    <span class="fw-bold text-dark small" style="letter-spacing: 0.5px; text-transform: uppercase;"><i class="bi bi-wallet2 text-primary me-1.5"></i>{{ __('messages.payment') }}</span>
                    <div>
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold" style="font-size: 0.75rem;">{{ $delivery->order?->payment_method ?? 'N/A' }}</span>
                        <span class="badge-status {{ ($delivery->order?->payment_status ?? '') == 'paid' ? 'status-delivered' : 'status-on_the_way' }} ms-1">
                            {{ Lang::has('messages.' . ($delivery->order?->payment_status ?? '')) ? __('messages.' . $delivery->order->payment_status) : ($delivery->order?->payment_status ?? 'N/A') }}
                        </span>
                    </div>
                </div>

                @if($delivery->delivery_status === 'failed')
                    <div class="mb-0 p-3 bg-danger bg-opacity-10 text-danger rounded-4 mt-3 border border-danger border-opacity-10 d-flex gap-2.5">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-danger-dark" style="font-size:0.85rem;">{{ __('messages.failed_delivery_reason') }}</h6>
                            <div class="small text-danger opacity-85" style="line-height:1.4;">{{ $delivery->failed_delivery_reason ?? 'No reason provided' }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status update form card --}}
        <div class="card card-custom shadow-sm bg-white">
            <div class="card-header bg-white border-bottom py-3">
                <span class="fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('messages.update_delivery_status') }}</span>
            </div>
            <div class="card-body">
                @if(in_array($delivery->delivery_status, ['delivered', 'failed']))
                    <div class="text-center py-4">
                        @if($delivery->delivery_status === 'delivered')
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:72px; height:72px;">
                                <i class="bi bi-check-circle-fill fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-success mb-1">{{ Lang::has('messages.' . $delivery->delivery_status) ? __('messages.' . $delivery->delivery_status) : ucfirst($delivery->delivery_status) }}</h5>
                            @if($delivery->received_by)
                                <p class="text-muted small mb-0">{{ __('messages.received_by') }}: <strong>{{ $delivery->received_by }}</strong></p>
                            @endif
                        @else
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center mx-auto mb-3" style="width:72px; height:72px;">
                                <i class="bi bi-x-circle-fill fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-danger mb-1">{{ Lang::has('messages.' . $delivery->delivery_status) ? __('messages.' . $delivery->delivery_status) : ucfirst($delivery->delivery_status) }}</h5>
                            @if($delivery->failed_delivery_reason)
                                <p class="text-muted small mb-0">{{ __('messages.failed_delivery_reason') }}: <strong>{{ $delivery->failed_delivery_reason }}</strong></p>
                            @endif
                        @endif
                        <p class="text-muted small mt-3 mb-0" style="font-size:0.75rem;"><i class="bi bi-info-circle me-1"></i>Completed or failed deliveries cannot be updated further.</p>
                    </div>
                @else
                    <form action="{{ route('delivery.update_status', $delivery->id) }}" method="POST" x-data="{ status: '{{ $delivery->delivery_status }}' }">
                        @csrf @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <select name="delivery_status" class="form-select border-2 fw-semibold" x-model="status" style="border-radius: 8px;">
                                <option value="assigned">{{ __('messages.assigned') }}</option>
                                <option value="on_the_way">{{ __('messages.on_the_way') }}</option>
                                <option value="delivered">{{ __('messages.delivered') }}</option>
                                <option value="failed">{{ __('messages.failed') }}</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" x-show="status === 'delivered'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <label class="form-label">{{ __('messages.received_by') }} <small class="text-muted">({{ __('messages.optional') }})</small></label>
                            <input type="text" name="received_by" class="form-control border-2" value="{{ $delivery->received_by }}" placeholder="e.g. Spouse, Guard, Self">
                        </div>
                        
                        <div class="mb-3" x-show="status === 'failed'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <label class="form-label text-danger">{{ __('messages.failed_delivery_reason') }} <small class="text-muted">({{ __('messages.required_field') }})</small></label>
                            <textarea name="failed_delivery_reason" class="form-control border-2" rows="3" placeholder="Explain why the delivery failed (e.g. client did not pick up phone, wrong location)">{{ $delivery->failed_delivery_reason }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none;">
                            <i class="bi bi-check-circle-fill"></i> {{ __('messages.update_status') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if($delivery->order?->latitude && $delivery->order?->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $delivery->order->latitude }};
    var lng = {{ $delivery->order->longitude }};
    var map = L.map('deliveryStaffMap', { zoomControl: true, attributionControl: false }).setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup('{!! e($delivery->order->delivery_address) !!}').openPopup();
    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endif
@endsection
