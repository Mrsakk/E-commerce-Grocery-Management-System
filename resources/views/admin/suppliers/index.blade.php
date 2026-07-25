@extends('layouts.admin')
@section('title', 'Suppliers')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-building text-primary"></i> Suppliers</h4>
        <p>Manage your supplier partners and contacts</p>
    </div>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg"></i> Add Supplier
    </a>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Suppliers</div>
        <span class="text-muted small">{{ $suppliers->count() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Contact</th>
                        <th class="d-none d-sm-table-cell">Phone</th>
                        <th class="d-none d-lg-table-cell">Email</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $s->id }}</span></td>
                            <td><span class="fw-semibold">{{ $s->supplier_name }}</span></td>
                            <td class="d-none d-md-table-cell">{{ $s->contact_person ?? '-' }}</td>
                            <td class="d-none d-sm-table-cell">{{ $s->phone ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell"><span class="text-muted">{{ $s->email ?? '-' }}</span></td>
                            <td><span class="badge-status bg-info text-white">{{ $s->products_count }}</span></td>
                            <td>
                                <span class="badge-status bg-{{ $s->status == 'active' ? 'success' : 'secondary' }} text-white">
                                    <i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-btns">
                                    <a href="{{ route('admin.suppliers.show', $s->id) }}" class="btn-action btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.suppliers.edit', $s->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.suppliers.destroy', $s->id) }}" style="display:inline" onsubmit="return confirm('Delete this supplier?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-building d-block"></i>
                                    <h5>No Suppliers Found</h5>
                                    <p>Add your first supplier partner.</p>
                                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Supplier
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($suppliers, 'links') && $suppliers->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $suppliers->links() }}</div>
    @endif
</div>
@endsection
