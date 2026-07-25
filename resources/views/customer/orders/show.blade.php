@extends('layouts.customer')
@section('title', __('messages.order') . ' #' . $order->id)
@section('content')

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">{{ __('messages.my_orders') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.order') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt text-success me-2"></i>{{ __('messages.order_tracker') }}</h4>
        <div>
            @php
                $statusColors = ['pending' => 'warning', 'confirmed' => 'info', 'processing' => 'primary', 'shipped' => 'secondary', 'delivered' => 'success', 'cancelled' => 'danger'];
            @endphp
            <span class="badge text-uppercase bg-{{ $statusColors[$order->order_status] ?? 'secondary' }} text-white fs-6 px-3 py-2 fw-bold" style="border-radius:6px;">
                {{ Lang::has('messages.' . $order->order_status) ? __('messages.' . $order->order_status) : $order->order_status }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left details column --}}
        <div class="col-lg-8">
            {{-- Order Items Card --}}
            <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                    <i class="bi bi-box-seam text-success me-2"></i>{{ __('messages.order_summary') }}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead class="table-light text-muted small uppercase">
                                <tr>
                                    <th style="padding: 16px;">{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th class="text-end pe-4">{{ __('messages.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td style="padding: 16px;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width:40px; height:40px; border-radius:var(--radius-sm); overflow:hidden; border: 1px solid var(--gray-200); display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                                                    <img src="{{ $detail->product->image_url }}" alt="{{ $detail->product->product_name }}" class="w-100 h-100 object-fit-cover">
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $detail->product->product_name }}</span>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">${{ number_format($detail->unit_price, 2) }}</td>
                                        <td class="fw-bold text-dark">{{ $detail->quantity }}</td>
                                        <td class="text-end pe-4 fw-extrabold text-success">${{ number_format($detail->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light border-top">
                                    <th colspan="3" class="text-end py-3">{{ __('messages.grand_total') }}</th>
                                    <th class="text-end pe-4 py-3 fs-5 fw-extrabold text-success">${{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Delivery Progress Timeline Card --}}
            @if($order->delivery)
                <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                    <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                        <i class="bi bi-truck text-success me-2"></i>{{ __('messages.logistics_progress') }}
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4 bg-light p-3 rounded-3 text-dark small border">
                            <div class="col-6 col-md-3"><span class="text-muted fw-bold d-block text-uppercase" style="font-size:0.68rem;">{{ __('messages.tracking_number') }}</span> <span class="fw-semibold">{{ $order->delivery->tracking_no ?? 'N/A' }}</span></div>
                            <div class="col-6 col-md-3"><span class="text-muted fw-bold d-block text-uppercase" style="font-size:0.68rem;">{{ __('messages.delivery_agent') }}</span> <span class="fw-semibold">{{ $order->delivery->staff->name ?? 'N/A' }}</span></div>
                            <div class="col-6 col-md-3"><span class="text-muted fw-bold d-block text-uppercase" style="font-size:0.68rem;">{{ __('messages.agent_contact') }}</span> <span class="fw-semibold">{{ $order->delivery->staff->phone ?? 'N/A' }}</span></div>
                            @if($order->delivery->received_by)
                                <div class="col-6 col-md-3"><span class="text-muted fw-bold d-block text-uppercase" style="font-size:0.68rem;">{{ __('messages.received_by') }}</span> <span class="fw-semibold text-success">{{ $order->delivery->received_by }}</span></div>
                            @endif
                        </div>
                        
                        @php
                            $allStatuses = ['assigned', 'on_the_way', 'delivered'];
                            $currentStatus = $order->delivery->delivery_status;
                            $statusIndex = array_search($currentStatus, $allStatuses);
                        @endphp
                        
                        {{-- Timeline Tracker HTML --}}
                        <div class="d-flex flex-column gap-4 py-2">
                            @foreach($allStatuses as $index => $status)
                                @php
                                    $isDone = $statusIndex >= $index;
                                    $isCurrent = $statusIndex === $index;
                                    
                                    $stepIcons = ['assigned' => 'clipboard-check', 'on_the_way' => 'truck', 'delivered' => 'check2-circle'];
                                    $stepTitles = [
                                        'assigned' => __('messages.step_title_assigned'),
                                        'on_the_way' => __('messages.step_title_on_the_way'),
                                        'delivered' => __('messages.step_title_delivered')
                                    ];
                                    $stepDescs = [
                                        'assigned' => __('messages.step_desc_assigned'),
                                        'on_the_way' => __('messages.step_desc_on_the_way'),
                                        'delivered' => __('messages.step_desc_delivered')
                                    ];
                                @endphp
                                
                                <div class="d-flex gap-3 position-relative">
                                    {{-- Line between circles --}}
                                    @if(!$loop->last)
                                        <div style="position: absolute; left: 18px; top: 36px; bottom: -28px; width: 3px; background: {{ $statusIndex > $index ? 'var(--primary)' : 'var(--gray-200)' }}; z-index: 1;"></div>
                                    @endif
                                    
                                    {{-- Circle icon --}}
                                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 38px; height: 38px; z-index: 2; border: 2.5px solid white;
                                                background: {{ $isDone ? 'var(--primary)' : 'var(--gray-200)' }}; 
                                                color: {{ $isDone ? 'white' : 'var(--gray-600)' }};">
                                        <i class="bi bi-{{ $stepIcons[$status] }} fs-5"></i>
                                    </div>
                                    
                                    <div class="pt-0.5">
                                        <h6 class="mb-1 fw-bold {{ $isDone ? 'text-dark' : 'text-muted' }}">{{ $stepTitles[$status] }}</h6>
                                        <p class="text-muted small mb-0" style="font-size:0.8rem;">{{ $stepDescs[$status] }}</p>
                                        @if($isCurrent)
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold small mt-1" style="font-size:0.62rem;">{{ __('messages.current_status') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right metadata column --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                    <i class="bi bi-info-circle text-success me-2"></i>{{ __('messages.summary_metadata') }}
                </div>
                
                <div class="card-body p-4">
                    <div class="mb-3 pb-3 border-bottom text-dark small">
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;">{{ __('messages.order_timestamp') }}</div>
                        <div class="fw-semibold">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom text-dark small">
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;">{{ __('messages.payment_channel') }}</div>
                        <div class="fw-semibold"><i class="bi bi-wallet2 text-success me-1"></i>{{ $order->payment_method }}</div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom text-dark small">
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;">{{ __('messages.collection_status') }}</div>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} text-white text-uppercase fw-bold" style="font-size:0.65rem;">
                            {{ Lang::has('messages.' . $order->payment_status) ? __('messages.' . $order->payment_status) : $order->payment_status }}
                        </span>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom text-dark small">
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;">{{ __('messages.shipping_destination') }}</div>
                        <div class="fw-semibold" style="line-height:1.4;">{{ $order->delivery_address }}</div>
                    </div>

                    @if($order->latitude && $order->longitude)
                    <div class="mb-3 pb-3 border-bottom text-dark small">
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;"><i class="bi bi-geo-alt text-success me-1"></i>{{ __('messages.delivery_location') }}</div>
                        <div id="customerOrderMap" style="height: 180px; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);"></div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted" style="font-size:0.7rem;">{{ number_format($order->latitude, 6) }}, {{ number_format($order->longitude, 6) }}</small>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill fw-bold" style="font-size:0.72rem;">
                                <i class="bi bi-sign-turn-right me-1"></i>{{ __('messages.get_directions') }}
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->note)
                        <div class="text-dark small">
                            <div class="text-muted fw-bold text-uppercase mb-1" style="font-size:0.68rem;">{{ __('messages.delivery_remarks') }}</div>
                            <div class="fw-medium text-muted" style="line-height:1.4;">"{{ $order->note }}"</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->latitude && $order->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $order->latitude }};
    var lng = {{ $order->longitude }};
    var map = L.map('customerOrderMap', { zoomControl: true, attributionControl: false }).setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup('{!! e($order->delivery_address) !!}').openPopup();
    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endif
@endsection
