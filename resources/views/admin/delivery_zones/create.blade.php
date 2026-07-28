@extends('layouts.admin')
@section('title', isset($zone) ? 'Edit Delivery Zone' : 'Add Delivery Zone')
@section('content')
@php $zone = $zone ?? null; @endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-geo-alt text-danger"></i> {{ isset($zone) ? 'Edit' : 'Add' }} Delivery Zone</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Define a delivery coverage area</p>
    </div>
    <a href="{{ route('admin.delivery-zones.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-body">
                <form action="{{ isset($zone) ? route('admin.delivery-zones.update', $zone->id) : route('admin.delivery-zones.store') }}" method="POST">
                    @csrf
                    @if(isset($zone)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Zone Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $zone->name ?? '') }}" required placeholder="e.g. Phnom Penh Central">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Zone description...">{{ old('description', $zone->description ?? '') }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Center Latitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="center_lat" class="form-control @error('center_lat') is-invalid @enderror"
                                   value="{{ old('center_lat', $zone->center_lat ?? '11.5564') }}" required>
                            @error('center_lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Center Longitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="center_lng" class="form-control @error('center_lng') is-invalid @enderror"
                                   value="{{ old('center_lng', $zone->center_lng ?? '104.9282') }}" required>
                            @error('center_lng') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Radius (km) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="radius_km" class="form-control @error('radius_km') is-invalid @enderror"
                                   value="{{ old('radius_km', $zone->radius_km ?? '5') }}" required min="0.1">
                            @error('radius_km') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Delivery Fee ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="delivery_fee" class="form-control @error('delivery_fee') is-invalid @enderror"
                                   value="{{ old('delivery_fee', $zone->delivery_fee ?? '2.00') }}" required min="0">
                            @error('delivery_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $zone->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Active Zone</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i> {{ isset($zone) ? 'Update' : 'Create' }} Zone</button>
                        <a href="{{ route('admin.delivery-zones.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-header"><i class="bi bi-map me-2"></i> Zone Preview</div>
            <div class="card-body p-2">
                <div id="zonePreviewMap" style="height: 350px; border-radius: var(--radius-sm);"></div>
                <div class="text-muted text-center mt-2" style="font-size:0.75rem;">Click on map to update coordinates</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = parseFloat(document.querySelector('[name=center_lat]').value) || 11.5564;
    var lng = parseFloat(document.querySelector('[name=center_lng]').value) || 104.9282;
    var radius = parseFloat(document.querySelector('[name=radius_km]').value) || 5;

    var map = L.map('zonePreviewMap', { zoomControl: true, attributionControl: false }).setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    var circle = L.circle([lat, lng], { radius: radius * 1000, color: '#10b981', fillColor: '#10b981', fillOpacity: 0.15, weight: 2 }).addTo(map);

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.querySelector('[name=center_lat]').value = e.latlng.lat.toFixed(6);
        document.querySelector('[name=center_lng]').value = e.latlng.lng.toFixed(6);
        updateCircle();
    });

    marker.on('dragend', function() {
        var pos = marker.getLatLng();
        document.querySelector('[name=center_lat]').value = pos.lat.toFixed(6);
        document.querySelector('[name=center_lng]').value = pos.lng.toFixed(6);
        updateCircle();
    });

    document.querySelector('[name=radius_km]').addEventListener('input', updateCircle);

    function updateCircle() {
        var lat = parseFloat(document.querySelector('[name=center_lat]').value);
        var lng = parseFloat(document.querySelector('[name=center_lng]').value);
        var radius = parseFloat(document.querySelector('[name=radius_km]').value) || 5;
        circle.setLatLng([lat, lng]);
        circle.setRadius(radius * 1000);
        map.setView([lat, lng], map.getZoom());
    }

    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endsection
