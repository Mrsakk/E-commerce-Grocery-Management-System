<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can access orders.');
        }
        $orders = Order::where('customer_id', $customer->id)
            ->with('details.product', 'payment', 'delivery')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can access orders.');
        }
        $order = Order::where('customer_id', $customer->id)
            ->with('details.product', 'payment', 'delivery.staff')
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $customer = Auth::user()->customer;
        if (! $customer) {
            return redirect()->route('home')->with('error', 'Only customers can cancel orders.');
        }

        try {
            $order = Order::where('customer_id', $customer->id)
                ->where('order_status', 'pending')
                ->findOrFail($id);

            OrderStatusService::change($order, 'cancelled', $request->cancel_reason);

            return redirect()->route('customer.orders.index')->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }
}
