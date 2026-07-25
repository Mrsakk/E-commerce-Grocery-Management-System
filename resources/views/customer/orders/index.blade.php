@extends('layouts.customer')
@section('title', __('messages.my_orders'))
@section('content')

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.my_orders') }}</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-box-seam text-success me-2"></i>{{ __('messages.my_orders') }}</h4>

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md); border: 1px solid var(--card-border); overflow: hidden;">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-3 px-4 bg-light border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-bold text-dark">{{ __('messages.order') }} #{{ $order->id }}</span>
                        <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $order->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $statusColors = ['pending' => 'warning', 'confirmed' => 'info', 'processing' => 'primary', 'shipped' => 'secondary', 'delivered' => 'success', 'cancelled' => 'danger'];
                            $payColors = ['paid' => 'success', 'unpaid' => 'warning', 'refunded' => 'danger'];
                        @endphp
                        <span class="badge text-uppercase bg-{{ $statusColors[$order->order_status] ?? 'secondary' }} fw-bold" style="font-size: 0.68rem; padding: 5px 10px; border-radius: 4px;">
                            {{ Lang::has('messages.' . $order->order_status) ? __('messages.' . $order->order_status) : $order->order_status }}
                        </span>
                        <span class="badge text-uppercase bg-{{ $payColors[$order->payment_status] ?? 'secondary' }} fw-bold" style="font-size: 0.68rem; padding: 5px 10px; border-radius: 4px;">
                            {{ Lang::has('messages.' . $order->payment_status) ? __('messages.' . $order->payment_status) : $order->payment_status }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            @foreach($order->details as $detail)
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:6px; height:6px; border-radius:50%; background:var(--primary);"></div>
                                        <span class="small text-dark fw-medium">{{ $detail->product->product_name }} <span class="text-muted">× {{ $detail->quantity }}</span></span>
                                    </div>
                                    <span class="small fw-bold text-muted">${{ number_format($detail->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="fw-extrabold text-success fs-5">${{ number_format($order->total_amount, 2) }}</div>
                            <small class="text-muted d-block mt-1"><i class="bi bi-wallet2 me-1"></i>{{ $order->payment_method }}</small>
                            @if($order->delivery && $order->delivery->tracking_no)
                                <div class="mt-1"><span class="badge bg-light text-dark border text-uppercase" style="font-size:0.62rem;"><i class="bi bi-truck me-1"></i>{{ $order->delivery->tracking_no }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center border-top">
                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3"><i class="bi bi-eye-fill me-1"></i> {{ __('messages.track_order') }}</a>
                    @if($order->order_status == 'pending')
                        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold p-0 btn-trigger-cancel" 
                                data-bs-toggle="modal" data-bs-target="#cancelOrderModal" 
                                data-action="{{ route('customer.orders.cancel', $order->id) }}">
                            {{ __('messages.cancel_order') }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
        
        <div class="mt-4 d-flex justify-content-center">{{ $orders->links() }}</div>
    @else
        <div class="card border-0 shadow-sm text-center py-5 px-4 mx-auto mt-4" style="max-width: 480px; border-radius: var(--radius-md); border: 1px solid var(--card-border);">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-receipt text-muted fs-2"></i>
            </div>
            <h5 class="fw-bold">{{ __('messages.no_orders_placed') }}</h5>
            <p class="text-muted small mb-4">{{ __('messages.no_orders_desc') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-success fw-bold px-4 rounded-pill py-2.5">
                <i class="bi bi-basket me-1"></i> {{ __('messages.start_shopping') }}
            </a>
        </div>
    @endif
</div>

{{-- Cancel Order Modal --}}
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start" style="border-radius: var(--radius-md);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="cancelOrderModalLabel"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelOrderForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-3">Please select a reason for cancelling this order. This helps us improve our service.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Cancellation Reason</label>
                        <select name="cancel_reason" id="cancelReasonSelect" class="form-select bg-light border-0 px-3" style="font-size:0.9rem; height:42px; border-radius:8px;" required>
                            <option value="" disabled selected>Select a reason...</option>
                            <option value="Changed my mind">Changed my mind</option>
                            <option value="Accidental order">Accidental order</option>
                            <option value="Found a better price elsewhere">Found a better price elsewhere</option>
                            <option value="Delivery takes too long">Delivery takes too long</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="customReasonWrapper">
                        <label class="form-label small fw-bold text-dark">Detailed Reason</label>
                        <textarea id="customReasonText" class="form-control" rows="3" placeholder="Please specify..." style="font-size:0.9rem; border-radius:8px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelModal = document.getElementById('cancelOrderModal');
        const cancelForm = document.getElementById('cancelOrderForm');
        const reasonSelect = document.getElementById('cancelReasonSelect');
        const customWrapper = document.getElementById('customReasonWrapper');
        const customText = document.getElementById('customReasonText');

        // Watch for cancel buttons clicked
        document.querySelectorAll('.btn-trigger-cancel').forEach(button => {
            button.addEventListener('click', function () {
                const actionUrl = this.dataset.action;
                cancelForm.action = actionUrl;
            });
        });

        // Toggle custom reason description when "Other" is selected
        reasonSelect.addEventListener('change', function () {
            if (this.value === 'Other') {
                customWrapper.classList.remove('d-none');
                customText.setAttribute('name', 'cancel_reason');
                reasonSelect.removeAttribute('name');
                customText.setAttribute('required', 'required');
            } else {
                customWrapper.classList.add('d-none');
                reasonSelect.setAttribute('name', 'cancel_reason');
                customText.removeAttribute('name');
                customText.removeAttribute('required');
            }
        });
    });
</script>

@endsection
