@extends('layouts.admin')
@section('title', 'Export & Backup')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-download text-success"></i> Export & Backup</h4>
        <p>Export data to CSV files for reporting and backup</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:var(--primary-50);color:var(--primary);width:56px;height:56px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                    <i class="bi bi-cart-check"></i>
                </div>
                <h5 class="fw-bold">Orders</h5>
                <p class="text-muted" style="font-size:0.85rem;">Export all orders with customer details, amounts, and statuses.</p>
                <form action="{{ route('admin.exports.orders') }}" method="GET">
                    <div class="mb-2">
                        <input type="date" name="date_from" class="form-control form-control-sm" placeholder="From">
                    </div>
                    <div class="mb-2">
                        <input type="date" name="date_to" class="form-control form-control-sm" placeholder="To">
                    </div>
                    <div class="mb-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            @foreach(['pending','confirmed','packing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-download me-1"></i> Export Orders CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:var(--blue-50);color:var(--blue-500);width:56px;height:56px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="fw-bold">Customers</h5>
                <p class="text-muted" style="font-size:0.85rem;">Export customer list with contact info, order counts, and account status.</p>
                <form action="{{ route('admin.exports.customers') }}" method="GET">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-download me-1"></i> Export Customers CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:var(--amber-50);color:var(--amber-500);width:56px;height:56px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                    <i class="bi bi-box"></i>
                </div>
                <h5 class="fw-bold">Products</h5>
                <p class="text-muted" style="font-size:0.85rem;">Export product catalog with categories, prices, and stock levels.</p>
                <form action="{{ route('admin.exports.products') }}" method="GET">
                    <button type="submit" class="btn btn-warning w-100"><i class="bi bi-download me-1"></i> Export Products CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:var(--red-50);color:var(--red-500);width:56px;height:56px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                    <i class="bi bi-credit-card"></i>
                </div>
                <h5 class="fw-bold">Payments</h5>
                <p class="text-muted" style="font-size:0.85rem;">Export payment records with amounts, methods, and statuses.</p>
                <form action="{{ route('admin.exports.payments') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="date" name="date_from" class="form-control form-control-sm" placeholder="From">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date_to" class="form-control form-control-sm" placeholder="To">
                    </div>
                    <div class="col-md-4">
                        <select name="method" class="form-select form-select-sm">
                            <option value="">All Methods</option>
                            <option value="cod">Cash on Delivery</option>
                            <option value="aba">ABA Pay</option>
                            <option value="wing">Wing Pay</option>
                            <option value="bakong">Bakong KHQR</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger w-100"><i class="bi bi-download me-1"></i> Export Payments CSV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon mx-auto mb-3" style="background:#f0fdf4;color:#10b981;width:56px;height:56px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <h5 class="fw-bold">Sales Reports</h5>
                <p class="text-muted" style="font-size:0.85rem;">Export daily or monthly sales summary with order statuses and revenue.</p>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.exports.report') }}" method="GET" class="flex-fill">
                        <input type="hidden" name="type" value="daily">
                        <button type="submit" class="btn btn-outline-success w-100"><i class="bi bi-calendar-day me-1"></i> Daily Report</button>
                    </form>
                    <form action="{{ route('admin.exports.report') }}" method="GET" class="flex-fill">
                        <input type="hidden" name="type" value="monthly">
                        <button type="submit" class="btn btn-outline-success w-100"><i class="bi bi-calendar-month me-1"></i> Monthly Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
