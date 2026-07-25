@extends('layouts.customer')
@section('title', __('messages.my_addresses'))
@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.index') }}">{{ __('messages.my_profile') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.my_addresses') }}</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-geo-alt-fill text-success me-2"></i>{{ __('messages.my_addresses') }}
        </h4>
        <button class="btn btn-success fw-bold px-4 rounded-pill btn-gradient" onclick="openAddModal()">
            <i class="bi bi-plus-lg me-1"></i> {{ __('messages.add_new_address') }}
        </button>
    </div>

    @if($addresses->isEmpty())
        <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: var(--radius-md); border: 1px solid var(--card-border);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-50); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="bi bi-map text-success" style="font-size: 2rem;"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">{{ __('messages.no_addresses_title') }}</h5>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">{{ __('messages.no_addresses_desc') }}</p>
            <button class="btn btn-success fw-bold px-4 rounded-pill btn-gradient" onclick="openAddModal()">
                <i class="bi bi-plus-lg me-1"></i> {{ __('messages.add_new_address') }}
            </button>
        </div>
    @else
        <div class="row g-3">
            @foreach($addresses as $addr)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 address-card" style="border-radius: var(--radius-md); border: 1px solid {{ $addr->is_default ? 'var(--primary) !important' : 'var(--card-border)' }} !important; transition: all 0.2s ease;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $addr->is_default ? 'var(--primary)' : 'var(--primary-50)' }}; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi {{ $addr->label_icon }} {{ $addr->is_default ? 'text-white' : 'text-success' }}" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ __('messages.' . $addr->label) }}</span>
                                        @if($addr->is_default)
                                            <span class="badge bg-success ms-2 px-2 py-1" style="font-size: 0.65rem; border-radius: 50px;">{{ __('messages.default_label') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow" style="border-radius: var(--radius-sm); min-width: 200px;">
                                        <li>
                                            <button class="dropdown-item py-2 d-flex align-items-center gap-2" onclick="openEditModal({{ $addr->toJson() }})">
                                                <i class="bi bi-pencil-square text-primary"></i> {{ __('messages.edit_address') }}
                                            </button>
                                        </li>
                                        @if(!$addr->is_default)
                                            <li>
                                                <form action="{{ route('addresses.set_default', $addr->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 w-100">
                                                        <i class="bi bi-star text-warning"></i> {{ __('messages.set_default') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        @if($addr->has_coordinates)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ $addr->google_maps_link }}" target="_blank">
                                                    <i class="bi bi-box-arrow-up-right text-success"></i> {{ __('messages.open_in_google_maps') }}
                                                </a>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_address') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger w-100">
                                                    <i class="bi bi-trash3"></i> {{ __('messages.delete_address') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if($addr->recipient_name)
                                <p class="fw-semibold text-dark mb-1" style="font-size: 0.88rem;">
                                    <i class="bi bi-person text-muted me-1"></i> {{ $addr->recipient_name }}
                                </p>
                            @endif
                            @if($addr->phone)
                                <p class="text-muted mb-1" style="font-size: 0.82rem;">
                                    <i class="bi bi-telephone text-muted me-1"></i> {{ $addr->phone }}
                                </p>
                            @endif

                            <div class="mb-2" style="font-size: 0.85rem; color: var(--gray-600);">
                                <i class="bi bi-geo-alt text-success me-1"></i>
                                {{ $addr->full_address ?: __('messages.no_address_details') }}
                            </div>

                            @if($addr->landmark)
                                <div class="mb-2" style="font-size: 0.82rem; color: var(--gray-600);">
                                    <i class="bi bi-pin-map text-warning me-1"></i> {{ $addr->landmark }}
                                </div>
                            @endif

                            @if($addr->delivery_note)
                                <div class="mt-2 p-2 rounded-3" style="background: #fffbeb; font-size: 0.8rem; color: #92400e;">
                                    <i class="bi bi-chat-left-text me-1"></i> {{ $addr->delivery_note }}
                                </div>
                            @endif

                            @if($addr->has_coordinates)
                                <div class="mt-2 pt-2 border-top" style="font-size: 0.75rem;">
                                    <span class="text-muted"><i class="bi bi-crosshair me-1"></i>{{ number_format($addr->latitude, 5) }}, {{ number_format($addr->longitude, 5) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Address Modal (Add / Edit) --}}
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius: var(--radius-md); overflow: hidden;">
            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #064e3b 0%, #10b981 100%);">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="addressModalLabel">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span id="modalTitleText">{{ __('messages.add_new_address') }}</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <form id="addressForm" method="POST">
                    @csrf
                    <div id="formMethodField"></div>

                    <div class="row g-0">
                        {{-- Left: Map --}}
                        <div class="col-lg-7">
                            <div style="position: relative;">
                                <div id="addressMap" style="height: 450px; width: 100%; z-index: 1;"></div>

                                {{-- Map controls overlay --}}
                                <div style="position: absolute; top: 12px; left: 12px; right: 12px; z-index: 1000;">
                                    <div class="bg-white rounded-3 shadow-sm p-2 d-flex gap-2" style="backdrop-filter: blur(8px);">
                                        <input type="text" id="mapSearchInput" class="form-control form-control-sm border-0 bg-light rounded-2" placeholder="{{ __('messages.search_location_placeholder') }}" style="font-size: 0.85rem; flex: 1;">
                                        <button type="button" id="searchBtn" class="btn btn-success btn-sm rounded-2 px-3" onclick="searchLocation()">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button type="button" id="locateBtn" class="btn btn-outline-primary btn-sm rounded-2 px-3" onclick="useCurrentLocation()" title="{{ __('messages.use_current_location') }}">
                                            <i class="bi bi-crosshair"></i>
                                        </button>
                                    </div>
                                    <div id="searchResults" class="bg-white rounded-3 shadow-sm mt-1 d-none" style="max-height: 200px; overflow-y: auto;"></div>
                                </div>

                                {{-- Map instruction tooltip --}}
                                <div id="mapTooltip" style="position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); z-index: 1000; background: rgba(15, 23, 42, 0.85); color: white; padding: 8px 16px; border-radius: 50px; font-size: 0.78rem; font-weight: 600; backdrop-filter: blur(4px); pointer-events: none; white-space: nowrap;">
                                    <i class="bi bi-info-circle me-1"></i> {{ __('messages.map_instruction') }}
                                </div>
                            </div>

                            {{-- Hidden lat/lng fields --}}
                            <input type="hidden" name="latitude" id="latitudeInput">
                            <input type="hidden" name="longitude" id="longitudeInput">
                        </div>

                        {{-- Right: Form Fields --}}
                        <div class="col-lg-5">
                            <div class="p-4">
                                {{-- Label --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('messages.address_label') }} <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="form-check label-radio">
                                            <input class="form-check-input" type="radio" name="label" value="home" id="labelHome" checked>
                                            <label class="form-check-label fw-semibold" for="labelHome">
                                                <i class="bi bi-house-door-fill text-success me-1"></i> {{ __('messages.home') }}
                                            </label>
                                        </div>
                                        <div class="form-check label-radio">
                                            <input class="form-check-input" type="radio" name="label" value="work" id="labelWork">
                                            <label class="form-check-label fw-semibold" for="labelWork">
                                                <i class="bi bi-building text-primary me-1"></i> {{ __('messages.work') }}
                                            </label>
                                        </div>
                                        <div class="form-check label-radio">
                                            <input class="form-check-input" type="radio" name="label" value="other" id="labelOther">
                                            <label class="form-check-label fw-semibold" for="labelOther">
                                                <i class="bi bi-geo-alt-fill text-warning me-1"></i> {{ __('messages.other') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('label') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                {{-- Recipient Name + Phone --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-7">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.recipient_name') }}</label>
                                        <input type="text" name="recipient_name" class="form-control form-control-sm rounded-2" id="recipientName" placeholder="{{ __('messages.recipient_name_placeholder') }}">
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.phone_number') }}</label>
                                        <input type="text" name="phone" class="form-control form-control-sm rounded-2" id="phoneInput" placeholder="{{ __('messages.phone_placeholder') }}">
                                    </div>
                                </div>

                                {{-- City + District --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.city') }}</label>
                                        <input type="text" name="city" class="form-control form-control-sm rounded-2" id="cityInput" placeholder="{{ __('messages.city_placeholder') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.district') }}</label>
                                        <input type="text" name="district" class="form-control form-control-sm rounded-2" id="districtInput" placeholder="{{ __('messages.district_placeholder') }}">
                                    </div>
                                </div>

                                {{-- Commune + Street --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.commune') }}</label>
                                        <input type="text" name="commune" class="form-control form-control-sm rounded-2" id="communeInput" placeholder="{{ __('messages.commune_placeholder') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.street') }}</label>
                                        <input type="text" name="street" class="form-control form-control-sm rounded-2" id="streetInput" placeholder="{{ __('messages.street_placeholder') }}">
                                    </div>
                                </div>

                                {{-- Landmark --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('messages.landmark') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                                    <input type="text" name="landmark" class="form-control form-control-sm rounded-2" id="landmarkInput" placeholder="{{ __('messages.landmark_placeholder') }}">
                                </div>

                                {{-- Delivery Note --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('messages.delivery_note') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                                    <textarea name="delivery_note" class="form-control form-control-sm rounded-2" id="deliveryNoteInput" rows="2" placeholder="{{ __('messages.delivery_note_placeholder') }}"></textarea>
                                </div>

                                {{-- Default --}}
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultCheck">
                                        <label class="form-check-label fw-semibold text-muted small" for="isDefaultCheck">{{ __('messages.set_as_default') }}</label>
                                    </div>
                                </div>

                                {{-- Coordinates display --}}
                                <div id="coordsDisplay" class="mb-3 p-2 rounded-2 bg-light d-none" style="font-size: 0.78rem;">
                                    <i class="bi bi-crosshair text-success me-1"></i>
                                    <span class="text-muted">{{ __('messages.selected_location') }}:</span>
                                    <span class="fw-bold text-dark" id="coordsText">—</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between">
                <button type="button" class="btn btn-light fw-semibold px-4 rounded-pill" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="submit" form="addressForm" class="btn btn-success fw-bold px-4 rounded-pill btn-gradient">
                    <i class="bi bi-check-lg me-1"></i> <span id="submitBtnText">{{ __('messages.save_address') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let map, marker;
    const PHNOM_PENH = [11.5564, 104.9282];
    let searchResultsList = [];
    let reverseGeocodeTimer = null;

    function debounce(fn, ms) {
        let t;
        return function() { clearTimeout(t); t = setTimeout(() => fn.apply(this, arguments), ms); };
    }

    function initMap(lat, lng, zoom) {
        if (map) { map.remove(); }
        map = L.map('addressMap', { zoomControl: false }).setView([lat, lng], zoom || 13);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });

        updateCoordinates(lat, lng);
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitudeInput').value = lat.toFixed(7);
        document.getElementById('longitudeInput').value = lng.toFixed(7);
        document.getElementById('coordsDisplay').classList.remove('d-none');
        document.getElementById('coordsText').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
        clearTimeout(reverseGeocodeTimer);
        reverseGeocodeTimer = setTimeout(function() { reverseGeocode(lat, lng); }, 800);
    }

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(r => r.json())
            .then(data => {
                if (data.address) {
                    const a = data.address;
                    if (!document.getElementById('cityInput').value && (a.city || a.town || a.state)) {
                        document.getElementById('cityInput').value = a.city || a.town || a.state || '';
                    }
                    if (!document.getElementById('districtInput').value && (a.city_district || a.suburb || a.district)) {
                        document.getElementById('districtInput').value = a.city_district || a.suburb || a.district || '';
                    }
                    if (!document.getElementById('communeInput').value && (a.neighbourhood || a.quarter || a.village)) {
                        document.getElementById('communeInput').value = a.neighbourhood || a.quarter || a.village || '';
                    }
                    if (!document.getElementById('streetInput').value && (a.road || a.pedestrian)) {
                        document.getElementById('streetInput').value = a.road || a.pedestrian || '';
                    }
                }
            })
            .catch(() => {});
    }

    function searchLocation() {
        const query = document.getElementById('mapSearchInput').value.trim();
        if (!query) return;

        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '<div class="p-3 text-center text-muted small"><i class="bi bi-hourglass-split me-1"></i> {{ __("messages.searching") }}...</div>';
        resultsDiv.classList.remove('d-none');

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=kh`)
            .then(r => r.json())
            .then(results => {
                searchResultsList = results;
                if (results.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-center text-muted small">{{ __("messages.no_results_found") }}</div>';
                    return;
                }
                resultsDiv.innerHTML = results.map((r, i) => `
                    <div class="p-2 px-3 border-bottom search-result-item" style="cursor:pointer; font-size:0.85rem;" onclick="selectSearchResult(${i})" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='white'">
                        <i class="bi bi-geo-alt text-success me-1"></i> ${r.display_name}
                    </div>
                `).join('');
            })
            .catch(() => {
                resultsDiv.innerHTML = '<div class="p-3 text-center text-danger small">{{ __("messages.search_error") }}</div>';
            });
    }

    function selectSearchResult(index) {
        const result = searchResultsList[index];
        if (!result) return;
        const lat = parseFloat(result.lat);
        const lng = parseFloat(result.lon);
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 16);
        updateCoordinates(lat, lng);
        document.getElementById('searchResults').classList.add('d-none');
        document.getElementById('mapSearchInput').value = result.display_name;
    }

    function useCurrentLocation() {
        const btn = document.getElementById('locateBtn');
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
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 16);
                updateCoordinates(lat, lng);
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

    function openAddModal() {
        const form = document.getElementById('addressForm');
        form.reset();
        document.getElementById('formMethodField').innerHTML = '';
        form.action = '{{ route("addresses.store") }}';
        document.getElementById('modalTitleText').textContent = '{{ __("messages.add_new_address") }}';
        document.getElementById('submitBtnText').textContent = '{{ __("messages.save_address") }}';
        document.getElementById('labelHome').checked = true;
        document.getElementById('latitudeInput').value = '';
        document.getElementById('longitudeInput').value = '';
        document.getElementById('coordsDisplay').classList.add('d-none');
        document.getElementById('landmarkInput').value = '';

        const modal = new bootstrap.Modal(document.getElementById('addressModal'));
        modal.show();

        setTimeout(function() {
            initMap(PHNOM_PENH[0], PHNOM_PENH[1], 13);
        }, 300);
    }

    function openEditModal(address) {
        const form = document.getElementById('addressForm');
        form.action = '/addresses/' + address.id;
        document.getElementById('formMethodField').innerHTML = '@method("PUT")';
        document.getElementById('modalTitleText').textContent = '{{ __("messages.edit_address") }}';
        document.getElementById('submitBtnText').textContent = '{{ __("messages.update_address") }}';

        document.getElementById('recipientName').value = address.recipient_name || '';
        document.getElementById('phoneInput').value = address.phone || '';
        document.getElementById('cityInput').value = address.city || '';
        document.getElementById('districtInput').value = address.district || '';
        document.getElementById('communeInput').value = address.commune || '';
        document.getElementById('streetInput').value = address.street || '';
        document.getElementById('landmarkInput').value = address.landmark || '';
        document.getElementById('deliveryNoteInput').value = address.delivery_note || '';
        document.getElementById('isDefaultCheck').checked = address.is_default;

        const labelMap = { home: 'labelHome', work: 'labelWork', other: 'labelOther' };
        if (labelMap[address.label]) {
            document.getElementById(labelMap[address.label]).checked = true;
        }

        const modal = new bootstrap.Modal(document.getElementById('addressModal'));
        modal.show();

        setTimeout(function() {
            if (address.latitude && address.longitude) {
                initMap(address.latitude, address.longitude, 16);
            } else {
                initMap(PHNOM_PENH[0], PHNOM_PENH[1], 13);
            }
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLocation();
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#searchResults') && !e.target.closest('#mapSearchInput')) {
                document.getElementById('searchResults').classList.add('d-none');
            }
        });
    });
</script>

<style>
    .address-card { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .address-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06) !important; }
    .label-radio { border: 1.5px solid var(--gray-200); border-radius: 10px; padding: 6px 12px; transition: all 0.2s ease; cursor: pointer; background: white; }
    .label-radio:has(.form-check-input:checked) { border-color: var(--primary); background: var(--primary-50); }
    .label-radio .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
    .label-radio:hover { border-color: var(--primary-light); }
    .form-control:focus, .form-select:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12) !important; }
    .btn-gradient { background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); transition: all 0.2s ease; }
    .btn-gradient:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25); }
    .leaflet-container { font-family: inherit; }
    #addressMap .leaflet-control-zoom a { background: white; color: var(--gray-900); border: none; width: 32px; height: 32px; line-height: 32px; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    #addressMap .leaflet-control-zoom a:hover { background: var(--primary-50); color: var(--primary); }
    .search-result-item:last-child { border-bottom: none !important; }
    @media (max-width: 991.98px) {
        #addressMap { height: 300px !important; }
    }
</style>
@endsection
