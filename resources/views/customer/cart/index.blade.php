@extends('layouts.customer')
@section('title', __('messages.shopping_cart'))
@section('content')

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.shopping_cart') }}</li>
        </ol>
    </nav>

    {{-- Step Indicator --}}
    <div class="step-indicator">
        <div class="step-item"><div class="step-circle active">1</div><span class="step-label active">{{ __('messages.cart') }}</span></div>
        <div class="step-line"></div>
        <div class="step-item"><div class="step-circle pending">2</div><span class="step-label pending">{{ __('messages.checkout') }}</span></div>
        <div class="step-line"></div>
        <div class="step-item"><div class="step-circle pending">3</div><span class="step-label pending">{{ __('messages.confirmation') }}</span></div>
    </div>

    @if($cart && $cart->items->count() > 0)
        <div class="row g-4">
            {{-- Cart Items Column --}}
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-cart3 text-success me-2"></i>{{ __('messages.my_cart') }} ({{ $cart->items->count() }})</h4>
                </div>
                
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead class="table-light text-muted small uppercase">
                                <tr>
                                    <th style="width:45%; padding: 16px;">{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.subtotal') }}</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                    <tr>
                                        <td style="padding: 16px;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--gray-200); overflow: hidden; background: var(--gray-50);">
                                                    <img src="{{ $item->product?->image_url ?? '' }}" alt="{{ $item->product?->product_name ?? 'Product' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <a href="{{ route('products.show', $item->product_id) }}" class="text-decoration-none text-dark fw-bold" style="font-size:0.92rem;">{{ $item->product?->product_name ?? 'Deleted Product' }}</a>
                                                    <div class="text-muted small" style="font-size: 0.78rem;">{{ $item->product?->brand ?? 'FreshMart' }} | {{ $item->product?->unit ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">${{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="input-group input-group-sm border rounded-3 overflow-hidden" style="max-width:120px;">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-light" onclick="var q=this.parentElement.querySelector('input'); if(parseInt(q.value)>1){ q.value=parseInt(q.value)-1; this.form.submit(); }">−</button>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product?->inventory?->qty_in_stock ?? 99 }}" class="form-control text-center fw-bold border-0 bg-white" readonly>
                                                <button type="button" class="btn btn-light" onclick="var q=this.parentElement.querySelector('input'); if(parseInt(q.value)<{{ $item->product?->inventory?->qty_in_stock ?? 99 }}){ q.value=parseInt(q.value)+1; this.form.submit(); }">+</button>
                                            </form>
                                        </td>
                                        <td class="fw-extrabold text-success">${{ number_format($item->subtotal, 2) }}</td>
                                        <td class="pe-3">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('{{ __('Remove :name from cart?', ['name' => $item->product?->product_name ?? 'this item']) }}')">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center py-3 border-top">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-bold rounded-pill px-3" onclick="return confirm('{{ __('Are you sure you want to empty your cart?') }}')"><i class="bi bi-trash me-1"></i> {{ __('messages.empty_cart') }}</button>
                        </form>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-sm fw-bold rounded-pill px-3"><i class="bi bi-arrow-left me-1"></i> {{ __('messages.continue_shopping') }}</a>
                    </div>
                </div>
            </div>

            {{-- Summary Column --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border: 1px solid var(--card-border); position: sticky; top: 90px;">
                    <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                        <i class="bi bi-receipt text-success me-2"></i>{{ __('messages.order_summary') }}
                    </div>
                    
                    <div class="card-body">
                        @php
                            $subtotal = $cart->items->sum('subtotal');
                            $appliedFee = $subtotal >= $freeDeliveryMin ? 0 : $deliveryFee;
                            $couponDiscount = session('coupon_discount', 0);
                            $total = max(0, ($subtotal - $couponDiscount) + $appliedFee);
                        @endphp
                        
                        {{-- Free Delivery Goal --}}
                        <div class="free-delivery-bar mb-4">
                            @if($subtotal >= $freeDeliveryMin)
                                <div class="d-flex align-items-center gap-2 text-success small">
                                    <i class="bi bi-check-circle-fill"></i> 
                                    <span class="fw-bold">{{ __('messages.free_delivery_phnom_penh') }}</span>
                                </div>
                            @else
                                <div class="d-flex justify-content-between small mb-1.5 text-dark">
                                    <span class="fw-semibold text-muted">{{ __('messages.free_delivery_over') }} ${{ number_format($freeDeliveryMin, 0) }}</span> 
                                    <span class="fw-bold">${{ number_format($subtotal, 2) }} / ${{ number_format($freeDeliveryMin, 0) }}</span>
                                </div>
                                <div class="free-delivery-progress">
                                    <div class="bar" style="width:{{ min(100, ($subtotal / $freeDeliveryMin) * 100) }}%;"></div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">{{ __('messages.cart_subtotal') }}</span>
                            <span class="fw-bold text-dark">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">{{ __('messages.delivery_dispatch') }}</span>
                            @if($appliedFee == 0)
                                <span class="text-success fw-bold">{{ __('messages.free') }}</span>
                            @else
                                <span class="fw-bold text-dark">${{ number_format($deliveryFee, 2) }}</span>
                            @endif
                        </div>

                        @if($couponDiscount > 0)
                            <div class="d-flex justify-content-between mb-3 text-danger">
                                <span class="small">Coupon ({{ session('coupon_code') }})</span>
                                <span class="fw-bold">-${{ number_format($couponDiscount, 2) }}</span>
                            </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold text-dark fs-6">{{ __('messages.est_total') }}</span>
                            <span class="fw-extrabold text-success fs-5">${{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Coupon Box --}}
                        <div class="mb-3">
                            @if(session('coupon_code'))
                                <div class="p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block" style="font-size:0.7rem; font-weight:600;">{{ __('messages.applied_code') ?? 'Applied Code' }}</small>
                                        <span class="fw-bold text-success"><i class="bi bi-tag-fill me-1"></i>{{ session('coupon_code') }}</span>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill" style="font-size:0.78rem;">{{ __('messages.remove') ?? 'Remove' }}</button>
                                    </form>
                                </div>
                            @else
                                <button class="btn btn-sm btn-outline-secondary w-100 rounded-3 text-start small d-flex align-items-center justify-content-between px-3 py-2" type="button" onclick="document.getElementById('couponForm').classList.toggle('d-none')">
                                    <span><i class="bi bi-ticket-perforated text-success me-1"></i> {{ __('messages.apply_coupon') }}</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </button>
                                <form id="couponForm" class="d-none input-group input-group-sm mt-2 mb-0" action="{{ route('cart.coupon.apply') }}" method="POST">
                                    @csrf
                                    <input type="text" name="coupon_code" class="form-control" placeholder="{{ __('messages.promo_code') }}" required>
                                    <button class="btn btn-success text-white" type="submit">{{ __('messages.apply') }}</button>
                                </form>
                            @endif
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: var(--radius-sm); padding:12px;">
                            <i class="bi bi-credit-card-fill"></i> {{ __('messages.proceed_checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm text-center py-5 px-4 mx-auto mt-4" style="max-width: 500px; border-radius: var(--radius-md); border: 1px solid var(--card-border);">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 90px; height: 90px;">
                <i class="bi bi-cart3 text-muted" style="font-size: 2.8rem;"></i>
            </div>
            <h4 class="fw-bold mb-2">{{ __('messages.cart_empty_title') }}</h4>
            <p class="text-muted small mb-4">{{ __('messages.cart_empty_desc') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-success fw-bold px-4 rounded-pill py-2.5">
                <i class="bi bi-basket me-1"></i> {{ __('messages.start_shopping') }}
            </a>
        </div>
    @endif
</div>

<style>
    .table-custom th {
        font-weight: 700;
        font-size: 0.75rem;
        background-color: var(--gray-50);
        border-bottom: 1.5px solid var(--gray-200);
    }
    .table-custom td {
        border-bottom: 1px solid var(--gray-100);
    }
</style>

@endsection
