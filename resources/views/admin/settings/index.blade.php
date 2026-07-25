@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="page-header mb-4">
    <div class="page-header-left">
        <h4><i class="bi bi-gear text-primary"></i> System Settings</h4>
        <p>Configure your store settings and preferences</p>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-4 shadow-sm">
        <ul class="mb-0 small fw-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="fw-bold fs-6"><i class="bi bi-shop me-2"></i> General Settings</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="settings[store_name]" class="form-control" value="{{ \App\Models\Setting::getValue('store_name', 'FreshMart') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Store Phone</label>
                    <input type="text" name="settings[store_phone]" class="form-control" value="{{ \App\Models\Setting::getValue('store_phone', '012 345 678') }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Store Address</label>
                    <textarea name="settings[store_address]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('store_address', 'Phnom Penh, Cambodia') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="fw-bold fs-6"><i class="bi bi-truck me-2"></i> Delivery Settings</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Delivery Fee ($)</label>
                    <input type="number" step="0.01" name="settings[delivery_fee]" class="form-control" value="{{ \App\Models\Setting::getValue('delivery_fee', '2.00') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Free Delivery Min Order ($)</label>
                    <input type="number" step="0.01" name="settings[free_delivery_min]" class="form-control" value="{{ \App\Models\Setting::getValue('free_delivery_min', '50.00') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Currency</label>
                    <select name="settings[currency]" class="form-select">
                        <option value="USD" {{ \App\Models\Setting::getValue('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="KHR" {{ \App\Models\Setting::getValue('currency', 'USD') == 'KHR' ? 'selected' : '' }}>KHR</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="fw-bold fs-6"><i class="bi bi-credit-card me-2"></i> Payment Methods</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @php $enabledMethods = json_decode(\App\Models\Setting::getValue('payment_methods', '["COD","Transfer","QR","Card"]'), true) ?? []; @endphp
                @foreach(['COD' => 'Cash on Delivery', 'Transfer' => 'Bank Transfer', 'QR' => 'QR Code', 'Card' => 'Credit/Debit Card'] as $key => $label)
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="payment_methods_cb[]" value="{{ $key }}" class="form-check-input" id="pm_{{ $key }}" {{ in_array($key, $enabledMethods) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="pm_{{ $key }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
                <input type="hidden" name="settings[payment_methods]" id="payment_methods_hidden" value="{{ \App\Models\Setting::getValue('payment_methods', '["COD","Transfer","QR","Card"]') }}">
                <input type="hidden" name="groups[payment_methods]" value="payment">
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="fw-bold fs-6"><i class="bi bi-receipt me-2"></i> Order Settings</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" step="0.01" name="settings[tax_rate]" class="form-control" value="{{ \App\Models\Setting::getValue('tax_rate', '0') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Save Settings</button>
    </div>
</form>

@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function() {
    const checkboxes = document.querySelectorAll('input[name="payment_methods_cb[]"]:checked');
    const values = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('payment_methods_hidden').value = JSON.stringify(values);
});
</script>
@endpush
@endsection
