@extends('layouts.customer')
@section('title', $product->product_name)
@section('content')

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category_id) }}">{{ Lang::has('messages.' . $product->category->category_name) ? __('messages.' . $product->category->category_name) : $product->category->category_name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->product_name }}</li>
        </ol>
    </nav>

    {{-- Main Product Card Info --}}
    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white mb-5" style="border-radius: var(--radius-lg); border: 1px solid var(--card-border);">
        <div class="row g-4 g-lg-5">
            {{-- Product Image Column --}}
            <div class="col-md-6 col-lg-5">
                <div class="position-relative shadow-sm border overflow-hidden product-img-zoom" style="background: #f8fafc; border-radius: var(--radius-lg); padding-bottom: 100%; cursor: zoom-in;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" 
                         class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; transition: transform 0.4s ease;">
                    
                    @if($product->inventory && $product->inventory->qty_in_stock <= $product->inventory->reorder_level)
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 px-3 py-1.5 fw-bold shadow-sm" style="font-size: 0.78rem;">{{ __('messages.low_stock') }}</span>
                    @endif
                </div>
            </div>

            {{-- Product Info Column --}}
            <div class="col-md-6 col-lg-7 d-flex flex-column justify-content-between">
                <div>
                    {{-- Badges row --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>{{ $product->brand ?? 'FreshMart' }}
                        </span>
                        <span class="badge bg-light text-muted fw-bold px-3 py-2 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-tag-fill me-1"></i>{{ Lang::has('messages.' . $product->category->category_name) ? __('messages.' . $product->category->category_name) : $product->category->category_name }}
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info fw-bold px-3 py-2 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-leaf-fill me-1"></i>100% Organic
                        </span>
                    </div>
                    
                    {{-- Title --}}
                    <h2 class="fw-bold mb-2 text-dark" style="font-size: 2.2rem; letter-spacing: -0.5px;">{{ $product->product_name }}</h2>
                    
                    {{-- Ratings --}}
                    @php
                        $avgRating = $product->averageRating();
                        $reviewsCount = $product->reviewsCount();
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="text-warning small d-flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star text-muted opacity-50"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $avgRating }}</span>
                        <span class="text-muted small">({{ $reviewsCount }} {{ __('messages.reviews') ?? 'reviews' }})</span>
                    </div>
                    
                    {{-- Price and KHR Estimate --}}
                    <div class="price-container p-3 px-4 rounded-4 bg-light bg-opacity-50 mb-4 d-inline-flex flex-column align-items-start" style="border: 1px solid var(--gray-200); min-width: 240px;">
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="price text-success fw-extrabold" style="font-size: 2.6rem; letter-spacing: -1px;">${{ number_format($product->price, 2) }}</span>
                            <span class="text-muted fw-bold">/ {{ $product->unit }}</span>
                        </div>
                        <span class="text-muted small mt-1 fw-bold"><i class="bi bi-cash-stack text-success me-1"></i> Est. ~{{ number_format($product->price * 4100, 0) }} KHR</span>
                    </div>

                    {{-- Stock and Expiry details --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @if($product->inventory)
                            <span class="badge rounded-pill px-3 py-2 border d-inline-flex align-items-center gap-1.5" style="{{ $product->inventory->qty_in_stock > 0 ? 'background:#ecfdf5; color:#047857; border-color:#d1fae5;' : 'background:#fef2f2; color:#b91c1c; border-color:#fee2e2;' }}">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                                <span class="fw-bold">{{ $product->inventory->qty_in_stock > 0 ? __('messages.in_stock_qty', ['qty' => $product->inventory->qty_in_stock, 'unit' => $product->unit]) : __('messages.out_of_stock') }}</span>
                            </span>
                        @endif
                        @if($product->expiry_date)
                            <span class="badge rounded-pill px-3 py-2 border d-inline-flex align-items-center gap-1.5 bg-light text-dark">
                                <i class="bi bi-calendar-event text-success"></i>
                                <span class="fw-bold">{{ __('messages.expiry_date_label', ['date' => $product->expiry_date]) }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Action / Forms Section --}}
                <div>
                    @auth
                        @if($product->inventory && $product->inventory->qty_in_stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="row g-3 align-items-end mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="col-12 col-sm-4">
                                    <label class="form-label fw-bold text-dark small">{{ __('messages.select_quantity') }}</label>
                                    <div class="quantity-picker d-flex align-items-center border rounded-3 bg-white" style="height: 48px; max-width: 150px; overflow: hidden;">
                                        <button type="button" class="btn btn-link text-dark text-decoration-none px-3 py-0 fs-5 fw-bold" onclick="var q=document.getElementById('qty'); if(parseInt(q.value)>1){ q.value=parseInt(q.value)-1; document.getElementById('stickyQty').value=q.value; }">−</button>
                                        <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->inventory->qty_in_stock }}" class="form-control text-center fw-bold border-0 bg-transparent p-0" readonly style="box-shadow: none; font-size: 1.1rem; width: 40px;">
                                        <button type="button" class="btn btn-link text-dark text-decoration-none px-3 py-0 fs-5 fw-bold" onclick="var q=document.getElementById('qty'); if(parseInt(q.value)<{{ $product->inventory->qty_in_stock }}){ q.value=parseInt(q.value)+1; document.getElementById('stickyQty').value=q.value; }">+</button>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-8 d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-success fw-bold d-inline-flex align-items-center justify-content-center gap-2 btn-lg shadow-sm" style="height:48px; border-radius: var(--radius-sm); padding:0 28px; background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none;">
                                        <i class="bi bi-cart-plus fs-5"></i> {{ __('messages.add_to_basket') }}
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="btn btn-warning fw-bold d-inline-flex align-items-center justify-content-center gap-2 btn-lg shadow-sm text-white" style="height:48px; border-radius: var(--radius-sm); padding:0 28px; background: linear-gradient(135deg, var(--accent), var(--accent-dark)); border: none;">
                                        <i class="bi bi-lightning-fill"></i> {{ __('messages.instant_checkout') }}
                                    </button>
                                    
                                    @php
                                        $detailInWishlist = \App\Models\Wishlist::where('customer_id', auth()->user()->customer->id ?? 0)
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    @endphp
                                    <button type="button" class="btn btn-light border d-inline-flex align-items-center justify-content-center shadow-sm" 
                                            style="width:48px; height:48px; border-radius: var(--radius-sm); transition: all 0.2s;" 
                                            title="{{ $detailInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                                            onclick="document.getElementById('detailsWishlistForm').submit();">
                                        <i class="bi bi-heart{{ $detailInWishlist ? '-fill text-danger' : '' }} fs-5"></i>
                                    </button>
                                </div>
                            </form>

                            <form id="detailsWishlistForm" action="{{ route('wishlist.add') }}" method="POST" style="display:none;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                            </form>
                            
                            {{-- Floating sticky footer for mobile screens --}}
                            <div class="d-lg-none fixed-bottom bg-white p-3 border-top" style="z-index:1025; box-shadow: 0 -3px 15px rgba(0,0,0,0.06);">
                                <div class="container d-flex align-items-center justify-content-between gap-3">
                                    <div>
                                        <span class="fw-extrabold text-success fs-5">${{ number_format($product->price, 2) }}</span>
                                        <div class="text-muted small">/{{ $product->unit }}</div>
                                    </div>
                                    
                                    <div class="input-group border rounded-3 overflow-hidden" style="max-width:110px; height:38px;">
                                        <button type="button" class="btn btn-sm btn-light" onclick="var q=document.getElementById('qty'); if(parseInt(q.value)>1){ q.value=parseInt(q.value)-1; document.getElementById('stickyQty').value=q.value; }">−</button>
                                        <input type="number" id="stickyQty" value="1" class="form-control form-control-sm text-center fw-bold border-0 bg-white" readonly>
                                        <button type="button" class="btn btn-sm btn-light" onclick="var q=document.getElementById('qty'); if(parseInt(q.value)<{{ $product->inventory->qty_in_stock }}){ q.value=parseInt(q.value)+1; document.getElementById('stickyQty').value=q.value; }">+</button>
                                    </div>
                                    
                                    <button type="button" class="btn btn-success fw-bold btn-sm flex-grow-1" style="height:38px; background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none;" onclick="document.querySelector('form[action*=\'cart/add\']').submit();">
                                        {{ __('messages.add_to_basket') }}
                                    </button>
                                </div>
                            </div>
                        @else
                            <button class="btn btn-lg btn-secondary d-flex align-items-center gap-2" disabled style="border-radius: var(--radius-sm);">
                                <i class="bi bi-x-circle"></i> {{ __('messages.out_of_stock') }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-lg btn-success fw-bold d-inline-flex align-items-center gap-2 mt-3" style="border-radius: var(--radius-sm); background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none; padding: 12px 32px;">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('messages.log_in_to_shop') }}
                        </a>
                    @endauth
                </div>

                {{-- Key Selling Points Section --}}
                <div class="row g-3 mt-4 pt-4 border-top">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px; flex-shrink:0;">
                                <i class="bi bi-truck fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size:0.85rem;">2-Hour Delivery</h6>
                                <span class="text-muted" style="font-size:0.72rem;">Express dispatch in Phnom Penh</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px; flex-shrink:0;">
                                <i class="bi bi-patch-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size:0.85rem;">Quality Sourced</h6>
                                <span class="text-muted" style="font-size:0.72rem;">Direct from local organic farms</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px; flex-shrink:0;">
                                <i class="bi bi-credit-card-2-front fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size:0.85rem;">Secure Payment</h6>
                                <span class="text-muted" style="font-size:0.72rem;">ABA, Wing Pay, Bakong, COD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Information --}}
    <div class="mb-5">
        <ul class="nav nav-tabs border-0 mb-3" id="productTabs" role="tablist" style="gap:16px;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-3 py-2.5 fs-5" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">
                    <i class="bi bi-card-text me-1.5"></i> {{ __('messages.description') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2.5 fs-5" id="nutrition-tab" data-bs-toggle="tab" data-bs-target="#nutrition" type="button">
                    <i class="bi bi-info-circle me-1.5"></i> {{ __('messages.specifications') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2.5 fs-5" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="bi bi-star-fill text-warning me-1.5"></i> {{ __('messages.reviews') }}
                </button>
            </li>
        </ul>
        
        <div class="tab-content bg-white border rounded-4 shadow-sm p-4">
            <div class="tab-pane fade show active" id="desc" role="tabpanel">
                <p class="text-muted mb-0" style="line-height:1.8; font-size: 0.98rem;">{{ $product->description ?: __('messages.no_desc_available') }}</p>
            </div>
            <div class="tab-pane fade" id="nutrition" role="tabpanel">
                <div class="row g-3 text-muted">
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-light bg-opacity-25 d-flex justify-content-between">
                            <strong>{{ __('messages.stock_capacity') }}:</strong>
                            <span>{{ $product->inventory->qty_in_stock ?? 0 }} units</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-light bg-opacity-25 d-flex justify-content-between">
                            <strong>{{ __('messages.reorder_warning') }}:</strong>
                            <span>{{ $product->inventory->reorder_level ?? 5 }} units</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-light bg-opacity-25 d-flex justify-content-between">
                            <strong>{{ __('messages.direct_source') }}:</strong>
                            <span>{{ __('messages.local_organic_farm') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-light bg-opacity-25 d-flex justify-content-between">
                            <strong>{{ __('messages.fulfillment_time') }}:</strong>
                            <span>{{ __('messages.dispatch_time') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                @php
                    $reviews = $product->reviews()->with('customer.user')->latest()->get();
                    $customer = auth()->check() ? auth()->user()->customer : null;
                    
                    $hasPurchased = false;
                    $alreadyReviewed = false;
                    
                    if ($customer) {
                        $hasPurchased = \App\Models\Order::where('customer_id', $customer->id)
                            ->where('order_status', 'delivered')
                            ->whereHas('details', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })->exists();
                            
                        $alreadyReviewed = \App\Models\ProductReview::where('product_id', $product->id)
                            ->where('customer_id', $customer->id)
                            ->exists();
                    }
                @endphp
                
                <div class="row g-4 text-start">
                    <div class="col-lg-7">
                        <h5 class="fw-bold mb-3 text-dark">Customer Reviews ({{ $reviews->count() }})</h5>
                        
                        @if($reviews->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($reviews as $rev)
                                    <div class="p-3 border rounded-3 bg-light bg-opacity-25">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size:0.9rem;">{{ $rev->customer->user->name }}</span>
                                                <div class="text-warning small mt-0.5">
                                                    @for($star = 1; $star <= 5; $star++)
                                                        <i class="bi bi-star{{ $star <= $rev->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $rev->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <p class="mb-0 text-muted small" style="line-height:1.5;">{{ $rev->review_text ?: 'No comment left.' }}</p>
                                    </div>
                                @endforeach
                             </div>
                        @else
                             <div class="text-center py-4 border rounded-3 bg-light bg-opacity-10">
                                 <i class="bi bi-chat-left-text text-muted fs-1 mb-2 d-block"></i>
                                 <span class="text-muted small">No reviews yet. Be the first to review this product!</span>
                             </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-5">
                        @auth
                            @if($hasPurchased && !$alreadyReviewed)
                                <div class="p-4 border rounded-4 bg-light">
                                    <h5 class="fw-bold mb-3 text-dark">Write a Review</h5>
                                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark">Rating</label>
                                            <div class="rating-stars-input d-flex gap-2 text-muted fs-4" style="cursor:pointer;">
                                                <i class="bi bi-star star-select" data-value="1"></i>
                                                <i class="bi bi-star star-select" data-value="2"></i>
                                                <i class="bi bi-star star-select" data-value="3"></i>
                                                <i class="bi bi-star star-select" data-value="4"></i>
                                                <i class="bi bi-star star-select" data-value="5"></i>
                                            </div>
                                            <input type="hidden" name="rating" id="ratingValueInput" value="5" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark">Your Review</label>
                                            <textarea name="review_text" class="form-control bg-white" rows="4" placeholder="Write your feedback..." style="font-size:0.9rem; border-radius:10px;"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-2.5">Submit Review</button>
                                    </form>
                                </div>
                            @elseif($alreadyReviewed)
                                <div class="p-4 border rounded-4 bg-light text-center">
                                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                                    <h6 class="fw-bold mb-1">Review Submitted</h6>
                                    <span class="text-muted small">You have already left feedback for this product. Thank you!</span>
                                </div>
                            @else
                                <div class="p-4 border rounded-4 bg-light text-center">
                                    <i class="bi bi-info-circle text-warning fs-1 mb-2 d-block"></i>
                                    <h6 class="fw-bold mb-1">Verified Purchases Only</h6>
                                    <span class="text-muted small">You can only write a review after purchasing and receiving this product.</span>
                                </div>
                            @endif
                        @else
                            <div class="p-4 border rounded-4 bg-light text-center">
                                <i class="bi bi-lock text-muted fs-1 mb-2 d-block"></i>
                                <h6 class="fw-bold mb-1">Login Required</h6>
                                <span class="text-muted small">Please log in to submit a product review.</span>
                             </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-5 pt-4 border-top">
            <div class="section-title mb-4">
                <span class="fs-4 fw-bold"><i class="bi bi-gift-fill text-success me-2"></i>{{ __('messages.related_fresh_items') }}</span>
                <a href="{{ route('products.category', $product->category_id) }}" class="see-all text-success fw-bold text-decoration-none">
                    {{ __('messages.view_all') }} <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 g-md-4">
                @foreach($relatedProducts as $related)
                    @include('partials.product-card', ['product' => $related, 'gridClass' => 'col'])
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .product-img-zoom {
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .product-img-zoom:hover img {
        transform: scale(1.05);
    }
    #productTabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        color: var(--gray-600);
        background: transparent !important;
        transition: all 0.2s ease;
        padding-bottom: 12px;
    }
    #productTabs .nav-link.active {
        border-bottom-color: var(--primary);
        color: var(--primary-dark) !important;
        font-weight: 700;
    }
    #productTabs .nav-link:hover {
        color: var(--primary);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const starSelects = document.querySelectorAll('.star-select');
        const ratingInput = document.getElementById('ratingValueInput');
        
        starSelects.forEach(star => {
            star.addEventListener('click', function () {
                const val = parseInt(this.dataset.value);
                ratingInput.value = val;
                
                starSelects.forEach(s => {
                    const sVal = parseInt(s.dataset.value);
                    if (sVal <= val) {
                        s.className = 'bi bi-star-fill star-select text-warning';
                    } else {
                        s.className = 'bi bi-star star-select text-muted';
                    }
                });
            });
        });
        
        // Trigger click on 5th star on load if form is present
        const defaultStar = document.querySelector('.star-select[data-value="5"]');
        if (defaultStar) defaultStar.click();
    });
</script>

@endsection
