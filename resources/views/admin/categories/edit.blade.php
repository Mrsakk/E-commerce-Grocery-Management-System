@extends('layouts.admin')
@section('title', __('messages.edit_category'))
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-pencil-square text-primary"></i> {{ __('messages.edit_category') }}</h4>
        <p>Update category: {{ $category->category_name }}</p>
    </div>
</div>
<div class="form-card" style="max-width:640px;">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
            <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" value="{{ old('category_name', $category->category_name) }}" required>
            @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('messages.description') }}</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('messages.status') }}</label>
            <select name="status" class="form-select">
                <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
            </select>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> {{ __('messages.save_changes') }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
