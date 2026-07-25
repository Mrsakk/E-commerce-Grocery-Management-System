@extends('layouts.customer')
@section('title', __('messages.checkout'))
@section('content')
@php
    $subtotal = $cart->items->sum('subtotal');
    $appliedDeliveryFee = $subtotal >= $freeDeliveryMin ? 0 : $deliveryFee;
    $total = $subtotal + $appliedDeliveryFee;
@endphp

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.checkout') }}</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-credit-card text-success me-2"></i>{{ __('messages.checkout_process') }}</h4>

    {{-- Step Indicator --}}
    <div class="step-indicator">
        <div class="step-item"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><span class="step-label done">{{ __('messages.cart') }}</span></div>
        <div class="step-line done"></div>
        <div class="step-item"><div class="step-circle active">2</div><span class="step-label active">{{ __('messages.checkout') }}</span></div>
        <div class="step-line"></div>
        <div class="step-item"><div class="step-circle pending">3</div><span class="step-label pending">{{ __('messages.confirmation') }}</span></div>
    </div>

    <div class="row g-4">
        {{-- Forms Column --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                    <i class="bi bi-truck text-success me-2"></i>{{ __('messages.delivery_info') }}
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
                        @csrf
                        
                        @if($addresses->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">{{ __('messages.saved_addresses') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                            <div class="row g-2">
                                @foreach($addresses as $addr)
                                    <div class="col-12">
                                        <label class="saved-address-card border rounded-3 p-3 d-flex align-items-start gap-3 position-relative" style="cursor:pointer; transition:all 0.2s; border-color: {{ $addr->is_default ? 'var(--primary)' : 'var(--gray-200)' }}; background: {{ $addr->is_default ? 'var(--primary-50)' : 'white' }};">
                                            <input class="form-check-input mt-1 saved-addr-radio" type="radio" name="saved_address_id" value="{{ $addr->id }}" data-address='@json($addr)' {{ $loop->first ? 'checked' : '' }}>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <i class="bi {{ $addr->label_icon }} text-success" style="font-size: 0.9rem;"></i>
                                                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ __('messages.' . $addr->label) }}</span>
                                                    @if($addr->is_default)
                                                        <span class="badge bg-success px-2 py-0" style="font-size: 0.6rem; border-radius: 50px;">{{ __('messages.default_label') }}</span>
                                                    @endif
                                                    @if($addr->recipient_name)
                                                        <span class="text-muted" style="font-size: 0.8rem;">- {{ $addr->recipient_name }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted" style="font-size: 0.82rem;">{{ $addr->full_address }}</div>
                                                @if($addr->phone)
                                                    <div class="text-muted" style="font-size: 0.78rem;"><i class="bi bi-telephone me-1"></i>{{ $addr->phone }}</div>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="saved_address_id" value="" id="useNewAddress" {{ $addresses->isEmpty() ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-muted small" for="useNewAddress">{{ __('messages.use_new_address') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mini Map Preview --}}
                        <div id="addressMiniMapWrap" class="mb-3 d-none">
                            <label class="form-label fw-bold text-dark small"><i class="bi bi-map text-success me-1"></i>{{ __('messages.delivery_location') }}</label>
                            <div id="checkoutMiniMap" style="height: 200px; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); z-index: 1;"></div>
                            <div id="mapCoords" class="text-muted mt-1" style="font-size: 0.75rem;"></div>
                        </div>
                        @endif
                        
                        <div id="manualAddressFields" @if($addresses->isNotEmpty()) class="d-none" @endif>
                            {{-- Interactive Map --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small"><i class="bi bi-map text-success me-1"></i>{{ __('messages.pin_delivery_location') }}</label>
                                <div style="position: relative;">
                                    <div id="checkoutMap" style="height: 320px; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); z-index: 1;"></div>
                                    {{-- Map search + GPS controls --}}
                                    <div style="position: absolute; top: 10px; left: 10px; right: 10px; z-index: 1000;">
                                        <div class="bg-white rounded-3 shadow-sm p-2 d-flex gap-2" style="backdrop-filter: blur(8px);">
                                            <input type="text" id="checkoutSearchInput" class="form-control form-control-sm border-0 bg-light rounded-2" placeholder="{{ __('messages.search_location_placeholder') }}" style="font-size: 0.85rem; flex: 1;">
                                            <button type="button" class="btn btn-success btn-sm rounded-2 px-3" onclick="checkoutSearchLocation()">
                                                <i class="bi bi-search"></i>
                                            </button>
                                            <button type="button" id="checkoutLocateBtn" class="btn btn-outline-primary btn-sm rounded-2 px-3" onclick="checkoutUseCurrentLocation()" title="{{ __('messages.use_current_location') }}">
                                                <i class="bi bi-crosshair"></i>
                                            </button>
                                        </div>
                                        <div id="checkoutSearchResults" class="bg-white rounded-3 shadow-sm mt-1 d-none" style="max-height: 180px; overflow-y: auto;"></div>
                                    </div>
                                    {{-- Map instruction tooltip --}}
                                    <div id="checkoutMapTooltip" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; background: rgba(15, 23, 42, 0.85); color: white; padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; pointer-events: none; white-space: nowrap;">
                                        <i class="bi bi-info-circle me-1"></i> {{ __('messages.map_instruction') }}
                                    </div>
                                </div>
                                <div id="checkoutCoordsDisplay" class="text-muted mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-crosshair me-1"></i>
                                    <span id="checkoutCoordsText">{{ __('messages.click_map_to_pin') }}</span>
                                </div>
                                @error('checkout_latitude') <span class="text-danger small">{{ $message }}</span> @enderror
                                @error('checkout_longitude') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            {{-- Hidden lat/lng fields --}}
                            <input type="hidden" name="checkout_latitude" id="checkoutLatInput">
                            <input type="hidden" name="checkout_longitude" id="checkoutLngInput">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">{{ __('messages.delivery_address') }} <span class="text-danger">*</span></label>
                                <textarea name="delivery_address" class="form-control" rows="2" placeholder="{{ __('messages.address_placeholder') }}" id="checkoutAddressText" style="font-size:0.9rem;">{{ old('delivery_address', $defaultAddress ? $defaultAddress->full_address : ($customer->address ?? '')) }}</textarea>
                                @error('delivery_address') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">{{ __('messages.city') }}</label>
                                    <input type="text" name="checkout_city" class="form-control bg-light" id="checkoutCityInput" value="{{ $defaultAddress ? $defaultAddress->city : ($customer->city ?? __('messages.phnom_penh_city')) }}" readonly style="font-size:0.9rem;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">{{ __('messages.phone_number') }}</label>
                                    <input type="text" class="form-control bg-light" value="{{ $defaultAddress ? ($defaultAddress->phone ?? '') : (Auth::user()->phone ?? __('messages.phone_na')) }}" readonly style="font-size:0.9rem;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">{{ __('messages.delivery_note') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                            <textarea name="note" class="form-control" rows="2" placeholder="{{ __('messages.note_placeholder') }}" style="font-size:0.9rem;">{{ old('note', $defaultAddress ? $defaultAddress->delivery_note : '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small mb-3">{{ __('messages.select_payment_method') }} <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                @php
                                    $methods = [
                                        'COD' => ['icon' => 'cash-stack', 'label' => 'Cash on Delivery', 'desc' => __('messages.cod_desc')],
                                        'ABA Payroll' => ['icon' => 'bank', 'label' => 'ABA PAY', 'desc' => __('messages.aba_desc')],
                                        'Wing' => ['icon' => 'phone', 'label' => 'Wing Pay', 'desc' => __('messages.wing_desc')],
                                        'Bakong' => ['icon' => 'qr-code-scan', 'label' => 'Bakong KHQR', 'desc' => __('messages.bakong_desc')],
                                    ];
                                @endphp
                                @foreach($methods as $value => $method)
                                    <div class="col-6 col-md-3">
                                        <label class="payment-card-option border rounded-3 p-3 d-block text-center position-relative h-100" style="cursor:pointer; transition:all 0.2s; border-color:var(--gray-200); background:white;">
                                            <input class="form-check-input position-absolute" style="top:8px; right:8px;" type="radio" name="payment_method" value="{{ $value }}" id="pm_{{ str_replace(' ', '', $value) }}" {{ old('payment_method', 'COD') == $value ? 'checked' : '' }}>
                                            <i class="bi bi-{{ $method['icon'] }} fs-2 d-block mb-1.5 text-success"></i>
                                            <span class="d-block fw-bold small text-dark mb-0.5" style="line-height:1.2;">{{ $method['label'] }}</span>
                                            <span class="text-muted" style="font-size:0.68rem; line-height:1;">{{ $method['desc'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('payment_method') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Dynamic QR Scan Box for ABA / Bakong / Wing --}}
                        <div id="digitalPaymentInfo" class="mb-4 d-none p-3 border rounded-3 text-center bg-light" style="border-style:dashed !important;">
                            <h6 class="fw-bold mb-2 text-dark" id="paymentTitle"><i class="bi bi-qr-code text-success me-1"></i>{{ __('messages.scan_qr_to_pay') }}</h6>
                            <p class="text-muted small mb-3">{{ __('messages.scan_qr_desc') }}</p>
                            
                            {{-- Mock QR code --}}
                            <div class="bg-white p-2.5 d-inline-block rounded-3 shadow-sm mb-3">
                                <div style="width:140px; height:140px; border: 1.5px solid var(--gray-200); display:flex; align-items:center; justify-content:center; background:#fafafa;">
                                    <i class="bi bi-qr-code text-dark" style="font-size: 7.5rem;"></i>
                                </div>
                            </div>
                            
                            <div class="text-muted small">
                                <div class="fw-semibold text-dark">FreshMart Grocery Store</div>
                                <div>Account: <span class="fw-bold text-dark">000 123 456</span></div>
                                <div class="text-danger fw-bold small mt-1">Amount to Transfer: ${{ number_format($total, 2) }}</div>
                            </div>
                            
                            {{-- Slip and Reference Inputs --}}
                            <div class="row g-2 mt-3 text-start border-top pt-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark mb-1">{{ __('messages.transaction_ref') ?? 'Transaction Reference (Ref)' }}</label>
                                    <input type="text" name="transaction_ref" class="form-control form-control-sm bg-white" placeholder="e.g. 12345678" style="font-size:0.8rem; height: 36px; border-radius: 6px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark mb-1">{{ __('messages.upload_slip') ?? 'Upload Payment Slip' }}</label>
                                    <input type="file" name="slip_image" class="form-control form-control-sm bg-white" accept="image/*" style="font-size:0.8rem; height: 36px; border-radius: 6px; padding: 4px 8px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: var(--radius-sm); padding: 14px; font-size:1.05rem;">
                            <i class="bi bi-check-circle-fill"></i> {{ __('messages.place_order') }} (${{ number_format($total, 2) }})
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Order Summary Column --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md); border: 1px solid var(--card-border); position: sticky; top: 90px;">
                <div class="card-header bg-white fw-bold py-3" style="border-bottom: 2px solid var(--primary);">
                    <i class="bi bi-receipt text-success me-2"></i>{{ __('messages.my_cart_items') }}
                </div>
                
                <div class="card-body p-4">
                    @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $item->product->product_name }}</span>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</div>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:0.9rem;">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                        @if(!$loop->last)<hr class="my-2 text-muted" style="opacity:0.15;">@endif
                    @endforeach
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between mb-1.5">
                        <span class="text-muted small">{{ __('messages.subtotal') }}</span>
                        <span class="fw-bold text-dark">${{ number_format($cart->items->sum('subtotal'), 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-1.5">
                        <span class="text-muted small">{{ __('messages.delivery_dispatch') }}</span>
                        @if($appliedDeliveryFee == 0)
                            <span class="text-success fw-bold">{{ __('messages.free') }}</span>
                        @else
                            <span class="fw-bold text-dark">${{ number_format($deliveryFee, 2) }}</span>
                        @endif
                    </div>
                    
                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-1.5 text-danger">
                            <span class="small">Coupon ({{ session('coupon_code') }})</span>
                            <span class="fw-bold">-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    
                    @if($appliedDeliveryFee > 0)
                        <small class="text-muted d-block mb-3" style="font-size:0.75rem;">{{ __('messages.free_delivery_over') }} ${{ number_format($freeDeliveryMin, 0) }}</small>
                    @endif
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark fs-6">{{ __('messages.order_total') }}</span>
                        <span class="fw-extrabold text-success fs-5">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #checkoutMap .leaflet-control-zoom a { background: white; color: var(--gray-900, #0f172a); border: none; width: 30px; height: 30px; line-height: 30px; font-size: 0.9rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    #checkoutMap .leaflet-control-zoom a:hover { background: var(--primary-50, #ecfdf5); color: var(--primary, #10b981); }
    #checkoutSearchResults .search-result-item:last-child { border-bottom: none !important; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const savedRadios = document.querySelectorAll('.saved-addr-radio');
        const manualFields = document.getElementById('manualAddressFields');
        const newAddrRadio = document.getElementById('useNewAddress');
        const deliveryAddress = document.querySelector('[name="delivery_address"]');
        const miniMapWrap = document.getElementById('addressMiniMapWrap');
        let miniMap = null;
        let miniMarker = null;

        /* ───── Interactive checkout map ───── */
        const PHNOM_PENH = [11.5564, 104.9282];
        let checkoutInteractiveMap = null;
        let checkoutInteractiveMarker = null;
        let checkoutSearchList = [];
        let checkoutReverseTimer = null;
        let checkoutMapInitialized = false;

        function initCheckoutMap(lat, lng, zoom) {
            if (checkoutInteractiveMap) { checkoutInteractiveMap.remove(); checkoutInteractiveMap = null; checkoutInteractiveMarker = null; }
            checkoutInteractiveMap = L.map('checkoutMap', { zoomControl: false }).setView([lat, lng], zoom || 13);
            L.control.zoom({ position: 'bottomright' }).addTo(checkoutInteractiveMap);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
            }).addTo(checkoutInteractiveMap);
            checkoutInteractiveMarker = L.marker([lat, lng], { draggable: true }).addTo(checkoutInteractiveMap);
            checkoutInteractiveMarker.on('dragend', function(e) {
                var pos = e.target.getLatLng();
                updateCheckoutCoords(pos.lat, pos.lng);
            });
            checkoutInteractiveMap.on('click', function(e) {
                checkoutInteractiveMarker.setLatLng(e.latlng);
                updateCheckoutCoords(e.latlng.lat, e.latlng.lng);
            });
            updateCheckoutCoords(lat, lng);
        }

        function updateCheckoutCoords(lat, lng) {
            document.getElementById('checkoutLatInput').value = lat.toFixed(7);
            document.getElementById('checkoutLngInput').value = lng.toFixed(7);
            document.getElementById('checkoutCoordsDisplay').style.display = '';
            document.getElementById('checkoutCoordsText').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
            clearTimeout(checkoutReverseTimer);
            checkoutReverseTimer = setTimeout(function() { checkoutReverseGeocode(lat, lng); }, 800);
        }

        function checkoutReverseGeocode(lat, lng) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.address) {
                        var a = data.address;
                        var parts = [];
                        if (a.road || a.pedestrian) parts.push(a.road || a.pedestrian);
                        if (a.neighbourhood || a.quarter || a.village) parts.push(a.neighbourhood || a.quarter || a.village);
                        if (a.city_district || a.suburb || a.district) parts.push(a.city_district || a.suburb || a.district);
                        if (a.city || a.town || a.state) parts.push(a.city || a.town || a.state);
                        var addrText = parts.join(', ');
                        if (addrText) {
                            document.getElementById('checkoutAddressText').value = addrText;
                        }
                        var cityEl = document.getElementById('checkoutCityInput');
                        if (cityEl && !cityEl.value) {
                            cityEl.value = a.city || a.town || a.state || '{{ __("messages.phnom_penh_city") }}';
                        }
                    }
                })
                .catch(function() {});
        }

        function checkoutSearchLocation() {
            var query = document.getElementById('checkoutSearchInput').value.trim();
            if (!query) return;
            var resultsDiv = document.getElementById('checkoutSearchResults');
            resultsDiv.innerHTML = '<div class="p-2 text-center text-muted small"><i class="bi bi-hourglass-split me-1"></i> {{ __("messages.searching") }}...</div>';
            resultsDiv.classList.remove('d-none');
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=5&countrycodes=kh')
                .then(function(r) { return r.json(); })
                .then(function(results) {
                    checkoutSearchList = results;
                    if (results.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-2 text-center text-muted small">{{ __("messages.no_results_found") }}</div>';
                        return;
                    }
                    resultsDiv.innerHTML = results.map(function(r, i) {
                        return '<div class="p-2 px-3 border-bottom search-result-item" style="cursor:pointer; font-size:0.82rem;" onclick="checkoutSelectSearchResult(' + i + ')" onmouseover="this.style.background=\'#f0fdf4\'" onmouseout="this.style.background=\'white\'"><i class="bi bi-geo-alt text-success me-1"></i> ' + r.display_name + '</div>';
                    }).join('');
                })
                .catch(function() {
                    resultsDiv.innerHTML = '<div class="p-2 text-center text-danger small">{{ __("messages.search_error") }}</div>';
                });
        }
        window.checkoutSearchLocation = checkoutSearchLocation;

        function checkoutSelectSearchResult(index) {
            var result = checkoutSearchList[index];
            if (!result) return;
            var lat = parseFloat(result.lat);
            var lng = parseFloat(result.lon);
            checkoutInteractiveMarker.setLatLng([lat, lng]);
            checkoutInteractiveMap.setView([lat, lng], 16);
            updateCheckoutCoords(lat, lng);
            document.getElementById('checkoutSearchResults').classList.add('d-none');
            document.getElementById('checkoutSearchInput').value = result.display_name;
        }
        window.checkoutSelectSearchResult = checkoutSelectSearchResult;

        function checkoutUseCurrentLocation() {
            var btn = document.getElementById('checkoutLocateBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btn.disabled = true;
            if (!navigator.geolocation) {
                alert('{{ __("messages.geolocation_not_supported") }}');
                btn.innerHTML = '<i class="bi bi-crosshair"></i>';
                btn.disabled = false;
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    var lat = pos.coords.latitude;
                    var lng = pos.coords.longitude;
                    checkoutInteractiveMarker.setLatLng([lat, lng]);
                    checkoutInteractiveMap.setView([lat, lng], 16);
                    updateCheckoutCoords(lat, lng);
                    btn.innerHTML = '<i class="bi bi-crosshair"></i>';
                    btn.disabled = false;
                },
                function() {
                    alert('{{ __("messages.location_permission_denied") }}');
                    btn.innerHTML = '<i class="bi bi-crosshair"></i>';
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
        window.checkoutUseCurrentLocation = checkoutUseCurrentLocation;

        function ensureCheckoutMapReady() {
            if (!checkoutMapInitialized && checkoutInteractiveMap) {
                setTimeout(function() { checkoutInteractiveMap.invalidateSize(); }, 200);
                checkoutMapInitialized = true;
            }
        }

        /* ───── Address toggle ───── */
        function toggleAddressFields() {
            var selectedNew = newAddrRadio && newAddrRadio.checked;
            if (manualFields) {
                if (selectedNew) {
                    manualFields.classList.remove('d-none');
                    deliveryAddress.setAttribute('required', 'required');
                    if (!checkoutMapInitialized) {
                        setTimeout(function() {
                            initCheckoutMap(PHNOM_PENH[0], PHNOM_PENH[1], 13);
                            checkoutMapInitialized = true;
                        }, 200);
                    } else {
                        ensureCheckoutMapReady();
                    }
                } else {
                    manualFields.classList.add('d-none');
                    deliveryAddress.removeAttribute('required');
                }
            }
            if (miniMapWrap) {
                if (selectedNew) { miniMapWrap.classList.add('d-none'); }
            }
        }

        function showMiniMap(lat, lng, addressText) {
            if (!lat || !lng || !miniMapWrap) return;
            miniMapWrap.classList.remove('d-none');
            var coordDiv = document.getElementById('mapCoords');
            if (coordDiv) coordDiv.textContent = parseFloat(lat).toFixed(6) + ', ' + parseFloat(lng).toFixed(6);

            if (!miniMap) {
                miniMap = L.map('checkoutMiniMap', { zoomControl: true, attributionControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMap);
                setTimeout(function() { miniMap.invalidateSize(); }, 200);
            } else {
                miniMap.setView([lat, lng], 16);
            }

            if (miniMarker) {
                miniMarker.setLatLng([lat, lng]);
            } else {
                miniMarker = L.marker([lat, lng]).addTo(miniMap);
            }
            miniMarker.bindPopup(addressText || '').openPopup();
        }

        /* ───── Form submit ───── */
        var checkoutForm = document.getElementById('checkoutForm');
        checkoutForm.addEventListener('submit', function() {
            var checkedSaved = document.querySelector('.saved-addr-radio:checked');
            if (checkedSaved && checkedSaved.value) {
                var addrData = JSON.parse(checkedSaved.dataset.address);
                var parts = [addrData.street, addrData.commune, addrData.district, addrData.city].filter(Boolean);
                deliveryAddress.value = parts.join(', ');
                document.getElementById('checkoutLatInput').value = addrData.latitude || '';
                document.getElementById('checkoutLngInput').value = addrData.longitude || '';
            } else {
                var manualText = document.getElementById('checkoutAddressText').value.trim();
                deliveryAddress.value = manualText;
            }
        });

        /* ───── Radio change handlers ───── */
        savedRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.saved-address-card').forEach(function(card) {
                    card.style.borderColor = 'var(--gray-200)';
                    card.style.background = 'white';
                });
                if (this.checked) {
                    var parent = this.closest('.saved-address-card');
                    parent.style.borderColor = 'var(--primary)';
                    parent.style.background = 'var(--primary-50)';
                    var addrData = JSON.parse(this.dataset.address);
                    if (addrData && addrData.latitude && addrData.longitude) {
                        var parts = [addrData.street, addrData.commune, addrData.district, addrData.city].filter(Boolean);
                        showMiniMap(addrData.latitude, addrData.longitude, parts.join(', '));
                    } else if (miniMapWrap) {
                        miniMapWrap.classList.add('d-none');
                    }
                }
                toggleAddressFields();
            });
        });

        if (newAddrRadio) {
            newAddrRadio.addEventListener('change', toggleAddressFields);
        }

        if (!savedRadios.length && manualFields) {
            manualFields.classList.remove('d-none');
            setTimeout(function() {
                initCheckoutMap(PHNOM_PENH[0], PHNOM_PENH[1], 13);
                checkoutMapInitialized = true;
            }, 300);
        }

        var initialChecked = document.querySelector('.saved-addr-radio:checked');
        if (initialChecked && initialChecked.value) {
            var addrData = JSON.parse(initialChecked.dataset.address);
            if (addrData && addrData.latitude && addrData.longitude) {
                var parts = [addrData.street, addrData.commune, addrData.district, addrData.city].filter(Boolean);
                setTimeout(function() { showMiniMap(addrData.latitude, addrData.longitude, parts.join(', ')); }, 300);
            }
        }

        document.getElementById('checkoutSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); checkoutSearchLocation(); }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#checkoutSearchResults') && !e.target.closest('#checkoutSearchInput')) {
                document.getElementById('checkoutSearchResults').classList.add('d-none');
            }
        });

        /* ───── Payment method toggle ───── */
        var paymentRadioButtons = document.querySelectorAll('[name="payment_method"]');
        var digitalPayBox = document.getElementById('digitalPaymentInfo');
        var paymentTitle = document.getElementById('paymentTitle');
        var scanQrTitleEn = "Scan {METHOD} QR Code to Pay";
        var scanQrTitleKm = "ស្កែនកូដ QR {METHOD} ដើម្បីទូទាត់";
        var activeLocale = "{{ App::getLocale() }}";

        function updatePaymentUI() {
            paymentRadioButtons.forEach(function(r) {
                var label = r.closest('.payment-card-option');
                if (r.checked) {
                    label.style.borderColor = 'var(--primary)';
                    label.style.background = 'var(--primary-50)';
                    if (r.value !== 'COD') {
                        digitalPayBox.classList.remove('d-none');
                        var methodUpper = r.value.toUpperCase();
                        var titleText = activeLocale === 'km'
                            ? scanQrTitleKm.replace('{METHOD}', methodUpper)
                            : scanQrTitleEn.replace('{METHOD}', methodUpper);
                        paymentTitle.innerHTML = '<i class="bi bi-qr-code text-success me-1"></i>' + titleText;
                    } else {
                        digitalPayBox.classList.add('d-none');
                    }
                } else {
                    label.style.borderColor = 'var(--gray-200)';
                    label.style.background = 'white';
                }
            });
        }

        paymentRadioButtons.forEach(function(el) { el.addEventListener('change', updatePaymentUI); });
        updatePaymentUI();
    });
</script>
@endsection
