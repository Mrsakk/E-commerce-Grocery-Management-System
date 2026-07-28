<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer.user', 'details.product', 'delivery');
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('customer.user', 'details.product', 'payment', 'delivery.staff')
            ->findOrFail($id);
        $deliveryStaff = User::where('role', 'delivery')->where('status', 'active')->get();

        return view('admin.orders.show', compact('order', 'deliveryStaff'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,packing,shipped,delivered,cancelled',
            'cancel_reason' => 'nullable|string|required_if:order_status,cancelled',
        ]);

        $order = Order::findOrFail($id);
        OrderStatusService::change($order, $request->order_status, $request->cancel_reason);
        ActivityLogger::logAction('updated', 'Order', $order->id, "Order #{$order->id} status changed to {$request->order_status}");

        if ($order->customer && $order->customer->user) {
            NotificationService::notifyCustomer(
                $order->customer->user_id,
                'Order '.ucfirst($request->order_status),
                "Your order #{$order->id} is now {$request->order_status}.",
                'order_status',
                $order->id
            );
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function assignDelivery(Request $request, $id)
    {
        $request->validate([
            'delivery_staff_id' => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($id);
        $delivery = $order->delivery;

        if ($delivery) {
            $delivery->update([
                'delivery_staff_id' => $request->delivery_staff_id,
                'delivery_status' => 'assigned',
            ]);
        } else {
            $delivery = Delivery::create([
                'order_id' => $order->id,
                'delivery_staff_id' => $request->delivery_staff_id,
                'delivery_status' => 'assigned',
                'tracking_no' => 'TRK-'.strtoupper(uniqid()),
            ]);
        }

        $staff = User::find($request->delivery_staff_id);
        if ($staff) {
            NotificationService::notifyDeliveryStaff(
                $staff->id,
                'New Delivery Assigned',
                "Order #{$order->id} has been assigned to you. Please check your deliveries.",
                'delivery_assigned',
                $order->id
            );
        }

        ActivityLogger::logAction('updated', 'Delivery', $delivery?->id, "Assigned delivery staff to order #{$order->id}");

        return back()->with('success', 'Delivery staff assigned successfully.');
    }

    public function printInvoice($id)
    {
        $order = Order::with('customer.user', 'details.product', 'payment', 'delivery.staff', 'coupon')
            ->findOrFail($id);

        return view('admin.invoices.print', compact('order'));
    }
}
