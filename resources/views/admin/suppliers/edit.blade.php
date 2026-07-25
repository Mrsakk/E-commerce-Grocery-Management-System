@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-pencil-square text-primary"></i> Edit Supplier</h4>
        <p>Update: {{ $supplier->supplier_name }}</p>
    </div>
</div>
<div class="form-card">
    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                @error('supplier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <h6 class="mt-4 fw-bold"><i class="bi bi-box-seam me-1"></i> Products Supplied</h6>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>Product</th><th>Supply Price</th><th>Lead Time (days)</th></tr></thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" name="products[]" value="{{ $product->id }}" class="form-check-input"
                                        id="p{{ $product->id }}" {{ $supplier->products->contains($product->id) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="p{{ $product->id }}">{{ $product->product_name }}</label>
                                </div>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="supply_price[{{ $product->id }}]" class="form-control form-control-sm"
                                    value="{{ $supplier->products->find($product->id)?->pivot->supply_price ?? '' }}" style="width:120px">
                            </td>
                            <td>
                                <input type="number" name="lead_time_days[{{ $product->id }}]" class="form-control form-control-sm"
                                    value="{{ $supplier->products->find($product->id)?->pivot->lead_time_days ?? '' }}" style="width:80px">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Update Supplier</button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
