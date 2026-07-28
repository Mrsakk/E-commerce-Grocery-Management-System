@extends('layouts.admin')
@section('title', __('messages.dashboard'))
@section('content')

{{-- Welcome Bar --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-4 bg-white rounded-3 shadow-sm border" style="border-color: var(--gray-200);">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--gray-900);">{{ __('messages.welcome_back_admin', ['name' => Auth::user()->name]) }}</h4>
        <p class="text-muted mb-0" style="font-size: 0.88rem;">{{ now()->format('l, F d, Y') }} · {{ __('messages.store_ops_glance') }}</p>
    </div>
    <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success shadow-sm"><i class="bi bi-plus-lg"></i> {{ __('messages.new_product') }}</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary shadow-sm"><i class="bi bi-eye"></i> {{ __('messages.view_orders') }}</a>
    </div>
</div>

{{-- Stat Cards Row 1 --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); color: white;">
            <div class="stat-body d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label text-white-50">{{ __('messages.total_products') }}</div>
                    <div class="stat-number">{{ $totalProducts }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            </div>
            <div class="px-4 pb-3" style="background: rgba(0,0,0,0.08);"><a href="{{ route('admin.products.index') }}" class="text-white small text-decoration-none fw-medium d-flex align-items-center gap-1">{{ __('messages.manage_catalog') }} <i class="bi bi-arrow-right"></i></a></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
            <div class="stat-body d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label text-white-50">{{ __('messages.total_orders') }}</div>
                    <div class="stat-number">{{ $totalOrders }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-cart-check"></i></div>
            </div>
            <div class="px-4 pb-3" style="background: rgba(0,0,0,0.08);"><a href="{{ route('admin.orders.index') }}" class="text-white small text-decoration-none fw-medium d-flex align-items-center gap-1">{{ __('messages.view_orders') }} <i class="bi bi-arrow-right"></i></a></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #78350f 0%, #d97706 100%); color: white;">
            <div class="stat-body d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label text-white-50">{{ __('messages.total_customers') }}</div>
                    <div class="stat-number">{{ $totalCustomers }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
            <div class="px-4 pb-3" style="background: rgba(0,0,0,0.08);"><a href="{{ route('admin.customers.index') }}" class="text-white small text-decoration-none fw-medium d-flex align-items-center gap-1">{{ __('messages.customers') }} <i class="bi bi-arrow-right"></i></a></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #1e293b 0%, #475569 100%); color: white;">
            <div class="stat-body d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label text-white-50">{{ __('messages.total_revenue') }}</div>
                    <div class="stat-number">៛{{ number_format($totalRevenue, 2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            </div>
            <div class="px-4 pb-3" style="background: rgba(0,0,0,0.08);"><a href="{{ route('admin.reports.index') }}" class="text-white small text-decoration-none fw-medium d-flex align-items-center gap-1">{{ __('messages.detailed_analytics') }} <i class="bi bi-arrow-right"></i></a></div>
        </div>
    </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 h-100 bg-white" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: var(--primary-50); color: var(--primary);"><i class="bi bi-cash-stack fs-4"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">{{ __('messages.todays_sales') }}</div>
                    <div class="fs-4 fw-extrabold" style="color: var(--gray-900); font-weight: 800;">៛{{ number_format($todaySales, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 h-100 bg-white" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #eff6ff; color: #3b82f6;"><i class="bi bi-calendar-check fs-4"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">{{ __('messages.orders_today') }}</div>
                    <div class="fs-4 fw-extrabold" style="color: var(--gray-900); font-weight: 800;">{{ $todayOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 h-100 bg-white" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #fef2f2; color: #ef4444;"><i class="bi bi-clock-history fs-4"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">{{ __('messages.pending_orders') }}</div>
                    <div class="fs-4 fw-extrabold" style="color: var(--gray-900); font-weight: 800;">{{ $pendingOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 h-100 bg-white" style="border-radius: var(--radius-md);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #fffbeb; color: #f59e0b;"><i class="bi bi-credit-card fs-4"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">{{ __('messages.pending_payments') }}</div>
                    <div class="fs-4 fw-extrabold" style="color: var(--gray-900); font-weight: 800;">{{ $pendingPayments }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Section --}}
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-bar-chart-line text-success fs-5"></i> {{ __('messages.monthly_sales_insights') }} ({{ now()->year }})</span>
                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">{{ __('messages.active') }}</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-pie-chart text-info fs-5"></i> {{ __('messages.orders_distribution') }}</span>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <div style="position: relative; height: 230px; width: 100%;" class="mb-3">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="row g-2 mt-2 text-center chart-legend-row" style="font-size: 0.8rem;">
                    <div class="col-3">
                        <div class="fw-bold text-success">{{ $deliveredOrders }}</div>
                        <span class="text-muted">{{ __('messages.delivered') }}</span>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-info">{{ $shippedOrders }}</div>
                        <span class="text-muted">{{ __('messages.shipped') }}</span>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-warning">{{ $processingOrders }}</div>
                        <span class="text-muted">{{ __('messages.processing') }}</span>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-danger">{{ $cancelledOrders }}</div>
                        <span class="text-muted">{{ __('messages.cancelled') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tables: Recent Orders & Stock Movements --}}
<div class="row g-4 mb-4">
    {{-- Left: Recent Orders --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-clock-history text-primary fs-5"></i> {{ __('messages.recent_orders') }}</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>#</th><th>{{ __('messages.customer') }}</th><th>{{ __('messages.amount') }}</th><th>{{ __('messages.status') }}</th><th class="d-none-mobile">{{ __('messages.date') }}</th></tr>
                        </thead>
                        <tbody>
                            @php 
                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-info text-white',
                                    'processing' => 'bg-primary text-white',
                                    'packing' => 'bg-primary text-white',
                                    'shipped' => 'bg-secondary text-white',
                                    'delivered' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white'
                                ]; 
                            @endphp
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><span class="fw-bold" style="color: var(--gray-600);">#{{ $order->id }}</span></td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer?->user?->name ?? 'Guest User' }}</div>
                                        <small class="text-muted">{{ $order->customer?->user?->phone ?? 'No phone' }}</small>
                                    </td>
                                    <td class="fw-bold text-dark">៛{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge-status {{ $statusColors[$order->order_status] ?? 'bg-secondary text-white' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="d-none-mobile"><span class="text-muted" style="font-size:0.8rem;">{{ $order->created_at->format('d/m/Y') }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('messages.no_orders_found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Best Sellers --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-trophy text-warning fs-5"></i> {{ __('messages.best_sellers') }}</span>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.catalog') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>{{ __('messages.rank') }}</th><th>{{ __('messages.product') }}</th><th class="text-end">{{ __('messages.qty_sold') }}</th></tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $i => $item)
                                <tr>
                                    <td>
                                        <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" 
                                              style="width:24px; height:24px; background: {{ $i == 0 ? '#fef3c7' : ($i == 1 ? '#f1f5f9' : '#fafaf9') }}; color: {{ $i == 0 ? '#b45309' : ($i == 1 ? '#475569' : '#78716c') }};">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->product_name }}</div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold px-3 py-1">{{ $item->total_qty }} units</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">{{ __('messages.no_sales_found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stock Movements & Low Stock Alerts --}}
<div class="row g-4 mb-4">
    {{-- Low Stock Alerts (Takes priority if products are low) --}}
    <div class="col-lg-7">
        <div class="card card-custom border-danger h-100">
            <div class="card-header text-danger fw-bold" style="background-color: #fef2f2; border-bottom: 1px solid #fecaca;">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill fs-5"></i> {{ __('messages.low_stock_alert') }} ({{ $lowStockCount }} {{ __('messages.items') }})</span>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-outline-danger">{{ __('messages.inventory') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 290px; overflow-y: auto;">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>{{ __('messages.product') }}</th><th>{{ __('messages.in_stock') }}</th><th>{{ __('messages.limit') }}</th><th>{{ __('messages.status') }}</th><th class="text-end">{{ __('messages.actions') }}</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $inv)
                                <tr class="table-danger bg-opacity-10">
                                    <td class="fw-semibold">{{ $inv->product?->product_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-danger rounded-pill px-2.5">{{ $inv->qty_in_stock }}</span></td>
                                    <td><span class="text-muted fs-xs">{{ $inv->reorder_level }}</span></td>
                                    <td><span class="badge-status bg-danger text-white">{{ __('messages.critical') }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.inventory.edit', $inv->id) }}" class="btn btn-sm btn-warning py-1 px-2.5" title="Quick Stock Update"><i class="bi bi-plus-circle"></i> {{ __('messages.restock') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-success fw-medium">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ __('messages.well_stocked') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Movements --}}
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-arrow-left-right text-muted fs-5"></i> {{ __('messages.recent_stock_movements') }}</span>
                <a href="{{ route('admin.stock_movements.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.history') }}</a>
            </div>
            <div class="card-body p-0" style="max-height: 290px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>{{ __('messages.product') }}</th><th>{{ __('messages.type') }}</th><th>{{ __('messages.quantity') }}</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentStockMovements as $m)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 160px;" title="{{ $m->product?->product_name ?? '' }}">{{ $m->product?->product_name ?? 'N/A' }}</div>
                                        <small class="text-muted" style="font-size:0.75rem;">by {{ $m->user?->name ?? 'System' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $m->type == 'stock_in' ? 'bg-success text-white' : ($m->type == 'stock_out' ? 'bg-danger text-white' : 'bg-warning text-white') }}">
                                            {{ $m->type == 'stock_in' ? 'In' : 'Out' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold {{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">{{ __('messages.no_movements_found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sales Chart Config
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const monthlySalesData = @json($monthlySales);
        
        const monthNames = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const labels = monthlySalesData.map(m => monthNames[m.month] || m.month);
        const dataValues = monthlySalesData.map(m => m.total);

        // Gradient Background for Line Chart
        let salesGradient = salesCtx.createLinearGradient(0, 0, 0, 300);
        salesGradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        salesGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['No Data'],
                datasets: [{
                    label: 'Delivered Revenue (៛)',
                    data: dataValues.length > 0 ? dataValues : [0],
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: salesGradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ៛' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        border: {
                            dash: [5, 5]
                        },
                        grid: {
                            color: '#e2e8f0'
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            color: '#64748b',
                            callback: function(value) {
                                return '៛' + value;
                            }
                        }
                    }
                }
            }
        });

        // Order Status Distribution Chart Config
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Delivered', 'Shipped', 'Processing/Packing', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $deliveredOrders }},
                        {{ $shippedOrders }},
                        {{ $processingOrders }},
                        {{ $cancelledOrders }}
                    ],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        cornerRadius: 6
                    }
                },
                cutout: '72%'
            }
        });
    });
</script>

@endsection