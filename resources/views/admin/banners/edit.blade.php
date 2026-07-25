@extends('layouts.admin')
@section('title', 'Edit Banner')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-pencil-square text-primary"></i> Edit Banner</h4>
        <p>Update promotional banner</p>
    </div>
</div>
<div class="form-card">
    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Title (EN) <span class="text-danger">*</span></label>
                <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $banner->title_en) }}" required>
                @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Title (KM)</label>
                <input type="text" name="title_km" class="form-control" value="{{ old('title_km', $banner->title_km) }}">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Description (EN)</label>
                <textarea name="description_en" class="form-control" rows="2">{{ old('description_en', $banner->description_en) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Description (KM)</label>
                <textarea name="description_km" class="form-control" rows="2">{{ old('description_km', $banner->description_km) }}</textarea>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Badge Label (EN)</label>
                <input type="text" name="badge_en" class="form-control" value="{{ old('badge_en', $banner->badge_en) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Badge Label (KM)</label>
                <input type="text" name="badge_km" class="form-control" value="{{ old('badge_km', $banner->badge_km) }}">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Redirect Link</label>
                <input type="text" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Button Text (EN)</label>
                <input type="text" name="button_text_en" class="form-control" value="{{ old('button_text_en', $banner->button_text_en) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Button Text (KM)</label>
                <input type="text" name="button_text_km" class="form-control" value="{{ old('button_text_km', $banner->button_text_km) }}">
            </div>
        </div>
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Upload New Image <small class="text-muted">(Optional)</small></label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($banner->image_path)
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1">Current:</small>
                        <img src="{{ asset($banner->image_path) }}" alt="Current" class="rounded border" style="max-height:80px;">
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Background Gradient CSS</label>
                <input type="text" name="gradient_css" class="form-control" value="{{ old('gradient_css', $banner->gradient_css) }}">
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Floating Icon Class</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $banner->icon) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $banner->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $banner->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Update Banner</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
