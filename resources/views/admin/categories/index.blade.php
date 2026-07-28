@extends('layouts.admin')
@section('title', __('messages.manage_categories'))
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-grid text-success"></i> {{ __('messages.categories') }}</h4>
        <p>Manage product categories and organize your catalog</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg"></i> {{ __('messages.add_category') }}
    </a>
</div>

<div class="card card-custom">
    <div class="card-header">
        <div class="fw-bold fs-6">All Categories</div>
        <span class="text-muted small">{{ $categories->count() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th class="d-none d-md-table-cell">{{ __('messages.description') }}</th>
                        <th>{{ __('messages.products') }}</th>
                        <th class="d-none-mobile">{{ __('messages.status') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td><span class="fw-bold" style="color:var(--gray-500);">#{{ $cat->id }}</span></td>
                            <td><span class="fw-semibold">{{ $cat->category_name }}</span></td>
                            <td class="d-none d-md-table-cell"><span class="text-muted">{{ Str::limit($cat->description, 45) }}</span></td>
                            <td><span class="badge-status bg-info text-white">{{ $cat->products_count }}</span></td>
                            <td class="d-none-mobile">
                                <span class="badge-status bg-{{ $cat->status == 'active' ? 'success' : 'secondary' }} text-white">
                                    <i class="bi bi-circle-fill" style="font-size:0.35rem;"></i> {{ ucfirst($cat->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-btns justify-content-end">
                                    <a href="{{ route('admin.categories.show', $cat->id) }}" class="btn-action btn-view" title="View Category">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-action btn-edit" title="Edit Category">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" id="delete-cat-{{ $cat->id }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Delete Category"
                                            onclick="confirmDelete('delete-cat-{{ $cat->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-grid d-block"></i>
                                    <h5>No Categories Found</h5>
                                    <p>Start by creating your first product category.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($categories, 'links') && $categories->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $categories->links() }}</div>
    @endif
</div>
@endsection
