<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can access cart.');
        }
        $cart = Cart::where('customer_id', $customer->id)->with('items.product.inventory')->first();
        $deliveryFee = (float) Setting::getValue('delivery_fee', 2.00);
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 50.00);

        return view('customer.cart.index', compact('cart', 'deliveryFee', 'freeDeliveryMin'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $isAjax = $request->ajax() || $request->expectsJson();
        $customer = Auth::user()->customer;
        if (! $customer) {
            if ($isAjax) {
                return response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Please register as a customer first.']);
            }

            return redirect()->route('login')->with('error', 'Please register as a customer first.');
        }

        try {
            $product = Product::with('inventory')->findOrFail($request->product_id);

            if (! $product->inventory || $product->inventory->qty_in_stock < $request->quantity) {
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => Lang::has('messages.insufficient_stock') ? __('messages.insufficient_stock') : 'Insufficient stock available!']);
                }

                return back()->with('error', 'Insufficient stock available!');
            }

            $cart = Cart::firstOrCreate(['customer_id' => $customer->id]);

            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {
                $newQty = $existingItem->quantity + $request->quantity;
                if ($product->inventory->qty_in_stock < $newQty) {
                    if ($isAjax) {
                        return response()->json(['success' => false, 'message' => Lang::has('messages.insufficient_stock') ? __('messages.insufficient_stock') : 'Insufficient stock available!']);
                    }

                    return back()->with('error', 'Insufficient stock available!');
                }
                $existingItem->update([
                    'quantity' => $newQty,
                    'subtotal' => $newQty * $product->price,
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $request->quantity * $product->price,
                ]);
            }
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Failed to add item to cart.']);
            }

            return back()->with('error', 'Failed to add item to cart.');
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index')->with('success', 'Product added! Complete your order.');
        }

        if ($isAjax) {
            $cartCount = $cart ? $cart->items()->count() : 0;

            return response()->json([
                'success' => true,
                'message' => Lang::has('messages.product_added_to_cart') ? __('messages.product_added_to_cart') : 'Product added to cart!',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::with('product.inventory', 'cart')->findOrFail($id);

        $customer = Auth::user()->customer;
        if (! $customer || ! $item->cart || $item->cart->customer_id !== $customer->id) {
            abort(403);
        }

        try {
            $product = Product::with('inventory')->findOrFail($item->product_id);

            if (! $product->inventory || $product->inventory->qty_in_stock < $request->quantity) {
                return back()->with('error', 'Insufficient stock!');
            }

            $item->update([
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $item->unit_price,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update cart.');
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        try {
            $item = CartItem::with('cart')->findOrFail($id);

            $customer = Auth::user()->customer;
            if (! $customer || ! $item->cart || $item->cart->customer_id !== $customer->id) {
                abort(403);
            }

            $item->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove item from cart.');
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can access cart.');
        }

        try {
            $cart = Cart::where('customer_id', $customer->id)->first();

            if ($cart) {
                $cart->items()->delete();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cart.');
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $customer = Auth::user()->customer;
        if (! $customer) {
            return back()->with('error', 'Please log in as customer first.');
        }

        try {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (! $coupon) {
                return back()->with('error', 'Invalid or expired coupon code.');
            }

            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return back()->with('error', 'This coupon usage limit has been reached.');
            }

            $cart = Cart::where('customer_id', $customer->id)->first();
            $subtotal = $cart ? $cart->items->sum('subtotal') : 0;

            if ($subtotal < $coupon->min_order_amount) {
                return back()->with('error', 'Minimum order amount of $'.number_format($coupon->min_order_amount, 2).' is required to use this coupon.');
            }

            $discount = 0;
            if ($coupon->discount_type === 'percentage') {
                $discount = ($subtotal * $coupon->discount_value) / 100;
                if ($coupon->max_discount !== null && $discount > $coupon->max_discount) {
                    $discount = $coupon->max_discount;
                }
            } else {
                $discount = $coupon->discount_value;
            }

            session([
                'applied_coupon_id' => $coupon->id,
                'coupon_code' => $coupon->code,
                'coupon_discount' => (float) $discount,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to apply coupon.');
        }

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget(['applied_coupon_id', 'coupon_code', 'coupon_discount']);

        return back()->with('success', 'Coupon removed successfully.');
    }
}
