@extends('layouts.customer')
@section('title', 'Notifications')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h4><i class="bi bi-bell"></i> Notifications</h4>
    <form action="{{ route('notifications.mark_all_read') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-primary">Mark All as Read</button>
    </form>
</div>
<div class="table-container">
    <div class="list-group">
        @forelse($notifications as $n)
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ !$n->is_read ? 'list-group-item-success' : '' }}">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">{{ $n->title }}</div>
                    <p class="mb-0 small">{{ $n->message }}</p>
                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                </div>
                @if(!$n->is_read)
                    <form action="{{ route('notifications.mark_read', $n->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark Read</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1"></i>
                <p class="mt-2">No notifications</p>
            </div>
        @endforelse
    </div>
    {{ $notifications->links() }}
</div>
@endsection
