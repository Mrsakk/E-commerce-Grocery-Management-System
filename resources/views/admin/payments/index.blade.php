@extends('layouts.admin')
@section('title', 'Manage Payments')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-credit-card text-primary"></i> Payments Tracking</h4>
        <p>Track and verify customer payments</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">TOTAL VOLUME</div>
                        <div class="stat-number" style="color:var(--primary);">${{ number_format($payments->where('payment_status', 'paid')->sum('amount'), 2) }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50);color:var(--primary);"><i class="bi bi-currency-dollar"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">PENDING</div>
                        <div class="stat-number" style="color:var(--accent);">{{ $payments->where('payment_status', 'pending')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--amber-50);color:var(--accent);"><i class="bi bi-clock"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">SUCCESS RATE</div>
                        @php $paidCount = $payments->where('payment_status', 'paid')->count(); $totalPay = $payments->count(); $successRate = $totalPay > 0 ? round(($paidCount / $totalPay) * 100) : 100; @endphp
                        <div class="stat-number" style="color:var(--blue-500);">{{ $successRate }}%</div>
                    </div>
                    <div class="stat-icon" style="background:var(--blue-50);color:var(--blue-500);"><i class="bi bi-graph-up"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">Transaction Register</div>
        <span class="text-muted small">Showing {{ $payments->firstItem() ?? 0 }}-{{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="d-none-mobile">TXN #</th>
                        <th class="d-none d-md-table-cell">Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th class="d-none d-sm-table-cell">Method</th>
                        <th class="d-none-mobile">Status</th>
                        <th class="d-none-mobile">Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $method = strtolower($payment->payment_method);
                            $methodBadge = 'bg-secondary text-white';
                            if ($method == 'bkash') $methodBadge = 'bg-danger text-white';
                            elseif ($method == 'nagad') $methodBadge = 'bg-warning text-dark';
                            elseif ($method == 'card') $methodBadge = 'bg-primary text-white';
                            elseif ($method == 'cod') $methodBadge = 'bg-dark text-white';
                        @endphp
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">TXN-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="fw-semibold" style="color:var(--primary);">#{{ $payment->order_id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:30px;height:30px;background:var(--primary-50);color:var(--primary);font-size:0.78rem;">
                                        {{ substr($payment->order?->customer?->user?->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color:var(--gray-900);">{{ $payment->order?->customer?->user?->name ?? 'Guest' }}</div>
                                        <small class="text-muted" style="font-size:0.7rem;">{{ $payment->order?->customer?->phone ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-bold" style="color:var(--gray-900);">${{ number_format($payment->amount, 2) }}</span></td>
                            <td class="d-none d-sm-table-cell"><span class="badge {{ $methodBadge }} px-2 py-1 text-uppercase fw-semibold" style="font-size:0.72rem;">{{ $payment->payment_method }}</span></td>
                            <td class="d-none-mobile">
                                <span class="badge-status bg-{{ $payment->payment_status == 'paid' ? 'success' : 'warning' }} text-white">
                                    <i class="bi {{ $payment->payment_status == 'paid' ? 'bi-check-circle-fill' : 'bi-clock-fill' }}" style="font-size:0.65rem;"></i> {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="d-none-mobile"><span class="text-muted" style="font-size:0.8rem;">{{ $payment->created_at->format('d/m/Y') }}</span></td>
                            <td class="text-end">
                                <div class="action-btns justify-content-end">
                                    @if($payment->payment_status != 'paid')
                                        <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action btn-upload" title="Confirm Payment"
                                                onclick="return Swal.fire({title:'Confirm Payment?',text:'This will mark the payment as paid.',icon:'question',showCancelButton:true,confirmButtonColor:'#10b981',cancelButtonColor:'#64748b',confirmButtonText:'Yes, confirm!',cancelButtonText:'Cancel',reverseButtons:true}).then(r=>{if(r.isConfirmed){this.closest('form').submit();return true;}return false;});">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-credit-card d-block"></i>
                                    <h5>No Payments Found</h5>
                                    <p>No payment transactions logged yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
