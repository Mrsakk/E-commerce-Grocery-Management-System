<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->where('status', 'active')->count();
        $totalOrders = Order::count();
        $totalRevenue = (float) Order::where('order_status', 'delivered')->sum('total_amount');

        $todaySales = (float) Order::whereDate('created_at', today())->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $pendingPayments = Order::where('payment_status', 'pending')->count();
        $lowStockCount = Inventory::whereColumn('qty_in_stock', '<=', 'reorder_level')->count();

        $lowStockProducts = Inventory::whereColumn('qty_in_stock', '<=', 'reorder_level')
            ->with('product')
            ->get();

        $recentOrders = Order::with('customer.user')
            ->latest()
            ->take(5)
            ->get();

        $recentStockMovements = StockMovement::with('product', 'user')
            ->latest()
            ->take(10)
            ->get();

        $bestSellers = DB::table('order_details')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        $productIds = $bestSellers->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->pluck('product_name', 'id');
        foreach ($bestSellers as $item) {
            $item->product_name = $products[$item->product_id] ?? 'N/A';
        }

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthRaw = $isSqlite ? "strftime('%m', order_date) + 0" : 'MONTH(order_date)';
        $yearRaw = $isSqlite ? "strftime('%Y', order_date) + 0" : 'YEAR(order_date)';

        $monthlySales = Order::select(
            DB::raw("$monthRaw as month"),
            DB::raw("$yearRaw as year"),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('order_status', 'delivered')
            ->whereYear('order_date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get()
            ->map(fn ($item) => [
                'month' => (int) $item->month,
                'total' => (float) $item->total,
            ]);

        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $shippedOrders = Order::where('order_status', 'shipped')->count();
        $processingOrders = Order::whereIn('order_status', ['confirmed', 'packing'])->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalCustomers',
            'totalOrders', 'totalRevenue', 'todaySales', 'todayOrders',
            'pendingOrders', 'pendingPayments', 'lowStockCount',
            'lowStockProducts', 'recentOrders',
            'recentStockMovements', 'bestSellers', 'monthlySales',
            'deliveredOrders', 'shippedOrders', 'processingOrders', 'cancelledOrders'
        ));
    }
}
