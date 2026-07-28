@extends('layouts.customer')
@section('title', __('messages.my_wishlist'))
@section('content')

<style>
    .btn-wishlist-delete {
        width: 32px;
        height: 32px;
        padding: 0 !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        background: white !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        color: #ef4444 !important;
        border-radius: 50% !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn-wishlist-delete:hover {
        background: #ef4444 !important;
        color: white !important;
        transform: scale(1.15) rotate(90deg) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
    }

    .btn-wishlist-delete i {
        font-size: 0.95rem !important;
        line-height: 1 !important;
    }

    /* Homepage card styling match */
    .product-card {
        border-color: var(--gray-200);
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary) !important;
    }
    .card-out-of-stock {
        border-color: var(--gray-200) !important;
        box-shadow: none !important;
    }
    .card-out-of-stock:hover {
        transform: none !important;
        box-shadow: none !important;
    }
    .btn-add-cart-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--primary-50);
        color: var(--primary);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }
    .btn-add-cart-circle:hover {
        background: var(--primary);
        color: white;
        transform: scale(1.06);
    }
</style>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.my_wishlist') }}</li>
        </ol>
    </nav>

    <div class="page-header-custom">
        <h4><i class="bi bi-heart text-danger me-2"></i>{{ __('messages.my_wishlist') }}</h4>
    </div>

    @if($wishlists->count() > 0)
        <div class="row g-4">
            @foreach($wishlists as $item)
                @php
                    $product = $item->product;
                    $qtyInStock = $product->inventory?->qty_in_stock ?? 0;
                    $reorderLevel = $product->inventory?->reorder_level ?? 0;
                    $isOutOfStock = $qtyInStock <= 0;
                    $isLowStock = !$isOutOfStock && $qtyInStock <= $reorderLevel;
                @endphp
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="product-card d-flex flex-column position-relative {{ $isOutOfStock ? 'card-out-of-stock' : '' }}" 
                         style="background: white; border: 1.5px solid var(--gray-200); border-radius: var(--radius-md); overflow: hidden; transition: all 0.25s ease; height: 100%;">
                        
                        <!-- Media Container -->
                        <div class="position-relative overflow-hidden" style="padding-bottom: 90%; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            
                            <!-- Product detail link image -->
                            <a href="{{ route('products.show', $product->id) }}" class="d-flex align-items-center justify-content-center position-absolute w-100 h-100 text-decoration-none">
                                <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" 
                                     class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;" loading="lazy">
                            </a>

                            <!-- Out of stock text overlay -->
                            @if($isOutOfStock)
                                <div class="position-absolute inset-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 5; top: 0; left: 0;">
                                    <span class="badge bg-danger text-white text-uppercase px-2.5 py-1 mb-1 fw-bold" style="font-size: 0.65rem;">{{ __('messages.out_of_stock') }}</span>
                                    <span class="fw-bold text-danger" style="font-size: 0.72rem; font-family: 'Kantumruy Pro', sans-serif;">{{ __('messages.out_of_stock_kh') ?? 'អស់ពីស្តុក' }}</span>
                                </div>
                            @endif

                            <!-- Delete wishlist trigger overlay (Top Right) -->
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-wishlist-delete" onclick="return confirm('{{ __('messages.remove_wishlist_confirm') }}')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>

                            <!-- Low stock badge overlay -->
                            @if($isLowStock)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 fw-bold shadow-sm" style="font-size: 0.62rem; padding: 4px 8px; border-radius: 4px;">
                                    {{ __('messages.only_left', ['qty' => $qtyInStock]) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Details Body -->
                        <div class="card-body p-3 d-flex flex-column flex-grow-1">
                            <span class="text-uppercase text-success fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ Lang::has('messages.' . ($product->category?->category_name ?? '')) ? __('messages.' . ($product->category?->category_name ?? '')) : ($product->category?->category_name ?? '') }}</span>
                            <h6 class="card-title fw-bold mt-1 mb-1" style="font-size: 0.92rem; height: 2.6em; line-height: 1.3; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">{{ $product->product_name }}</a>
                            </h6>
                            <div class="text-muted small mb-2" style="font-size: 0.75rem;">{{ $product->brand ?? 'FreshMart' }}</div>
                            
                            <!-- Ratings and score -->
                            <div class="d-flex align-items-center gap-1 mb-3 text-warning" style="font-size: 0.78rem;">
                                <i class="bi bi-star-fill"></i>
                                <span class="text-dark fw-bold">4.8</span>
                                <span class="text-muted">(24)</span>
                            </div>

                            <!-- Price and action row -->
                            <div class="price-row mt-auto d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="price text-success fw-extrabold fs-5">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-muted small">/{{ $product->unit }}</span>
                                    <!-- Dual Riel estimate pricing -->
                                    <div class="text-muted" style="font-size:0.68rem; line-height: 1;">~{{ number_format($product->price * 4100, 0) }} ៛</div>
                                </div>
                                
                                @if(!$isOutOfStock)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart-circle shadow-sm" title="Add to Cart">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-add-cart-circle bg-light border text-muted" disabled style="cursor: not-allowed; opacity: 0.5;">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-heartbreak fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">{{ __('messages.wishlist_empty_title') }}</h5>
            <p class="text-muted">{{ __('messages.wishlist_empty_desc') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">{{ __('messages.browse_products') }}</a>
        </div>
    @endif
</div>

@endsection
