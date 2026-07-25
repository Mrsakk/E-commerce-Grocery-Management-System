@extends('layouts.admin')
@section('title', 'Review Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-star text-warning"></i> Review #{{ $review->id }}</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">{{ $review->product->product_name }} by {{ $review->customer->user->name ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-chat-left-text me-2"></i> Review Content</div>
            <div class="card-body">
                <div class="mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}" style="font-size:1.2rem;"></i>
                    @endfor
                    <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                </div>
                <div class="p-3 rounded-3" style="background:var(--gray-50);">
                    <p class="mb-0" style="line-height:1.7;">{{ $review->review_text ?: 'No written review provided.' }}</p>
                </div>
                <div class="mt-3 text-muted" style="font-size:0.8rem;">
                    Posted on {{ $review->created_at->format('M d, Y \a\t h:i A') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i> Info</div>
            <div class="card-body">
                <div class="modal-detail-row"><div class="modal-detail-label">Product</div><div class="modal-detail-value">{{ $review->product->product_name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Customer</div><div class="modal-detail-value">{{ $review->customer->user->name ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Email</div><div class="modal-detail-value">{{ $review->customer->user->email ?? 'N/A' }}</div></div>
                <div class="modal-detail-row"><div class="modal-detail-label">Status</div>
                    <div class="modal-detail-value">
                        <span class="badge rounded-pill px-2 py-1 {{ $review->is_approved ? 'bg-success' : 'bg-secondary' }}">
                            {{ $review->is_approved ? 'Approved' : 'Hidden' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header"><i class="bi bi-gear me-2"></i> Actions</div>
            <div class="card-body d-grid gap-2">
                @if($review->is_approved)
                    <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100"><i class="bi bi-eye-slash me-1"></i> Hide Review</button>
                    </form>
                @else
                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-eye me-1"></i> Approve Review</button>
                    </form>
                @endif
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Delete this review permanently?')">
                        <i class="bi bi-trash me-1"></i> Delete Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
