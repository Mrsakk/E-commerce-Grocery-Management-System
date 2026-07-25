<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        return view('admin.exports.index');
    }

    public function exportOrders(Request $request): StreamedResponse
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        $query = Order::with('customer.user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->latest()->cursor();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Date', 'Total', 'Payment Method', 'Payment Status', 'Order Status', 'Address']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->customer->user->name ?? 'N/A',
                    $order->customer->user->email ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i'),
                    $order->total_amount,
                    strtoupper($order->payment_method),
                    $order->payment_status,
                    $order->order_status,
                    $order->delivery_address,
                ]);
            }
            fclose($handle);
        }, 'orders_'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportCustomers(): StreamedResponse
    {
        $customers = Customer::with('user')->latest()->cursor();

        return response()->streamDownload(function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'City', 'Total Orders', 'Status', 'Joined']);
            foreach ($customers as $customer) {
                fputcsv($handle, [
                    $customer->id,
                    $customer->user->name ?? 'N/A',
                    $customer->user->email ?? 'N/A',
                    $customer->user->phone ?? 'N/A',
                    $customer->city ?? 'N/A',
                    $customer->orders()->count(),
                    $customer->user->status ?? 'active',
                    $customer->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, 'customers_'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportProducts(): StreamedResponse
    {
        $products = Product::with('category', 'inventory')->latest()->cursor();

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Category', 'Price', 'Unit', 'Brand', 'Stock', 'Reorder Level', 'Status']);
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->product_name,
                    $product->category->category_name ?? 'N/A',
                    $product->price,
                    $product->unit,
                    $product->brand,
                    $product->inventory->qty_in_stock ?? 0,
                    $product->inventory->reorder_level ?? 0,
                    $product->status ? 'Active' : 'Inactive',
                ]);
            }
            fclose($handle);
        }, 'products_'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportPayments(Request $request): StreamedResponse
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'method' => 'nullable|string',
        ]);

        $query = Payment::with('order.customer.user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->latest()->cursor();

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Payment ID', 'Order ID', 'Customer', 'Amount', 'Method', 'Status', 'Transaction Ref', 'Date']);
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->id,
                    $payment->order_id,
                    $payment->order->customer->user->name ?? 'N/A',
                    $payment->amount,
                    strtoupper($payment->payment_method),
                    $payment->payment_status,
                    $payment->transaction_ref ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        }, 'payments_'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $request->validate([
            'type' => 'required|in:daily,monthly',
        ]);

        if ($request->type === 'daily') {
            $data = Order::whereDate('created_at', today())
                ->selectRaw('order_status, COUNT(*) as count, SUM(total_amount) as total')
                ->groupBy('order_status')
                ->get();

            ActivityLogger::logAction('exported', 'Report', null, 'Exported daily sales report');

            return response()->streamDownload(function () use ($data) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Status', 'Orders', 'Revenue']);
                foreach ($data as $row) {
                    fputcsv($handle, [$row->order_status, $row->count, $row->total]);
                }
                fclose($handle);
            }, 'daily_report_'.today()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
        }

        $data = Order::whereMonth('created_at', today()->month)
            ->whereYear('created_at', today()->year)
            ->selectRaw('order_status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('order_status')
            ->get();

        ActivityLogger::logAction('exported', 'Report', null, 'Exported monthly sales report');

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Status', 'Orders', 'Revenue']);
            foreach ($data as $row) {
                fputcsv($handle, [$row->order_status, $row->count, $row->total]);
            }
            fclose($handle);
        }, 'monthly_report_'.now()->format('Y-m').'.csv', ['Content-Type' => 'text/csv']);
    }
}
