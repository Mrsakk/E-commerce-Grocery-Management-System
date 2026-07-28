@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-clock-history text-primary"></i> Activity Logs</h4>
        <p>Audit trail of all admin actions</p>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Action</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">All Actions</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Model</label>
                <select name="model_type" class="form-select form-select-sm">
                    <option value="">All Models</option>
                    <option value="Product" {{ request('model_type') == 'Product' ? 'selected' : '' }}>Product</option>
                    <option value="Order" {{ request('model_type') == 'Order' ? 'selected' : '' }}>Order</option>
                    <option value="Payment" {{ request('model_type') == 'Payment' ? 'selected' : '' }}>Payment</option>
                    <option value="Category" {{ request('model_type') == 'Category' ? 'selected' : '' }}>Category</option>
                    <option value="Supplier" {{ request('model_type') == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                    <option value="Inventory" {{ request('model_type') == 'Inventory' ? 'selected' : '' }}>Inventory</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Audit Trail</div>
        <span class="text-muted small">{{ $logs->count() }} entries</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height:600px;overflow-y:auto;">
            <table class="table table-custom">
                <thead class="sticky-top" style="background:var(--gray-50);">
                    <tr>
                        <th class="d-none-mobile">Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th class="d-none d-md-table-cell">Model</th>
                        <th class="d-none d-md-table-cell">Description</th>
                        <th class="d-none-mobile">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="d-none-mobile"><span class="text-muted" style="font-size:0.82rem;">{{ $log->created_at->format('d/m/Y H:i') }}</span></td>
                            <td><span class="fw-semibold">{{ $log->user?->name ?? 'System' }}</span></td>
                            <td>
                                <span class="badge-status bg-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'info' : ($log->action == 'deleted' ? 'danger' : 'warning')) }} text-white">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ $log->model_type }} #{{ $log->model_id }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ Str::limit($log->description, 80) }}</span></td>
                            <td class="d-none-mobile"><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history d-block"></i>
                                    <h5>No Activity Logs</h5>
                                    <p>Admin actions will be logged here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($logs, 'links') && $logs->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
