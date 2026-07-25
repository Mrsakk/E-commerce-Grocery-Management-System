<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $zones = DeliveryZone::latest()->paginate(10);

        return view('admin.delivery_zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.delivery_zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'center_lat' => 'required|numeric',
            'center_lng' => 'required|numeric',
            'radius_km' => 'required|numeric|min:0.1',
            'delivery_fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        DeliveryZone::create([
            'name' => $request->name,
            'description' => $request->description,
            'center_lat' => $request->center_lat,
            'center_lng' => $request->center_lng,
            'radius_km' => $request->radius_km,
            'delivery_fee' => $request->delivery_fee,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogger::logAction('created', 'DeliveryZone', null,
            "Created delivery zone: {$request->name}");

        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone created.');
    }

    public function edit($id)
    {
        $zone = DeliveryZone::findOrFail($id);

        return view('admin.delivery_zones.edit', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        $zone = DeliveryZone::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'center_lat' => 'required|numeric',
            'center_lng' => 'required|numeric',
            'radius_km' => 'required|numeric|min:0.1',
            'delivery_fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $zone->update([
            'name' => $request->name,
            'description' => $request->description,
            'center_lat' => $request->center_lat,
            'center_lng' => $request->center_lng,
            'radius_km' => $request->radius_km,
            'delivery_fee' => $request->delivery_fee,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogger::logAction('updated', 'DeliveryZone', $zone->id,
            "Updated delivery zone: {$zone->name}");

        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone updated.');
    }

    public function destroy($id)
    {
        $zone = DeliveryZone::findOrFail($id);
        $zoneName = $zone->name;
        $zone->delete();

        ActivityLogger::logAction('deleted', 'DeliveryZone', $id,
            "Deleted delivery zone: {$zoneName}");

        return redirect()->route('admin.delivery-zones.index')->with('success', 'Delivery zone deleted.');
    }

    public function toggleStatus($id)
    {
        $zone = DeliveryZone::findOrFail($id);
        $zone->update(['is_active' => ! $zone->is_active]);

        ActivityLogger::logAction('updated', 'DeliveryZone', $zone->id,
            "Toggled zone {$zone->name} to ".($zone->is_active ? 'active' : 'inactive'));

        return back()->with('success', 'Zone status updated.');
    }
}
