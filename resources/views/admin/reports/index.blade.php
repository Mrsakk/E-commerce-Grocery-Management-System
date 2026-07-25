@extends('layouts.admin')
@section('title', 'Reports & Analytics')
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--gray-900);"><i class="bi bi-file-earmark-bar-graph text-success me-2"></i>Reports & Analytics</h4>
    <button onclick="window.print()" class="btn btn-sm btn-outline-primary shadow-sm"><i class="bi bi-printer"></i> Export / Print Report</button>
</div>

{{-- Top Row KPIs --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%); color: white;">
            <div class="stat-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-white-50">Today's Sales Revenue</div>
                    <div class="stat-number">${{ number_format($dailySales, 2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
            <div class="stat-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-white-50">This Month's Sales Volume</div>
                    <div class="stat-number">${{ number_format($monthlySales, 2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Visual Analytics Section --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-graph-up text-success fs-5"></i> Revenue Trend ({{ now()->year }})</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-pie-chart text-info fs-5"></i> Order Volume by Status</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="ordersPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-wallet2 text-warning fs-5"></i> Payment Methods Revenue Share</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="paymentsPolarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detailed Data Grids --}}
<div class="row g-4 mb-4">
    {{-- Best Sellers --}}
    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header">
                <span class="fw-bold"><i class="bi bi-trophy text-warning me-1"></i> Best Selling Products</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->product_name }}</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">{{ $item->total_qty }} units</span></td>
                                    <td class="fw-bold">${{ number_format($item->total_sales, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">No sales data recorded yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Low stock --}}
    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header">
                <span class="fw-bold text-danger"><i class="bi bi-exclamation-triangle text-danger me-1"></i> Low Stock Alerts</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr><th>Product</th><th>Stock Level</th><th>Reorder Level</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStock as $inv)
                                <tr class="table-warning bg-opacity-10">
                                    <td class="fw-semibold text-dark">{{ $inv->product->product_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-danger rounded-pill px-2.5 py-1 fw-bold">{{ $inv->qty_in_stock }}</span></td>
                                    <td><span class="text-muted">{{ $inv->reorder_level }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-success py-4"><i class="bi bi-check-circle-fill me-1"></i> All items are well stocked</td></tr>
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
        // 1. Revenue Line Chart
        const revCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const monthlyData = @json($monthlyData);
        
        const monthNames = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const revLabels = monthlyData.map(m => monthNames[m.month] + ' ' + m.year);
        const revTotals = monthlyData.map(m => m.total);

        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: revLabels.length > 0 ? revLabels : ['No Data'],
                datasets: [{
                    label: 'Delivered Revenue ($)',
                    data: revTotals.length > 0 ? revTotals : [0],
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    }
                }
            }
        });

        // 2. Orders Distribution Pie Chart
        const ordersCtx = document.getElementById('ordersPieChart').getContext('2d');
        const orderSummary = @json($orderSummary);
        const orderLabels = orderSummary.map(o => o.order_status.charAt(0).toUpperCase() + o.order_status.slice(1));
        const orderCounts = orderSummary.map(o => o.total);

        new Chart(ordersCtx, {
            type: 'doughnut',
            data: {
                labels: orderLabels.length > 0 ? orderLabels : ['No Orders'],
                datasets: [{
                    data: orderCounts.length > 0 ? orderCounts : [0],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#64748b', '#ec4899', '#8b5cf6'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, font: { family: 'Inter', size: 11 } }
                    }
                },
                cutout: '65%'
            }
        });

        // 3. Payments Method Polar Chart
        const payCtx = document.getElementById('paymentsPolarChart').getContext('2d');
        const paymentSummary = @json($paymentSummary);
        const payLabels = paymentSummary.map(p => p.payment_method.toUpperCase());
        const payAmounts = paymentSummary.map(p => p.amount);

        new Chart(payCtx, {
            type: 'polarArea',
            data: {
                labels: payLabels.length > 0 ? payLabels : ['No Payments'],
                datasets: [{
                    data: payAmounts.length > 0 ? payAmounts : [0],
                    backgroundColor: ['rgba(59, 130, 246, 0.7)', 'rgba(239, 68, 68, 0.7)', 'rgba(245, 158, 11, 0.7)', 'rgba(16, 185, 129, 0.7)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, font: { family: 'Inter', size: 11 } }
                    }
                }
            }
        });
    });
</script>

<style>
    @media print {
        body { background: white !important; color: black !important; }
        .sidebar, .topbar, .btn { display: none !important; }
        .content { margin-left: 0 !important; padding: 0 !important; }
        .page-content { padding: 0 !important; }
        .card-custom { border: none !important; box-shadow: none !important; }
    }
</style>

@endsection
