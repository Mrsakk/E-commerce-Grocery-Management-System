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

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with('order.customer.user', 'staff');

        if ($request->filled('status')) {
            $query->where('delivery_status', $request->status);
        }
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->whereHas('order.customer.user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $deliveries = $query->latest()->paginate(10);

        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        $ordersWithoutDelivery = Order::whereNotIn('id', Delivery::pluck('order_id'))
            ->whereNotIn('order_status', ['cancelled', 'delivered'])
            ->with('customer.user')
            ->latest()
            ->get();

        $deliveryStaff = User::where('role', 'delivery')->where('status', 'active')->get();

        return view('admin.deliveries.create', compact('ordersWithoutDelivery', 'deliveryStaff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_staff_id' => 'required|exists:users,id',
            'tracking_no' => 'nullable|string|max:100',
        ]);

        $existing = Delivery::where('order_id', $request->order_id)->first();
        if ($existing) {
            return back()->with('error', 'This order already has a delivery assigned.');
        }

        $delivery = Delivery::create([
            'order_id' => $request->order_id,
            'delivery_staff_id' => $request->delivery_staff_id,
            'tracking_no' => $request->tracking_no,
            'delivery_status' => 'assigned',
        ]);

        $order = $delivery->order;
        if ($order && $order->order_status === 'pending') {
            OrderStatusService::change($order, 'confirmed');
        }

        ActivityLogger::logAction('created', 'Delivery', $delivery->id,
            "Assigned delivery to order #{$delivery->order_id} for staff: ".($delivery->staff?->name ?? 'Unknown'));

        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery assigned successfully.');
    }

    public function show($id)
    {
        $delivery = Delivery::with('order.customer.user', 'order.details.product', 'order.address', 'staff')
            ->findOrFail($id);
        $deliveryStaff = User::where('role', 'delivery')->where('status', 'active')->get();

        return view('admin.deliveries.show', compact('delivery', 'deliveryStaff'));
    }

    public function updateTracking(Request $request, $id)
    {
        $request->validate([
            'tracking_no' => 'required|string|max:100',
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->update(['tracking_no' => $request->tracking_no]);

        ActivityLogger::logAction('updated', 'Delivery', $delivery->id,
            "Updated tracking number to {$request->tracking_no} for order #{$delivery->order_id}");

        return back()->with('success', 'Tracking number updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:assigned,on_the_way,delivered,failed',
            'failed_delivery_reason' => 'nullable|string|required_if:delivery_status,failed',
        ]);

        $delivery = Delivery::findOrFail($id);
        $oldStatus = $delivery->delivery_status;
        $delivery->update([
            'delivery_status' => $request->delivery_status,
            'failed_delivery_reason' => $request->failed_delivery_reason ?? null,
        ]);

        if ($request->delivery_status === 'delivered') {
            $delivery->update(['delivery_date' => now()]);
            $order = $delivery->order;
            if ($order && $order->order_status !== 'delivered') {
                OrderStatusService::change($order, 'delivered');
            }
        }

        if ($request->delivery_status === 'failed' && $delivery->order && $delivery->order->customer && $delivery->order->customer->user) {
            NotificationService::notifyCustomer(
                $delivery->order->customer->user_id,
                'Delivery Failed',
                "Your order #{$delivery->order_id} delivery has failed. Reason: {$request->failed_delivery_reason}",
                'delivery_failed',
                $delivery->order_id
            );
        }

        ActivityLogger::logAction('updated', 'Delivery', $delivery->id,
            "Delivery status changed from {$oldStatus} to {$request->delivery_status} for order #{$delivery->order_id}");

        return back()->with('success', 'Delivery status updated.');
    }

    public function updateFailedReason(Request $request, $id)
    {
        $request->validate([
            'failed_delivery_reason' => 'required|string',
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->update([
            'delivery_status' => 'failed',
            'failed_delivery_reason' => $request->failed_delivery_reason,
        ]);

        ActivityLogger::logAction('updated', 'Delivery', $delivery->id,
            "Recorded failed delivery reason for order #{$delivery->order_id}");

        return back()->with('success', 'Failed delivery reason recorded.');
    }
}
