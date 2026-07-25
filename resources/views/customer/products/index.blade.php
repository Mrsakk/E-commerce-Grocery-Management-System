@extends('layouts.customer')
@section('title', isset($category) ? (Lang::has('messages.' . $category->category_name) ? __('messages.' . $category->category_name) : $category->category_name) : (isset($query) ? __('messages.search_results') . ': ' . $query : __('messages.fresh_products')))
@section('content')

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            @if(isset($category))
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                <li class="breadcrumb-item active">{{ Lang::has('messages.' . $category->category_name) ? __('messages.' . $category->category_name) : $category->category_name }}</li>
            @else
                <li class="breadcrumb-item active">{{ __('messages.products') }}</li>
            @endif
        </ol>
    </nav>

    {{-- Category Banner Header with Premium Gradients --}}
    @php
        $bgGradients = [
            'Fresh Vegetables' => 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
            'Fresh Fruits' => 'linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%)',
            'Meat & Poultry' => 'linear-gradient(135deg, #fffaf0 0%, #ffebcf 100%)',
            'Seafood' => 'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)',
            'Dairy & Eggs' => 'linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%)',
            'Rice & Noodles' => 'linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%)',
            'Beverages' => 'linear-gradient(135deg, #fdf8f6 0%, #fbeee9 100%)',
        ];
        $catName = isset($category) ? $category->category_name : '';
        $bannerBg = $bgGradients[$catName] ?? 'linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%)';
    @endphp

    <div class="p-4 p-md-5 rounded-4 mb-4 shadow-sm position-relative overflow-hidden" style="background: {{ $bannerBg }}; border: 1px solid var(--card-border);">
        <div class="position-relative" style="z-index: 2;">
            @if(isset($query))
                <span class="badge bg-success bg-opacity-10 text-success fw-bold mb-2 px-3 py-1.5 rounded-pill">{{ __('messages.search') }}</span>
                <h3 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">{{ __('messages.search_results') }}: "{{ $query }}"</h3>
                <p class="text-muted small mb-0">{{ $products->total() }} {{ __('messages.matching_found') }}</p>
            @elseif(isset($category))
                <span class="badge bg-success bg-opacity-10 text-success fw-bold mb-2 px-3 py-1.5 rounded-pill">{{ __('messages.categories') }}</span>
                <h3 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">{{ Lang::has('messages.' . $category->category_name) ? __('messages.' . $category->category_name) : $category->category_name }}</h3>
                <p class="text-muted mb-0" style="font-size: 0.92rem;">{{ $category->description ?: __('messages.browse_quality') }}</p>
            @else
                <span class="badge bg-success bg-opacity-10 text-success fw-bold mb-2 px-3 py-1.5 rounded-pill">{{ __('messages.products') }}</span>
                <h3 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">{{ __('messages.fresh_products') }}</h3>
                <p class="text-muted mb-0" style="font-size: 0.92rem;">{{ __('messages.browse_quality') }}</p>
            @endif
        </div>
        <!-- Subtle icon backdrop overlay -->
        <i class="bi bi-basket-fill position-absolute end-0 bottom-0 m-3 d-none d-md-block" style="font-size: 8rem; opacity: 0.05; color: var(--primary); transform: rotate(-15deg);"></i>
    </div>

    <div class="row g-4">
        {{-- Left Filters Panel --}}
        <div class="col-lg-3">
            <div class="d-lg-none mb-3">
                <button class="btn btn-outline-success w-100 shadow-sm fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-funnel-fill me-1"></i> {{ __('messages.filter_products') }}
                </button>
            </div>
            <div class="collapse d-lg-block" id="filterCollapse">
                @include('partials.product-filters', ['categories' => $categories, 'category' => $category ?? null, 'sticky' => true])
            </div>
        </div>

        {{-- Right Products Grid --}}
        <div class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                <div class="text-muted small fw-semibold bg-white px-3 py-2 rounded-pill border shadow-sm">
                    {{ __('messages.showing') }} {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $products->total() }}
                </div>
            </div>

            {{-- Active Filter Chips --}}
            @if(request('min_price') || request('max_price') || request('sort'))
                <div class="d-flex flex-wrap gap-2 mb-4 align-items-center bg-white p-3 rounded-4 border shadow-sm">
                    <span class="small text-muted fw-bold ms-2">{{ __('messages.active') }}:</span>
                    @if(request('sort'))
                        <a href="{{ route('products.index', array_merge(request()->except('sort', 'page'), ['sort' => ''])) }}" class="filter-chip text-decoration-none">
                            {{ __('messages.sort_label') }}: {{ request('sort') == 'price_low' ? __('messages.price_low_high') : (request('sort') == 'price_high' ? __('messages.price_high_low') : (request('sort') == 'popular' ? __('messages.popularity') : __('messages.newest_items'))) }}
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                    @if(request('min_price'))
                        <a href="{{ route('products.index', array_merge(request()->except('min_price', 'page'), ['min_price' => ''])) }}" class="filter-chip text-decoration-none">
                            {{ __('messages.min_placeholder') }}: ${{ request('min_price') }} <i class="bi bi-x"></i>
                        </a>
                    @endif
                    @if(request('max_price'))
                        <a href="{{ route('products.index', array_merge(request()->except('max_price', 'page'), ['max_price' => ''])) }}" class="filter-chip text-decoration-none">
                            {{ __('messages.max_placeholder') }}: ${{ request('max_price') }} <i class="bi bi-x"></i>
                        </a>
                    @endif
                    <a href="{{ route('products.index') }}" class="text-decoration-none small text-danger fw-bold ms-auto me-2">{{ __('messages.clear_all') }}</a>
                </div>
            @endif

            {{-- Products Listing --}}
            @if($products->count() > 0)
                <div class="row g-3 g-md-4">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product, 'gridClass' => 'col-6 col-md-4 col-lg-3'])
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm text-center py-5 px-4 bg-white" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-search text-muted fs-2"></i>
                    </div>
                    <h5 class="fw-bold">{{ __('messages.no_products_found') }}</h5>
                    <p class="text-muted small mb-4">{{ __('messages.no_products_desc') }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-success fw-bold px-4" style="background: linear-gradient(135deg, var(--primary-light), var(--primary)); border:none;">{{ __('messages.view_all_products') }}</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-50);
        color: var(--primary-dark);
        border: 1px solid var(--primary-light);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .filter-chip:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endsection
