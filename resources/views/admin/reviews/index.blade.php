@extends('layouts.admin')
@section('title', 'Manage Reviews')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-star text-warning"></i> Review & Feedback Management</h4>
        <p>Manage customer reviews and ratings</p>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by product or customer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Reviews</div>
        <span class="text-muted small">{{ $reviews->total() }} reviews</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="d-none-mobile">#</th>
                        <th>Product</th>
                        <th class="d-none d-md-table-cell">Customer</th>
                        <th>Rating</th>
                        <th class="d-none d-md-table-cell">Review</th>
                        <th class="d-none-mobile">Status</th>
                        <th class="d-none-mobile">Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">#{{ $review->id }}</span></td>
                            <td class="fw-semibold">{{ $review->product->product_name ?? 'Deleted' }}</td>
                            <td class="d-none d-md-table-cell">{{ $review->customer->user->name ?? 'N/A' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}" style="font-size:0.75rem;"></i>
                                @endfor
                            </td>
                            <td class="d-none d-md-table-cell"><span class="text-muted" style="font-size:0.85rem;">{{ Str::limit($review->review_text, 80) }}</span></td>
                            <td class="d-none-mobile">
                                <span class="badge rounded-pill px-2 py-1 {{ $review->is_approved ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="d-none-mobile"><span class="text-muted">{{ $review->created_at->format('d/m/Y') }}</span></td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn-action btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($review->is_approved)
                                        <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action btn-delete" title="Hide Review">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action btn-view" title="Approve Review">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Delete this review permanently?')">
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
                                    <i class="bi bi-star d-block"></i>
                                    <h5>No Reviews Found</h5>
                                    <p>Customer reviews will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
