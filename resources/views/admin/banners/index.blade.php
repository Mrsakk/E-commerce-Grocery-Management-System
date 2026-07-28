@extends('layouts.admin')
@section('title', 'Manage Banners')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-images text-success"></i> Promotional Banners</h4>
        <p>Manage homepage banners and promotional content</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" onclick="showUploadModal('{{ route('admin.banners.store') }}', 'Upload Banner Image', 'banner', 0)">
            <i class="bi bi-cloud-upload"></i> Upload
        </button>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg"></i> Add Banner
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Banners</div>
        <span class="text-muted small">{{ $banners->count() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="d-none-mobile" style="width:60px;">ID</th>
                        <th>Preview</th>
                        <th>Title (EN)</th>
                        <th class="d-none d-md-table-cell">Title (KM)</th>
                        <th class="d-none d-md-table-cell">Badge</th>
                        <th class="d-none-mobile">Sort</th>
                        <th class="d-none-mobile">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="d-none-mobile"><span class="fw-bold" style="color:var(--gray-500);">#{{ $banner->id }}</span></td>
                            <td>
                                @if($banner->image_path)
                                    <img src="{{ asset($banner->image_path) }}" alt="Banner" class="rounded border" style="width: 110px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="rounded border d-flex align-items-center justify-content-center text-white" style="background: {{ $banner->gradient_css ?? 'linear-gradient(135deg, #022c22 0%, #10b981 100%)' }}; width: 110px; height: 45px; font-size: 0.7rem; padding: 2px;">
                                        <i class="bi {{ $banner->icon ?? 'bi-star-fill' }} me-1"></i> Gradient
                                    </div>
                                @endif
                            </td>
                            <td><span class="fw-semibold">{{ $banner->title_en }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ $banner->title_km ?? 'N/A' }}</span></td>
                            <td class="d-none d-md-table-cell">
                                @if($banner->badge_en)
                                    <span class="badge bg-warning text-dark">{{ $banner->badge_en }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="d-none-mobile"><span class="badge bg-light text-dark border fw-bold">{{ $banner->sort_order }}</span></td>
                            <td class="d-none-mobile">
                                <span class="badge-status bg-{{ $banner->status == 'active' ? 'success' : 'secondary' }} text-white">
                                    <i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> {{ ucfirst($banner->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-btns justify-content-end">
                                    <button class="btn-action btn-upload" title="Upload Image"
                                        onclick="showUploadModal('{{ route('admin.banners.update', $banner->id) }}', 'Upload Banner Image', 'banner', '{{ $banner->id }}')">
                                        <i class="bi bi-cloud-upload"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-images d-block"></i>
                                    <h5>No Banners Found</h5>
                                    <p>Create promotional banners for your homepage.</p>
                                    <a href="{{ route('admin.banners.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Banner
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($banners, 'links') && $banners->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $banners->links() }}</div>
    @endif
</div>
@endsection
