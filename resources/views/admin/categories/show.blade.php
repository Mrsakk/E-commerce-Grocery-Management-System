@extends('layouts.admin')
@section('title', 'Category Details')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-grid text-success"></i> Category Details</h4>
        <p>{{ $category->category_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:150px;">Name</td><td class="fw-bold">{{ $category->category_name }}</td></tr>
                    <tr><td class="text-muted">Description</td><td>{{ $category->description ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td><span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }} text-white">{{ ucfirst($category->status) }}</span></td></tr>
                    <tr><td class="text-muted">Total Products</td><td class="fw-bold">{{ $category->products_count }}</td></tr>
                </table>
            </div>
        </div>

        <h6 class="fw-bold mb-3"><i class="bi bi-box text-primary me-2"></i>Products in this Category</h6>
        @if($category->products->count())
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Price</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($category->products as $product)
                            <tr>
                                <td>#{{ $product->id }}</td>
                                <td class="fw-semibold">{{ $product->product_name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td><span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }} text-white">{{ ucfirst($product->status) }}</span></td>
                                <td class="text-end"><a href="{{ route('admin.products.show', $product->id) }}" class="btn-action btn-view" title="View"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No products in this category.</p>
        @endif
    </div>
</div>
@endsection