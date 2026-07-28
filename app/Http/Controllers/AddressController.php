<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|in:home,work,other',
            'recipient_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'landmark' => 'nullable|string|max:255',
            'delivery_note' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            $isDefault = $request->boolean('is_default', false);

            if ($isDefault) {
                Address::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            $address = Address::create([
                'user_id' => Auth::id(),
                'label' => $request->label,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'city' => $request->city,
                'district' => $request->district,
                'commune' => $request->commune,
                'street' => $request->street,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'landmark' => $request->landmark,
                'delivery_note' => $request->delivery_note,
                'is_default' => $isDefault,
            ]);

            if (Auth::user()->addresses()->count() === 1) {
                $address->update(['is_default' => true]);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add address.');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully!');
    }

    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $addresses = Address::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.addresses.index', compact('addresses', 'address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'label' => 'required|in:home,work,other',
            'recipient_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'landmark' => 'nullable|string|max:255',
            'delivery_note' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            $isDefault = $request->boolean('is_default', false);

            if ($isDefault) {
                Address::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            $address->update([
                'label' => $request->label,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'city' => $request->city,
                'district' => $request->district,
                'commune' => $request->commune,
                'street' => $request->street,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'landmark' => $request->landmark,
                'delivery_note' => $request->delivery_note,
                'is_default' => $isDefault,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update address.');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully!');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $latest = Address::where('user_id', Auth::id())->latest()->first();
                if ($latest) {
                    $latest->update(['is_default' => true]);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete address.');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Address deleted successfully!');
    }

    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to set default address.');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Default address updated!');
    }
}
