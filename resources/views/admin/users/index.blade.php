@extends('layouts.admin')
@section('title', 'Manage Users')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-person-gear text-primary"></i> User & Role Management</h4>
        <p>Manage admin, staff, and delivery users</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add User
    </a>
</div>

<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    @foreach(['admin', 'staff', 'delivery', 'customer'] as $role)
                        <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Users</div>
        <span class="text-muted small">{{ $users->total() }} users</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $user->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;background:var(--primary-50);color:var(--primary);font-size:0.8rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td><span class="text-muted">{{ $user->email }}</span></td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $roleColors = ['admin' => 'danger', 'staff' => 'warning', 'delivery' => 'info', 'customer' => 'success'];
                                @endphp
                                <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }} rounded-pill px-2 py-1">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </td>
                            <td><span class="text-muted">{{ $user->created_at->format('d/m/Y') }}</span></td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-action btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.users.toggle_status', $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action {{ ($user->status ?? 'active') === 'active' ? 'btn-delete' : 'btn-view' }}" title="Toggle Status"
                                                onclick="return confirm('Toggle status for {{ $user->name }}?')">
                                            <i class="bi bi-{{ ($user->status ?? 'active') === 'active' ? 'person-dash' : 'person-check' }}"></i>
                                        </button>
                                    </form>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete"
                                                onclick="return confirm('Delete user {{ $user->name }}? This cannot be undone.')">
                                            <i class="bi bi-trash"></i>
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
                                    <i class="bi bi-person-gear d-block"></i>
                                    <h5>No Users Found</h5>
                                    <p>Create your first user account.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $users->links() }}</div>
    @endif
</div>
@endsection
