<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class OrderStatusService
{
    public static function change(Order $order, $newStatus, $cancelReason = null)
    {
        $oldStatus = $order->order_status;
        $order->update(['order_status' => $newStatus]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => Auth::id(),
            'cancel_reason' => $cancelReason,
        ]);

        if ($newStatus === 'delivered') {
            $order->update(['payment_status' => 'paid']);
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                ]);
            }
            if ($order->delivery) {
                $order->delivery->update([
                    'delivery_status' => 'delivered',
                    'delivery_date' => now(),
                ]);
            }
        }

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->details as $detail) {
                $inventory = $detail->product->inventory;
                if ($inventory) {
                    $inventory->qty_in_stock += $detail->quantity;
                    $inventory->save();
                    StockMovementService::stockIn(
                        $detail->product_id,
                        $detail->quantity,
                        'order',
                        $order->id,
                        'Order cancelled — stock returned'
                    );
                }
            }
        }

        NotificationService::notifyAdmins(
            'Order ' . ucfirst($newStatus),
            "Order #{$order->id} status changed from {$oldStatus} to {$newStatus}.",
            'order_status',
            $order->id
        );

        return true;
    }
}
