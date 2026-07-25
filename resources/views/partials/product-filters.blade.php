<div class="card border-0 shadow-sm overflow-hidden {{ isset($sticky) && $sticky ? 'filter-sidebar-sticky' : '' }}" 
     style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
    
    <!-- Sidebar Header -->
    <div class="card-header bg-white fw-bold py-3" style="border-bottom: 1.5px solid var(--gray-200);">
        <span class="d-flex align-items-center gap-2 text-dark"><i class="bi bi-sliders text-success"></i> {{ __('messages.filter_catalog') }}</span>
    </div>
        <!-- Category Section -->
    <div class="card-header bg-light fw-bold py-2.5 border-top border-bottom" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase; color: var(--gray-600);">
        {{ __('messages.categories') }}
    </div>

    <div class="list-group list-group-flush" style="font-size: 0.88rem;">
        <!-- All Products Link -->
        @php
            $isAllActive = !isset($category) && !request('category');
        @endphp
        <a href="{{ route('products.index') }}" 
           class="list-group-item list-group-item-action border-0 py-2.5 px-3 d-flex align-items-center justify-content-between {{ $isAllActive ? 'active-filter-item' : 'text-dark' }}">
            <span><i class="bi bi-collection me-2 text-success"></i>{{ __('messages.all_items') }}</span>
            <i class="bi bi-chevron-right small text-muted"></i>
        </a>

        <!-- Category Loop Links -->
        @foreach($categories as $cat)
            @php
                $isCatActive = (isset($category) && $category->id == $cat->id) || request('category') == $cat->id;
                $heroIcons = ['Fresh Vegetables'=>'flower1','Fresh Fruits'=>'apple','Meat & Poultry'=>'egg','Seafood'=>'water','Dairy & Eggs'=>'cup-straw','Rice & Noodles'=>'basket','Beverages'=>'cup'];
                $catIcon = $heroIcons[$cat->category_name] ?? 'chevron-right';
            @endphp
            <a href="{{ route('products.category', $cat->id) }}?{{ http_build_query(request()->except('category', 'page')) }}"
               class="list-group-item list-group-item-action border-0 py-2.5 px-3 d-flex align-items-center justify-content-between {{ $isCatActive ? 'active-filter-item' : 'text-dark' }}">
                <span><i class="bi bi-{{ $catIcon }} me-2 text-success"></i>{{ Lang::has('messages.' . $cat->category_name) ? __('messages.' . $cat->category_name) : $cat->category_name }}</span>
                <i class="bi bi-chevron-right small text-muted"></i>
            </a>
        @endforeach
    </div>

    <!-- Active Filters Form -->
    <div class="card-body p-3">
        <form method="GET" action="{{ route('products.index') }}">
            <!-- Category Context Retention -->
            @if(isset($category))
                <input type="hidden" name="category" value="{{ $category->id }}">
            @endif

            <!-- Sort By Control -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">{{ __('messages.sort_by') }}</label>
                <select name="sort" class="form-select form-select-sm border-2" onchange="this.form.submit()" style="font-size:0.85rem; border-radius:6px;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.newest_items') }}</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('messages.price_low_high') }}</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('messages.price_high_low') }}</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('messages.popularity') }}</option>
                </select>
            </div>

            <!-- Price Limits Controls -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small mb-2">{{ __('messages.price_limit') }}</label>
                <div class="d-flex gap-2 align-items-center">
                    <input type="number" name="min_price" class="form-control form-control-sm border-2 text-center" placeholder="{{ __('messages.min_placeholder') }}" value="{{ request('min_price') }}" style="font-size:0.82rem; border-radius:6px;">
                    <span class="text-muted small">—</span>
                    <input type="number" name="max_price" class="form-control form-control-sm border-2 text-center" placeholder="{{ __('messages.max_placeholder') }}" value="{{ request('max_price') }}" style="font-size:0.82rem; border-radius:6px;">
                </div>
            </div>

            <!-- Action buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-sm w-100 fw-bold rounded-3" style="font-size:0.8rem; padding: 7px 0;"><i class="bi bi-filter me-1"></i> {{ __('messages.apply') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-light border btn-sm w-100 fw-bold rounded-3 text-muted" style="font-size:0.8rem; padding: 7px 0;">{{ __('messages.reset') }}</a>
            </div>
        </form>
    </div>


</div>

<style>
    @media (min-width: 992px) {
        .filter-sidebar-sticky, #filterCollapse {
            position: sticky;
            top: 90px;
            z-index: 10;
        }
    }
    .active-filter-item {
        background-color: var(--primary-50) !important;
        color: var(--primary-dark) !important;
        font-weight: 700;
        border-left: 4px solid var(--primary) !important;
    }
    .list-group-item:hover:not(.active-filter-item) {
        background-color: var(--gray-100);
    }
</style>
