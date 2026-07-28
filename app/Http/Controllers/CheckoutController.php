<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Models\Setting;
use App\Services\NotificationService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can access checkout.');
        }
        $cart = Cart::where('customer_id', $customer->id)->with('items.product.inventory')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $deliveryFee = (float) Setting::getValue('delivery_fee', 2.00);
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 50.00);

        $couponId = session('applied_coupon_id');
        $coupon = null;
        $discount = 0;
        if ($couponId) {
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                $subtotal = $cart->items->sum('subtotal');
                if ($subtotal >= $coupon->min_order_amount) {
                    if ($coupon->discount_type === 'percentage') {
                        $discount = ($subtotal * $coupon->discount_value) / 100;
                        if ($coupon->max_discount !== null && $discount > $coupon->max_discount) {
                            $discount = $coupon->max_discount;
                        }
                    } else {
                        $discount = $coupon->discount_value;
                    }
                    session(['coupon_discount' => (float) $discount]);
                } else {
                    session()->forget(['applied_coupon_id', 'coupon_code', 'coupon_discount']);
                    $discount = 0;
                    $coupon = null;
                }
            }
        }

        $addresses = Address::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        $defaultAddress = $addresses->firstWhere('is_default', true);

        return view('customer.checkout.index', compact('cart', 'customer', 'deliveryFee', 'freeDeliveryMin', 'addresses', 'defaultAddress', 'coupon', 'discount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'nullable|string|max:1000',
            'saved_address_id' => 'nullable|exists:addresses,id',
            'payment_method' => 'required|in:COD,ABA Payroll,Wing,Bakong',
            'note' => 'nullable|string|max:500',
            'checkout_latitude' => 'nullable|numeric|between:-90,90',
            'checkout_longitude' => 'nullable|numeric|between:-180,180',
            'slip_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'transaction_ref' => 'nullable|string|max:100',
        ]);

        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can place orders.');
        }

        $deliveryAddress = $request->delivery_address;
        $orderLat = null;
        $orderLng = null;
        $orderAddressId = null;

        if ($request->filled('saved_address_id')) {
            $savedAddr = Address::where('id', $request->saved_address_id)
                ->where('user_id', Auth::id())
                ->first();
            if ($savedAddr) {
                $deliveryAddress = $savedAddr->full_address;
                $orderLat = $savedAddr->latitude;
                $orderLng = $savedAddr->longitude;
                $orderAddressId = $savedAddr->id;
                if (! $request->note && $savedAddr->delivery_note) {
                    $request->merge(['note' => $savedAddr->delivery_note]);
                }
            }
        } else {
            if ($request->filled('checkout_latitude') && $request->filled('checkout_longitude')) {
                $orderLat = (float) $request->checkout_latitude;
                $orderLng = (float) $request->checkout_longitude;
            }
        }

        if (empty($deliveryAddress)) {
            return back()->with('error', 'Please provide a delivery address!');
        }

        $cart = Cart::where('customer_id', $customer->id)->with('items.product.inventory')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        try {
            foreach ($cart->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->first();

                if (! $inventory || $inventory->qty_in_stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->product->product_name}!");
                }
            }

            $subtotal = $cart->items->sum('subtotal');
            $deliveryFee = (float) Setting::getValue('delivery_fee', 2.00);
            $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 50.00);
            $appliedDeliveryFee = $subtotal >= $freeDeliveryMin ? 0 : $deliveryFee;

            $couponId = session('applied_coupon_id');
            $discount = 0;
            $coupon = null;
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon && $coupon->status === 'active' && $subtotal >= $coupon->min_order_amount) {
                    if ($coupon->discount_type === 'percentage') {
                        $discount = ($subtotal * $coupon->discount_value) / 100;
                        if ($coupon->max_discount !== null && $discount > $coupon->max_discount) {
                            $discount = $coupon->max_discount;
                        }
                    } else {
                        $discount = $coupon->discount_value;
                    }

                    if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                        throw new \Exception('This coupon usage limit has been reached.');
                    }

                    $coupon->increment('used_count');
                } else {
                    $coupon = null;
                }
            }

            $totalAmount = max(0, ($subtotal - $discount) + $appliedDeliveryFee);

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'COD' ? 'unpaid' : 'pending',
                'order_status' => 'pending',
                'delivery_address' => $deliveryAddress,
                'latitude' => $orderLat,
                'longitude' => $orderLng,
                'address_id' => $orderAddressId,
                'note' => $request->note,
                'coupon_id' => $coupon ? $coupon->id : null,
                'discount_amount' => $discount,
            ]);

            foreach ($cart->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->first();

                if (! $inventory) {
                    throw new \Exception("Inventory not found for product ID {$item->product_id}.");
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ]);

                $inventory->qty_in_stock -= $item->quantity;
                $inventory->last_updated = now();
                $inventory->save();

                StockMovementService::stockOut(
                    $item->product_id,
                    $item->quantity,
                    'order',
                    $order->id
                );
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'COD' ? 'unpaid' : 'pending',
                'payment_date' => $request->payment_method === 'COD' ? null : now(),
            ]);

            if ($request->payment_method !== 'COD') {
                $slipData = null;
                if ($request->hasFile('slip_image')) {
                    $file = $request->file('slip_image');
                    $mimeType = $file->getMimeType();
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $slipData = 'data:'.$mimeType.';base64,'.$base64;
                }

                PaymentVerification::create([
                    'payment_id' => $payment->id,
                    'slip_image' => $slipData,
                    'transaction_ref' => $request->transaction_ref,
                    'status' => 'pending',
                ]);
            }

            Delivery::create([
                'order_id' => $order->id,
                'delivery_status' => 'assigned',
                'tracking_no' => 'TRK-'.strtoupper(uniqid()),
            ]);

            $cart->items()->delete();
            session()->forget(['applied_coupon_id', 'coupon_code', 'coupon_discount']);

            NotificationService::notifyAdmins(
                'New Order #'.$order->id,
                "A new order of \${$totalAmount} has been placed by {$customer->user->name}.",
                'new_order',
                $order->id
            );

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
