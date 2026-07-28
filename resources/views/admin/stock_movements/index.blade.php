@extends('layouts.admin')
@section('title', 'Stock Movements')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-arrow-left-right text-info"></i> Stock Movement History</h4>
        <p>Track all stock in, out, and adjustment records</p>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($t)) }}</option>
                    @endforeach
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
                <a href="{{ route('admin.stock_movements.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Movement Records</div>
        <span class="text-muted small">Showing {{ $movements->count() }} records</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="d-none-mobile">#</th>
                        <th class="d-none-mobile">Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th class="d-none d-md-table-cell">Reference</th>
                        <th class="d-none-mobile">By</th>
                        <th class="d-none d-md-table-cell">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">#{{ $m->id }}</span></td>
                            <td class="d-none-mobile"><span class="text-muted" style="font-size:0.82rem;">{{ $m->created_at->format('d/m/Y H:i') }}</span></td>
                            <td><span class="fw-semibold">{{ $m->product->product_name ?? 'N/A' }}</span></td>
                            <td>
                                <span class="badge-status bg-{{ $m->type == 'stock_in' ? 'success' : ($m->type == 'stock_out' ? 'danger' : ($m->type == 'adjustment' ? 'warning' : ($m->type == 'damaged' ? 'dark' : 'info'))) }} text-white">
                                    {{ str_replace('_', ' ', ucfirst($m->type)) }}
                                </span>
                            </td>
                            <td class="{{ $m->quantity > 0 ? 'text-success' : 'text-danger' }} fw-bold">{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                            <td class="d-none d-md-table-cell"><small class="text-muted">{{ $m->reference_type ? ucfirst($m->reference_type) . ' #' . $m->reference_id : '-' }}</small></td>
                            <td class="d-none-mobile"><small>{{ $m->user->name ?? 'N/A' }}</small></td>
                            <td class="d-none d-md-table-cell"><small class="text-muted">{{ Str::limit($m->note, 30) }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-arrow-left-right d-block"></i>
                                    <h5>No Movements Found</h5>
                                    <p>Stock movement records will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($movements, 'links') && $movements->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $movements->links() }}</div>
    @endif
</div>
@endsection
