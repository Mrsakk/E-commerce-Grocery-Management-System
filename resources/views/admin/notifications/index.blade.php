@extends('layouts.admin')
@section('title', 'Admin Notifications')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-bell text-primary"></i> Notification Management</h4>
        <p>Manage and send system notifications</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('admin.admin_notifications.mark_all_read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-check-all me-1"></i> Mark All Read</button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">TOTAL</div>
                        <div class="stat-number" style="color:var(--primary);">{{ $notifications->total() }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-50);color:var(--primary);"><i class="bi bi-bell"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label" style="color:var(--gray-500);">UNREAD</div>
                        <div class="stat-number" style="color:var(--red-500);">{{ $unreadCount }}</div>
                    </div>
                    <div class="stat-icon" style="background:var(--red-50);color:var(--red-500);"><i class="bi bi-envelope"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.admin_notifications.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search notifications..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['admin_notification', 'order_status', 'payment_received', 'delivery_assigned', 'low_stock', 'delivery_failed', 'customer_notification'] as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($type)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.admin_notifications.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width:30px;"></th>
                        <th>Title</th>
                        <th class="d-none d-md-table-cell">Message</th>
                        <th class="d-none d-md-table-cell">Type</th>
                        <th class="d-none-mobile">User</th>
                        <th class="d-none-mobile">Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr style="{{ !$notification->is_read ? 'background: var(--primary-50);' : '' }}">
                            <td>
                                @if(!$notification->is_read)
                                    <span class="badge rounded-circle p-1" style="background:var(--primary);width:8px;height:8px;"></span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $notification->title }}</td>
                            <td class="d-none d-md-table-cell"><span class="text-muted" style="font-size:0.85rem;">{{ Str::limit($notification->message, 60) }}</span></td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                    {{ str_replace('_', ' ', $notification->type) }}
                                </span>
                            </td>
                            <td class="d-none-mobile">{{ $notification->user?->name ?? 'N/A' }}</td>
                            <td class="d-none-mobile"><span class="text-muted" style="font-size:0.8rem;">{{ $notification->created_at->format('d/m/Y H:i') }}</span></td>
                            <td>
                                <div class="action-btns">
                                    @if(!$notification->is_read)
                                    <form action="{{ route('admin.admin_notifications.mark_read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-view" title="Mark as Read">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.admin_notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete notification?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-bell d-block"></i>
                                    <h5>No Notifications</h5>
                                    <p>System notifications will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
