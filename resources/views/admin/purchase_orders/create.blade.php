@extends('layouts.admin')
@section('title', 'Create Purchase Order')
@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="bi bi-plus-circle text-success"></i> Create Purchase Order</h4>
        <p>Order stock from your suppliers</p>
    </div>
</div>
<div class="form-card">
    <form action="{{ route('admin.purchase-orders.store') }}" method="POST">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" class="form-select" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->supplier_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Note</label>
                <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
            </div>
        </div>

        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-1"></i> Order Items</h6>
        <div class="table-responsive">
            <table class="table table-custom" id="itemsTable">
                <thead><tr><th>Product</th><th>Qty</th><th>Unit Cost</th><th>Subtotal</th><th>Action</th></tr></thead>
                <tbody>
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select form-select-sm product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->product_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm qty" value="1" min="1" required></td>
                        <td><input type="number" step="0.01" name="items[0][unit_cost]" class="form-control form-control-sm cost" value="0" required></td>
                        <td class="subtotal fw-bold">$0.00</td>
                        <td><button type="button" class="btn-action btn-delete remove-row" title="Remove"><i class="bi bi-trash"></i></button></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end fw-bold">Total:</td><td id="grandTotal" class="fw-bold" style="color:var(--primary);">$0.00</td><td></td></tr>
                </tfoot>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addRow"><i class="bi bi-plus-lg me-1"></i> Add Item</button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Create Purchase Order</button>
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let rowIndex = 1;
document.getElementById('addRow')?.addEventListener('click', function() {
    const tbody = document.querySelector('#itemsTable tbody');
    const row = document.querySelector('.item-row').cloneNode(true);
    row.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        if (name) el.setAttribute('name', name.replace(/\d+/, rowIndex));
        el.value = '';
    });
    row.querySelector('.qty').value = 1;
    row.querySelector('.subtotal').textContent = '$0.00';
    tbody.appendChild(row);
    rowIndex++;
});
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') || e.target.classList.contains('cost')) {
        const row = e.target.closest('tr');
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const cost = parseFloat(row.querySelector('.cost').value) || 0;
        row.querySelector('.subtotal').textContent = '$' + (qty * cost).toFixed(2);
        calcGrandTotal();
    }
});
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) e.target.closest('tr').remove();
        calcGrandTotal();
    }
});
function calcGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => { total += parseFloat(el.textContent.replace('$', '')) || 0; });
    document.getElementById('grandTotal').innerHTML = '<strong>$' + total.toFixed(2) + '</strong>';
}
</script>
@endpush
@endsection
