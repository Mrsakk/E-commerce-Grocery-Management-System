<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;

class NotificationService
{
    public static function send($userId, $title, $message, $type, $referenceType = null, $referenceId = null)
    {
        return AppNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    public static function notifyAdmins($title, $message, $referenceType = null, $referenceId = null)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::send($admin->id, $title, $message, 'admin_notification', $referenceType, $referenceId);
        }
    }

    public static function notifyDeliveryStaff($staffId, $title, $message, $referenceType = null, $referenceId = null)
    {
        return self::send($staffId, $title, $message, 'delivery_assigned', $referenceType, $referenceId);
    }

    public static function notifyCustomer($userId, $title, $message, $referenceType = null, $referenceId = null)
    {
        return self::send($userId, $title, $message, 'customer_notification', $referenceType, $referenceId);
    }

    public static function lowStockAlert($productName, $qty)
    {
        $title = 'Low Stock Alert';
        $message = "Product \"{$productName}\" is low on stock. Only {$qty} left.";
        self::notifyAdmins($title, $message, 'low_stock', null);
    }
}
