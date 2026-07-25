@extends('layouts.admin')
@section('title', 'Edit Delivery Zone')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-geo-alt text-danger"></i> Edit Delivery Zone</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Update delivery coverage area</p>
    </div>
    <a href="{{ route('admin.delivery-zones.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-custom">
            <div class="card-body">
                <form action="{{ route('admin.delivery-zones.update', $zone->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Zone Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $zone->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $zone->description) }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Center Latitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="center_lat" class="form-control" value="{{ old('center_lat', $zone->center_lat) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Center Longitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="center_lng" class="form-control" value="{{ old('center_lng', $zone->center_lng) }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Radius (km) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="radius_km" class="form-control" value="{{ old('radius_km', $zone->radius_km) }}" required min="0.1">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Delivery Fee ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', $zone->delivery_fee) }}" required min="0">
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $zone->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Active Zone</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i> Update Zone</button>
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
