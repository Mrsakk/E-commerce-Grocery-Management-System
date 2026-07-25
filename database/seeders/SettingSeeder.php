<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Store info
            ['key' => 'store_name', 'value' => 'FreshMart Grocery', 'group' => 'general'],
            ['key' => 'store_address', 'value' => '#123, Street 456, Phnom Penh, Cambodia', 'group' => 'general'],
            ['key' => 'store_phone', 'value' => '012 345 678', 'group' => 'general'],
            ['key' => 'store_email', 'value' => 'info@freshmart.com', 'group' => 'general'],

            // Delivery
            ['key' => 'delivery_fee', 'value' => '2.00', 'group' => 'delivery'],
            ['key' => 'free_delivery_min_order', 'value' => '20.00', 'group' => 'delivery'],
            ['key' => 'delivery_slots', 'value' => 'Morning (8AM-12PM),Afternoon (12PM-4PM),Evening (4PM-8PM)', 'group' => 'delivery'],

            // Payment
            ['key' => 'payment_methods', 'value' => 'Cash on Delivery,ABA Payroll,Wing,Bakong', 'group' => 'payment'],
            ['key' => 'aba_account_number', 'value' => '000 123 456', 'group' => 'payment'],
            ['key' => 'aba_account_name', 'value' => 'FreshMart Grocery', 'group' => 'payment'],
            ['key' => 'wing_account_number', 'value' => '098 765 432', 'group' => 'payment'],
            ['key' => 'wing_account_name', 'value' => 'FreshMart Grocery', 'group' => 'payment'],

            // Order
            ['key' => 'max_order_items', 'value' => '50', 'group' => 'order'],
            ['key' => 'min_order_amount', 'value' => '5.00', 'group' => 'order'],
            ['key' => 'auto_cancel_unpaid_hours', 'value' => '24', 'group' => 'order'],

            // Tax
            ['key' => 'tax_rate', 'value' => '0', 'group' => 'tax'],
            ['key' => 'tax_label', 'value' => 'VAT (0%)', 'group' => 'tax'],

            // Notification
            ['key' => 'admin_new_order_notification', 'value' => '1', 'group' => 'notification'],
            ['key' => 'customer_order_status_notification', 'value' => '1', 'group' => 'notification'],
            ['key' => 'low_stock_threshold', 'value' => '10', 'group' => 'notification'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
