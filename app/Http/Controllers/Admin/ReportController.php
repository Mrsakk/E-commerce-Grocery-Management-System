<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Daily sales
        $dailySales = Order::whereDate('order_date', today())
            ->where('order_status', 'delivered')
            ->sum('total_amount');

        // Monthly sales
        $monthlySales = Order::whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->where('order_status', 'delivered')
            ->sum('total_amount');

        // Best selling products
        $bestSellers = DB::table('order_details')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        $productIds = $bestSellers->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->pluck('product_name', 'id');
        foreach ($bestSellers as $item) {
            $item->product_name = $products[$item->product_id] ?? 'N/A';
        }

        // Low stock products
        $lowStock = Inventory::whereColumn('qty_in_stock', '<=', 'reorder_level')
            ->with('product')
            ->get();

        // Order summary by status
        $orderSummary = Order::select('order_status', DB::raw('count(*) as total'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('order_status')
            ->get();

        // Payment summary
        $paymentSummary = Payment::select('payment_method', DB::raw('count(*) as total'), DB::raw('SUM(amount) as amount'))
            ->groupBy('payment_method')
            ->get();

        // Monthly sales chart data
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $monthRaw = "strftime('%m', order_date) + 0";
            $yearRaw = "strftime('%Y', order_date) + 0";
        } elseif ($driver === 'pgsql') {
            $monthRaw = 'EXTRACT(MONTH FROM order_date)';
            $yearRaw = 'EXTRACT(YEAR FROM order_date)';
        } else {
            $monthRaw = 'MONTH(order_date)';
            $yearRaw = 'YEAR(order_date)';
        }

        $monthlyData = Order::select(
            DB::raw("$monthRaw as month"),
            DB::raw("$yearRaw as year"),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('order_status', 'delivered')
            ->whereYear('order_date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.index', compact(
            'dailySales', 'monthlySales', 'bestSellers',
            'lowStock', 'orderSummary', 'paymentSummary', 'monthlyData'
        ));
    }
}
