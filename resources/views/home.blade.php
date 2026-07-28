@extends('layouts.customer')
@section('title', 'Home')
@section('content')

{{-- Modern Hero Section --}}
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="d-flex justify-content-center justify-content-lg-start gap-4 mb-4 hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">{{ __('messages.items') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">{{ __('messages.fresh') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">2 HR</div>
                        <div class="stat-label">{{ __('messages.delivery') }}</div>
                    </div>
                </div>
                <h1 class="hero-title mb-3">{!! __('messages.fresh_grocery') !!}</h1>
                <p class="hero-subtitle mb-4">{{ __('messages.hero_subtitle') }}</p>
                
                <div class="d-flex gap-2 mt-4 justify-content-center justify-content-lg-start flex-wrap">
                    @foreach($categories as $cat)
                        @php $heroIcons = ['Fresh Vegetables'=>'flower1','Fresh Fruits'=>'apple','Meat & Poultry'=>'egg','Seafood'=>'water','Dairy & Eggs'=>'cup-straw','Rice & Noodles'=>'basket','Beverages'=>'cup']; @endphp
                        <a href="{{ route('products.category', $cat->id) }}" class="hero-chip">
                            <i class="bi bi-{{ $heroIcons[$cat->category_name] ?? 'basket' }} text-success"></i> 
                            {{ Lang::has('messages.' . $cat->category_name) ? __('messages.' . $cat->category_name) : $cat->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
            
            <div class="col-lg-5 d-none d-lg-block">
                {{-- Decorative Floating Card Illustration --}}
                <div class="card border-0 shadow-lg p-4 bg-white" style="border-radius: var(--radius-lg); transform: rotate(2deg);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ __('messages.quality_guaranteed') }}</h6>
                            <small class="text-muted">{{ __('messages.strict_inspections') }}</small>
                        </div>
                    </div>
                    <div class="text-center py-3 bg-light rounded-3 mb-3">
                        <i class="bi bi-truck fs-1 text-success"></i>
                        <h6 class="fw-bold mt-2 mb-0">{{ __('messages.phnom_penh_delivery') }}</h6>
                        <small class="text-muted">{{ __('messages.free_over_20') }}</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-dark">
                        <span class="small fw-semibold text-muted">{{ __('messages.secured_local_payment') }}</span>
                        <div class="d-flex gap-1">
                            <span class="badge bg-light text-success border">ABA</span>
                            <span class="badge bg-light text-primary border">Wing</span>
                            <span class="badge bg-light text-danger border">Bakong</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dynamic Promotional Carousel --}}
<style>
    .promo-carousel-container {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.08), 0 5px 15px -5px rgba(15, 23, 42, 0.03);
    }
    .promo-slide {
        padding: 60px 80px;
        min-height: 290px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .promo-btn {
        background: #ffffff !important;
        border: none !important;
        font-size: 0.92rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .promo-btn:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        filter: brightness(1.05);
    }
    .promo-floating-icon {
        position: absolute;
        right: 8%;
        top: 50%;
        transform: translateY(-50%) rotate(-12deg);
        font-size: 9.5rem;
        color: rgba(255, 255, 255, 0.08);
        pointer-events: none;
        line-height: 1;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .promo-slide:hover .promo-floating-icon {
        transform: translateY(-52%) rotate(-5deg) scale(1.08);
        color: rgba(255, 255, 255, 0.12);
    }
    .promo-floating-img-container {
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .promo-slide:hover .promo-floating-img-container {
        transform: translateY(-52%) rotate(0deg) scale(1.05) !important;
        box-shadow: 0 20px 45px rgba(0,0,0,0.3) !important;
    }
    @media (max-width: 768px) {
        .promo-slide {
            padding: 45px 30px;
            min-height: 240px;
        }
        .promo-floating-icon, .promo-floating-img-container {
            display: none !important;
        }
    }
</style>

<div class="container mt-5">
    <div id="promoCarousel" class="carousel slide promo-carousel-container carousel-fade" data-bs-ride="carousel">
        @if($banners->count() > 0)
            <div class="carousel-indicators" style="bottom: 18px;">
                @foreach($banners as $index => $banner)
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" style="width: 28px; height: 5px; border-radius: 5px; border: none;"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $index => $banner)
                    @php
                        $title = App::getLocale() === 'km' && $banner->title_km ? $banner->title_km : $banner->title_en;
                        $description = App::getLocale() === 'km' && $banner->description_km ? $banner->description_km : $banner->description_en;
                        $badge = App::getLocale() === 'km' && $banner->badge_km ? $banner->badge_km : $banner->badge_en;
                        $btnText = App::getLocale() === 'km' && $banner->button_text_km ? $banner->button_text_km : ($banner->button_text_en ?: __('messages.browse_shop'));
                        $link = $banner->link ?: route('products.index');
                        $gradient = $banner->gradient_css ?: 'linear-gradient(135deg, #022c22 0%, #065f46 50%, #10b981 100%)';
                    @endphp
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="5000">
                        <div style="background: {{ $gradient }};" class="promo-slide d-flex align-items-center">
                            <div class="position-relative z-1" style="max-width: 580px;">
                                @if($badge)
                                    <span class="badge bg-warning text-dark fw-bold mb-3 text-uppercase px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.8px;">
                                        <i class="bi {{ $banner->icon ?: 'bi-star-fill' }} me-1"></i> {{ $badge }}
                                    </span>
                                @endif
                                <h2 class="fw-extrabold mb-3 text-white display-6" style="font-family: 'Outfit', 'Koh Santepheap', 'Khmer', sans-serif; letter-spacing: -0.5px;">
                                    {{ $title }}
                                </h2>
                                @if($description)
                                    <p class="text-white opacity-85 mb-4 fs-5 fw-medium" style="line-height: 1.5;">
                                        {{ $description }}
                                    </p>
                                @endif
                                <a href="{{ $link }}" class="btn btn-light promo-btn px-5 py-3 rounded-pill fw-bold shadow" style="color: #065f46;">
                                    <span>{{ $btnText }}</span>
                                    <i class="bi bi-arrow-right-short fs-4"></i>
                                </a>
                            </div>
                            
                            @if($banner->image_path)
                                <div class="d-none d-md-block promo-floating-img-container" style="position: absolute; right: 8%; top: 50%; transform: translateY(-50%) rotate(2deg); width: 280px; height: 190px; pointer-events: none; border-radius: 16px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.25); border: 2px solid rgba(255,255,255,0.2);">
                                    <img src="{{ asset($banner->image_path) }}" alt="{{ $title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @else
                                <div class="promo-floating-icon">
                                    <i class="bi {{ $banner->icon ?: 'bi-star-fill' }}"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Default Fallback Carousel Slides -->
            <div class="carousel-indicators" style="bottom: 18px;">
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active" style="width: 28px; height: 5px; border-radius: 5px; border: none;"></button>
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1" style="width: 28px; height: 5px; border-radius: 5px; border: none;"></button>
            </div>
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active" data-bs-interval="5000">
                    <div style="background: linear-gradient(135deg, #022c22 0%, #065f46 50%, #10b981 100%);" class="promo-slide d-flex align-items-center">
                        <div class="position-relative z-1" style="max-width: 580px;">
                            <span class="badge bg-warning text-dark fw-bold mb-3 text-uppercase px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.8px;">
                                <i class="bi bi-star-fill me-1"></i> {{ __('messages.exclusive_deal') }}
                            </span>
                            <h2 class="fw-extrabold mb-3 text-white display-6" style="font-family: 'Outfit', 'Koh Santepheap', 'Khmer', sans-serif; letter-spacing: -0.5px;">
                                {{ __('messages.free_delivery_title') }}
                            </h2>
                            <p class="text-white opacity-85 mb-4 fs-5 fw-medium" style="line-height: 1.5;">
                                {{ __('messages.free_delivery_desc') }}
                            </p>
                            <a href="{{ route('products.index') }}" class="btn btn-light promo-btn px-5 py-3 rounded-pill fw-bold shadow" style="color: #065f46;">
                                <span>{{ __('messages.browse_shop') }}</span>
                                <i class="bi bi-arrow-right-short fs-4"></i>
                            </a>
                        </div>
                        <div class="promo-floating-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="carousel-item" data-bs-interval="5000">
                    <div style="background: linear-gradient(135deg, #2a0800 0%, #9a3412 50%, #ea580c 100%);" class="promo-slide d-flex align-items-center">
                        <div class="position-relative z-1" style="max-width: 580px;">
                            <span class="badge bg-light text-dark fw-bold mb-3 text-uppercase px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.8px;">
                                <i class="bi bi-patch-check-fill me-1 text-success"></i> {{ __('messages.direct_sourced') }}
                            </span>
                            <h2 class="fw-extrabold mb-3 text-white display-6" style="font-family: 'Outfit', 'Koh Santepheap', 'Khmer', sans-serif; letter-spacing: -0.5px;">
                                {{ __('messages.farm_fresh_veg') }}
                            </h2>
                            <p class="text-white opacity-85 mb-4 fs-5 fw-medium" style="line-height: 1.5;">
                                {{ __('messages.farm_fresh_veg_desc') }}
                            </p>
                            <a href="{{ route('products.category', 1) }}" class="btn btn-light promo-btn px-5 py-3 rounded-pill fw-bold shadow" style="color: #9a3412;">
                                <span>{{ __('messages.shop_veggies') }}</span>
                                <i class="bi bi-arrow-right-short fs-4"></i>
                            </a>
                        </div>
                        <div class="promo-floating-icon">
                            <i class="bi bi-flower1"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev" style="width: 7%;">
            <span class="carousel-control-prev-icon bg-dark bg-opacity-25 rounded-circle p-3" style="background-size: 50% 50%;"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next" style="width: 7%;">
            <span class="carousel-control-next-icon bg-dark bg-opacity-25 rounded-circle p-3" style="background-size: 50% 50%;"></span>
        </button>
    </div>
</div>

{{-- Best Selling Products --}}
@if($bestSellers->count() > 0)
<div class="container mt-5">
    <div class="section-title">
        <span><i class="bi bi-fire text-danger me-2"></i>Popular Items</span>
        <a href="{{ route('products.index') }}?sort=popular" class="see-all">View All <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 g-md-4">
        @foreach($bestSellers as $product)
            @include('partials.product-card', ['product' => $product, 'gridClass' => 'col'])
        @endforeach
    </div>
</div>
@endif

{{-- Featured Products --}}
<div class="container mt-5">
    <div class="section-title">
        <span><i class="bi bi-star-fill text-warning me-2"></i>Featured Freshness</span>
        <a href="{{ route('products.index') }}" class="see-all">View All <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 g-md-4">
        @foreach($featuredProducts as $product)
            @include('partials.product-card', ['product' => $product, 'gridClass' => 'col'])
        @endforeach
    </div>
</div>

{{-- Special Offers / Discounts --}}
@if($promotions->count() > 0)
<div class="container mt-5">
    <div class="section-title">
        <span><i class="bi bi-tag-fill text-danger me-2"></i>Hot Discounts</span>
        <a href="{{ route('promotions.index') }}" class="see-all">View All <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 g-md-4">
        @foreach($promotions as $product)
            @include('partials.product-card', ['product' => $product, 'gridClass' => 'col'])
        @endforeach
    </div>
</div>
@endif

{{-- Latest Products --}}
<div class="container mt-5">
    <div class="section-title">
        <span><i class="bi bi-clock-history text-primary me-2"></i>New Arrivals</span>
        <a href="{{ route('products.index') }}?sort=latest" class="see-all">View All <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 g-md-4">
        @foreach($latestProducts as $product)
            @include('partials.product-card', ['product' => $product, 'gridClass' => 'col'])
        @endforeach
    </div>
</div>

{{-- Features and Guarantees --}}
<div class="container my-5 py-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:58px;height:58px;">
                        <i class="bi bi-truck fs-3 text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Fast Dispatch</h6>
                    <p class="text-muted small mb-0">Delivery within 2 hours across Phnom Penh area suburbs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:58px;height:58px;">
                        <i class="bi bi-patch-check fs-3 text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark">100% Organic Quality</h6>
                    <p class="text-muted small mb-0">We guarantee absolute freshness or your money back.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-body">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:58px;height:58px;">
                        <i class="bi bi-wallet2 fs-3 text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Local QR Cashless</h6>
                    <p class="text-muted small mb-0">Easily pay with ABA Pay, Wing Pay, or Bakong QR codes.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
