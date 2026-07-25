@extends('layouts.admin')
@section('title', 'Banner Details')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-image text-success"></i> Banner Details</h4>
        <p>{{ $banner->title_en ?? 'Banner' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                @if($banner->image_path && file_exists(public_path($banner->image_path)))
                    <img src="{{ asset($banner->image_path) }}" class="w-100 rounded" style="max-height:250px;object-fit:cover;" alt="{{ $banner->title_en }}">
                @elseif($banner->gradient_css)
                    <div class="w-100 rounded d-flex align-items-center justify-content-center" style="height:200px;background:{{ $banner->gradient_css }};">
                        <span class="text-white fw-bold fs-5">{{ $banner->title_en }}</span>
                    </div>
                @else
                    <div class="w-100 rounded d-flex align-items-center justify-content-center" style="height:200px;background:var(--gray-100);">
                        <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:130px;">Title (EN)</td><td class="fw-semibold">{{ $banner->title_en }}</td></tr>
                    <tr><td class="text-muted">Title (KM)</td><td>{{ $banner->title_km ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Description</td><td>{{ $banner->description_en ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Badge</td><td>{{ $banner->badge_en ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Link</td><td>{{ $banner->link ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Button Text</td><td>{{ $banner->button_text_en ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Icon</td><td>{{ $banner->icon ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Sort Order</td><td>{{ $banner->sort_order }}</td></tr>
                    <tr><td class="text-muted">Status</td><td><span class="badge bg-{{ $banner->status == 'active' ? 'success' : 'secondary' }} text-white">{{ ucfirst($banner->status) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection