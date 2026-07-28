<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        if (! $customer) {
            return redirect()->route('products.index')->with('error', 'Please complete your profile first.');
        }
        $wishlists = Wishlist::where('customer_id', $customer->id)
            ->with('product.category', 'product.inventory')
            ->latest()
            ->get();

        return view('customer.wishlist.index', compact('wishlists'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $customer = auth()->user()->customer;
        if (! $customer) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'redirect' => route('products.index'), 'message' => 'Please complete your profile first.']);
            }

            return redirect()->route('products.index')->with('error', 'Please complete your profile first.');
        }

        try {
            $wishlist = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                $wishlistCount = Wishlist::where('customer_id', $customer->id)->count();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'in_wishlist' => false,
                        'wishlist_count' => $wishlistCount,
                        'message' => __('messages.removed_from_wishlist') ?? 'Product removed from wishlist.',
                    ]);
                }

                return back()->with('success', 'Product removed from wishlist.');
            } else {
                Wishlist::create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->product_id,
                ]);
                $wishlistCount = Wishlist::where('customer_id', $customer->id)->count();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'in_wishlist' => true,
                        'wishlist_count' => $wishlistCount,
                        'message' => __('messages.added_to_wishlist') ?? 'Product added to wishlist!',
                    ]);
                }

                return back()->with('success', 'Product added to wishlist!');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update wishlist.']);
            }

            return back()->with('error', 'Failed to update wishlist.');
        }
    }

    public function remove(Request $request, $id)
    {
        $customer = auth()->user()->customer;
        if (! $customer) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please log in first.']);
            }

            return back()->with('error', 'Please log in first.');
        }

        try {
            $deleted = Wishlist::where('customer_id', $customer->id)->where('id', $id)->delete();

            if (! $deleted) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Wishlist item not found.']);
                }

                return back()->with('error', 'Wishlist item not found.');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to remove from wishlist.']);
            }

            return back()->with('error', 'Failed to remove from wishlist.');
        }

        if ($request->ajax()) {
            $wishlistCount = Wishlist::where('customer_id', $customer->id)->count();

            return response()->json([
                'success' => true,
                'wishlist_count' => $wishlistCount,
                'message' => __('messages.removed_from_wishlist') ?? 'Product removed from wishlist.',
            ]);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }
}
