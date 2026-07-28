@extends('layouts.admin')
@section('title', 'Delivery Zones')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-geo-alt text-danger"></i> Delivery Zone Management</h4>
        <p>Manage delivery coverage areas and zones</p>
    </div>
    <a href="{{ route('admin.delivery-zones.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Zone
    </a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body p-2">
        <div id="zonesMap" style="height: 400px; border-radius: var(--radius-md);"></div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Delivery Zones</div>
        <span class="text-muted small">{{ $zones->total() }} zones</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="d-none-mobile">#</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Description</th>
                        <th class="d-none-mobile">Radius</th>
                        <th>Delivery Fee</th>
                        <th class="d-none-mobile">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zones as $zone)
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">#{{ $zone->id }}</span></td>
                            <td class="fw-semibold">{{ $zone->name }}</td>
                            <td class="d-none d-md-table-cell"><span class="text-muted" style="font-size:0.85rem;">{{ Str::limit($zone->description, 50) }}</span></td>
                            <td class="d-none-mobile">{{ $zone->radius_km }} km</td>
                            <td class="fw-bold" style="color:var(--primary);">${{ number_format($zone->delivery_fee, 2) }}</td>
                            <td class="d-none-mobile">
                                <form action="{{ route('admin.delivery-zones.toggle_status', $zone->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="badge rounded-pill px-2 py-1 border-0 {{ $zone->is_active ? 'bg-success' : 'bg-secondary' }}" style="cursor:pointer;">
                                        {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.delivery-zones.edit', $zone->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.delivery-zones.destroy', $zone->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this zone?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-geo-alt d-block"></i>
                                    <h5>No Delivery Zones</h5>
                                    <p>Create delivery zones to define coverage areas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($zones->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $zones->links() }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('zonesMap', { zoomControl: true, attributionControl: false }).setView([11.5564, 104.9282], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    @foreach($zones as $zone)
        @if($zone->center_lat && $zone->center_lng)
            var circle_{{ $zone->id }} = L.circle([{{ $zone->center_lat }}, {{ $zone->center_lng }}], {
                radius: {{ $zone->radius_km * 1000 }},
                color: '{{ $zone->is_active ? "#10b981" : "#94a3b8" }}',
                fillColor: '{{ $zone->is_active ? "#10b981" : "#94a3b8" }}',
                fillOpacity: 0.15,
                weight: 2
            }).addTo(map).bindPopup('<strong>{{ addslashes($zone->name) }}</strong><br>Radius: {{ $zone->radius_km }}km<br>Fee: ${{ number_format($zone->delivery_fee, 2) }}');
        @endif
    @endforeach

    setTimeout(function(){ map.invalidateSize(); }, 200);
});
</script>
@endsection
