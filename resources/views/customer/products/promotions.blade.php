@extends('layouts.customer')
@section('title', __('messages.promotions_offers'))
@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.promotions_offers') }}</li>
        </ol>
    </nav>

    <div class="p-4 p-md-5 rounded-4 mb-5 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid rgba(245, 158, 11, 0.2);">
        <div class="position-relative" style="z-index: 2;">
            <span class="badge bg-warning bg-opacity-20 text-warning-dark fw-extrabold mb-2 px-3 py-1.5 rounded-pill text-uppercase" style="color: #b45309;">{{ __('messages.exclusive_deal') }}</span>
            <h3 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">{{ __('messages.promotions_special_offers') }}</h3>
            <p class="text-muted mb-0" style="font-size:0.95rem;">{{ __('messages.promo_subtitle') }}</p>
        </div>
        <i class="bi bi-tag-fill position-absolute end-0 bottom-0 m-3 d-none d-md-block" style="font-size: 8rem; opacity: 0.05; color: var(--accent); transform: rotate(-10deg);"></i>
    </div>

    {{-- Active Coupons styled as physical tickets --}}
    @if($coupons->count() > 0)
        <div class="section-title mb-3">
            <span class="fs-5 fw-bold text-dark"><i class="bi bi-ticket-perforated-fill text-warning me-2"></i>{{ __('messages.active_coupon') ?? 'Available Coupons' }}</span>
        </div>
        <div class="row g-4 mb-5">
            @foreach($coupons as $coupon)
                <div class="col-md-6 col-lg-4">
                    <div class="coupon-card p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i class="bi bi-percent fs-3 text-warning"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">{{ __('messages.active_coupon') }}</span>
                            </div>
                            
                            @if($coupon->discount_type === 'percentage')
                                <h3 class="fw-extrabold text-danger mb-2" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem;">{{ $coupon->discount_value }}% OFF</h3>
                            @else
                                <h3 class="fw-extrabold text-danger mb-2" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem;">${{ number_format($coupon->discount_value, 2) }} OFF</h3>
                            @endif
                            
                            @if($coupon->min_order_amount)
                                <p class="text-muted mb-1 small"><i class="bi bi-cart me-1"></i>{{ __('messages.min_order') }}: <strong>${{ number_format($coupon->min_order_amount, 2) }}</strong></p>
                            @endif
                            @if($coupon->end_date)
                                <p class="text-muted mb-0 small"><i class="bi bi-clock me-1"></i>{{ __('messages.expires') }}: <strong>{{ \Carbon\Carbon::parse($coupon->end_date)->format('M d, Y') }}</strong></p>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 border-top border-light d-flex align-items-center justify-content-between">
                            <span class="small text-muted fw-bold">CODE:</span>
                            <span class="coupon-code-badge" onclick="copyCoupon('{{ $coupon->code }}', this)">
                                {{ $coupon->code }} <i class="bi bi-copy ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Discounted Products Listing --}}
    <div class="section-title mb-4">
        <span class="fs-4 fw-bold text-dark"><i class="bi bi-fire text-danger me-2"></i>{{ __('messages.discounted_products') }}</span>
    </div>
    @if($products->count() > 0)
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 g-md-4">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product, 'gridClass' => 'col'])
            @endforeach
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm text-center py-5 px-4 bg-white" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-emoji-frown text-muted fs-2"></i>
            </div>
            <p class="text-muted mb-0">{{ __('messages.no_promotions') }}</p>
        </div>
    @endif
</div>

<style>
    .coupon-card {
        position: relative;
        background: radial-gradient(circle at 0 50%, transparent 12px, #fffbf2 13px),
                    radial-gradient(circle at 100% 50%, transparent 12px, #fffbf2 13px);
        background-color: #fffbf2;
        border-radius: var(--radius-md);
        border: 2px dashed #fcd34d;
        overflow: visible;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
    }
    .coupon-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }
    .coupon-card::before, .coupon-card::after {
        content: '';
        position: absolute;
        width: 4px;
        height: calc(100% - 24px);
        top: 12px;
        background-image: radial-gradient(circle, #fcd34d 1.5px, transparent 2px);
        background-size: 4px 8px;
    }
    .coupon-card::before { left: -2.5px; }
    .coupon-card::after { right: -2.5px; }
    
    .coupon-code-badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 0.88rem;
        letter-spacing: 1px;
        padding: 6px 14px;
        border: 1.5px dashed var(--accent);
        background: var(--accent-50);
        color: var(--accent-dark);
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .coupon-code-badge:hover {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
        transform: scale(1.04);
    }
</style>

<script>
    function copyCoupon(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = 'COPIED! <i class="bi bi-check2"></i>';
            btn.style.background = '#d1fae5';
            btn.style.color = '#065f46';
            btn.style.borderColor = '#34d399';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
                btn.style.color = '';
                btn.style.borderColor = '';
            }, 2000);
        });
    }
</script>

@endsection
