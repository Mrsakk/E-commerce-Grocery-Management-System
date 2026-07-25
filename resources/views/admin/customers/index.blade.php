@extends('layouts.admin')
@section('title', 'Manage Customers')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-people text-primary"></i> Customers</h4>
        <p>View and manage registered customer accounts</p>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Search</button>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Customers</div>
        <span class="text-muted small">{{ $customers->total() }} registered</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-sm-table-cell">Phone</th>
                        <th class="d-none d-lg-table-cell">City</th>
                        <th>Orders</th>
                        <th class="d-none d-sm-table-cell">Status</th>
                        <th class="d-none d-lg-table-cell">Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $customer->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;background:var(--primary-50);color:var(--primary);font-size:0.8rem;">
                                        {{ substr($customer->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    <span class="fw-semibold">{{ $customer->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ $customer->user->email ?? 'N/A' }}</span></td>
                            <td class="d-none d-sm-table-cell">{{ $customer->user->phone ?? 'N/A' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $customer->city }}</td>
                            <td><span class="badge-status bg-info text-white">{{ $customer->orders->count() }}</span></td>
                            <td class="d-none d-sm-table-cell">
                                @php $userStatus = $customer->user->status ?? 'active'; @endphp
                                <span class="badge rounded-pill px-2 py-1 {{ $userStatus === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($userStatus) }}
                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell"><span class="text-muted">{{ $customer->created_at->format('d/m/Y') }}</span></td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-action btn-view" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.customers.toggle_status', $customer->user_id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action {{ $userStatus === 'active' ? 'btn-delete' : 'btn-view' }}"
                                                title="{{ $userStatus === 'active' ? 'Deactivate' : 'Activate' }}"
                                                onclick="return confirm('{{ $userStatus === 'active' ? 'Deactivate' : 'Activate' }} this customer?')">
                                            <i class="bi bi-{{ $userStatus === 'active' ? 'person-dash' : 'person-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-people d-block"></i>
                                    <h5>No Customers Found</h5>
                                    <p>{{ request('search') ? 'No customers match your search.' : 'Customer accounts will appear here once they register.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($customers, 'links') && $customers->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
