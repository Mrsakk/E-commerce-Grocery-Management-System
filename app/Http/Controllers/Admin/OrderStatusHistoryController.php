<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;

class OrderStatusHistoryController extends Controller
{
    public function index(Order $order)
    {
        $histories = OrderStatusHistory::where('order_id', $order->id)
            ->with('changedBy')
            ->latest()
            ->get();
        return view('admin.order_status_histories.index', compact('order', 'histories'));
    }
}
