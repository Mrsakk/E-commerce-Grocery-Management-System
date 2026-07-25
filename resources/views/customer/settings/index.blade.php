@extends('layouts.customer')
@section('title', __('messages.settings'))
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.index') }}">{{ __('messages.my_profile') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.settings') }}</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4"><i class="bi bi-gear text-success me-2"></i>{{ __('messages.settings') }}</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: var(--radius-md);">
                <div class="nav flex-column nav-pills" id="settingsTabs" role="tablist" style="gap: 4px;">
                    <button class="nav-link active text-start py-2 px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#preferences"><i class="bi bi-palette me-2"></i>Preferences</button>
                    <button class="nav-link text-start py-2 px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#notifications"><i class="bi bi-bell me-2"></i>Notifications</button>
                    <button class="nav-link text-start py-2 px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#payment"><i class="bi bi-credit-card me-2"></i>Payment</button>
                    <button class="nav-link text-start py-2 px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#privacy"><i class="bi bi-shield-lock me-2"></i>Privacy</button>
                    <button class="nav-link text-start py-2 px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#accessibility"><i class="bi bi-universal-access me-2"></i>Accessibility</button>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="tab-content">
                {{-- Preferences --}}
                <div class="tab-pane fade show active" id="preferences" role="tabpanel">
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">{{ __('messages.preferences') }}</h6>
                            <form method="POST" action="{{ route('settings.preferences.update') }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Theme</label>
                                    <select name="theme" class="form-select rounded-3">
                                        <option value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ ($settings['theme'] ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="system" {{ ($settings['theme'] ?? '') === 'system' ? 'selected' : '' }}>System</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Layout</label>
                                    <select name="layout" class="form-select rounded-3">
                                        <option value="grid" {{ ($settings['layout'] ?? 'grid') === 'grid' ? 'selected' : '' }}>Grid</option>
                                        <option value="list" {{ ($settings['layout'] ?? '') === 'list' ? 'selected' : '' }}>List</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Currency</label>
                                    <select name="currency" class="form-select rounded-3">
                                        <option value="USD" {{ ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="KHR" {{ ($settings['currency'] ?? '') === 'KHR' ? 'selected' : '' }}>KHR (៛)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4"><i class="bi bi-check-lg me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">{{ __('messages.notifications') }}</h6>
                            <form method="POST" action="{{ route('settings.notifications.update') }}">
                                @csrf @method('PUT')
                                @php $notifs = $settings['notifications'] ?? []; @endphp
                                @foreach(['order_confirmations','delivery_status','order_cancellation','deals_discounts','new_products','weekly_newsletter'] as $key)
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="{{ $key }}" value="1" id="{{ $key }}" {{ ($notifs[$key] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold small" for="{{ $key }}">{{ ucwords(str_replace('_',' ',$key)) }}</label>
                                    </div>
                                @endforeach
                                <hr>
                                <p class="fw-semibold small mb-2">Channels</p>
                                @foreach(['email','sms','push'] as $ch)
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="{{ $ch }}" value="1" id="ch_{{ $ch }}" {{ ($notifs[$ch] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold small" for="ch_{{ $ch }}">{{ ucfirst($ch) }}</label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 mt-3"><i class="bi bi-check-lg me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">{{ __('messages.payment') }}</h6>
                            <form method="POST" action="{{ route('settings.payment.update') }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Default Payment Method</label>
                                    <select name="default_payment" class="form-select rounded-3">
                                        @foreach(['COD','ABA','Wing','Bakong'] as $pm)
                                            <option value="{{ $pm }}" {{ ($settings['default_payment'] ?? 'COD') === $pm ? 'selected' : '' }}>{{ $pm }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="save_payment_info" value="1" id="savePayment" {{ ($settings['save_payment_info'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="savePayment">Save payment information</label>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4"><i class="bi bi-check-lg me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Privacy --}}
                <div class="tab-pane fade" id="privacy" role="tabpanel">
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Privacy & Security</h6>
                            <form method="POST" action="{{ route('settings.privacy.update') }}">
                                @csrf @method('PUT')
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="two_factor" value="1" id="twoFactor" {{ ($settings['two_factor'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="twoFactor">Two-factor authentication</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_order_activity" value="1" id="orderActivity" {{ ($settings['show_order_activity'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="orderActivity">Show order activity</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="share_analytics" value="1" id="analytics" {{ ($settings['share_analytics'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="analytics">Share analytics data</label>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4"><i class="bi bi-check-lg me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Accessibility --}}
                <div class="tab-pane fade" id="accessibility" role="tabpanel">
                    <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Accessibility</h6>
                            <form method="POST" action="{{ route('settings.accessibility.update') }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Font Size</label>
                                    <select name="font_size" class="form-select rounded-3">
                                        @foreach(['default','large','xlarge'] as $fs)
                                            <option value="{{ $fs }}" {{ ($settings['font_size'] ?? 'default') === $fs ? 'selected' : '' }}>{{ ucfirst($fs) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="high_contrast" value="1" id="highContrast" {{ ($settings['high_contrast'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="highContrast">High contrast mode</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="reduce_animations" value="1" id="reduceAnim" {{ ($settings['reduce_animations'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="reduceAnim">Reduce animations</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="enhanced_focus" value="1" id="enhancedFocus" {{ ($settings['enhanced_focus'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="enhancedFocus">Enhanced keyboard focus</label>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-4"><i class="bi bi-check-lg me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection