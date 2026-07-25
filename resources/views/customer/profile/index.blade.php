@extends('layouts.customer')
@section('title', __('messages.my_profile'))
@section('content')

<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.my_profile') }}</li>
        </ol>
    </nav>

    @php
        $orderCount = $customer ? $customer->orders()->count() : 0;
        $activeOrders = $customer ? $customer->orders()->whereNotIn('order_status', ['delivered','cancelled'])->count() : 0;
        $cancelledCount = $customer ? $customer->orders()->where('order_status', 'cancelled')->count() : 0;
        $totalSpent = $customer ? $customer->orders()->where('order_status', 'delivered')->sum('total_amount') : 0;
    @endphp

    {{-- Premium Profile Banner --}}
    <div class="card border-0 overflow-hidden shadow-sm mb-4 profile-header-card" style="border-radius: var(--radius-md);">
        <div class="profile-banner-bg" style="background: linear-gradient(135deg, #064e3b 0%, #10b981 100%); padding: 40px 30px; position: relative;">
            {{-- Floating SVG leaves/decoration --}}
            <div style="position: absolute; right: 5%; bottom: -10px; opacity: 0.15; pointer-events: none;">
                <i class="bi bi-flower1" style="font-size: 15rem; color: white;"></i>
            </div>
            
            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-md-auto text-center text-md-start">
                    <div class="profile-avatar-wrap" style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; background: white; margin: 0 auto; box-shadow: var(--shadow-md); position: relative; overflow: visible;">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            <i class="bi bi-person-fill text-success fs-1"></i>
                        @endif
                        
                        {{-- Change Photo Button --}}
                        <div class="avatar-camera-btn" onclick="document.getElementById('avatarInput').click();" title="Upload/Change Photo">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                        
                        {{-- Delete Photo Button --}}
                        @if($user->avatar)
                            <form id="deleteAvatarForm" action="{{ route('profile.avatar.delete') }}" method="POST" class="position-absolute" style="bottom: 0; left: 0; z-index: 10;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger p-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border: 2px solid white; box-shadow: var(--shadow-sm);" title="Delete Photo" onclick="return confirm('Are you sure you want to delete your profile picture?')">
                                    <i class="bi bi-trash-fill" style="font-size: 0.75rem;"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    {{-- Hidden file submit form --}}
                    <form id="avatarForm" action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data" class="d-none">
                        @csrf
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit();">
                    </form>
                </div>
                <div class="col text-center text-md-start text-white">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 flex-wrap mb-1">
                        <h3 class="fw-bold mb-0 text-white">{{ $user->name }}</h3>
                        <span class="badge bg-white text-success px-3 py-1 rounded-pill fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $user->role }}</span>
                    </div>
                    <p class="text-white-50 mb-2">{{ $user->email }}</p>
                    <p class="mb-0 small text-white-50"><i class="bi bi-calendar-check me-1"></i> {{ __('messages.registered_on') }} {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Side Menu & Info Column --}}
        <div class="col-lg-4">
            {{-- Navigation Pills Menu --}}
            <div class="card border-0 shadow-sm mb-4 p-3 side-menu-card" style="border-radius: var(--radius-md);">
                <div class="nav flex-column nav-pills" id="profileTabs" role="tablist" style="gap: 6px;">
                    <button class="nav-link active text-start py-2.5 px-3 fw-semibold d-flex align-items-center gap-3" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                        <i class="bi bi-person-bounding-box fs-5"></i> {{ __('messages.edit_profile') }}
                    </button>
                    <button class="nav-link text-start py-2.5 px-3 fw-semibold d-flex align-items-center gap-3" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">
                        <i class="bi bi-shield-lock fs-5"></i> {{ __('messages.change_password') }}
                    </button>
                    <a class="nav-link text-start py-2.5 px-3 fw-semibold d-flex align-items-center gap-3 text-decoration-none" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-heart fs-5"></i> {{ __('messages.my_wishlist') }}
                    </a>
                    <a class="nav-link text-start py-2.5 px-3 fw-semibold d-flex align-items-center gap-3 text-decoration-none" href="{{ route('customer.orders.index') }}">
                        <i class="bi bi-bag-check fs-5"></i> {{ __('messages.order_history') }}
                    </a>
                    <a class="nav-link text-start py-2.5 px-3 fw-semibold d-flex align-items-center gap-3 text-decoration-none" href="{{ route('addresses.index') }}">
                        <i class="bi bi-geo-alt fs-5"></i> {{ __('messages.my_addresses') }}
                    </a>
                </div>
            </div>

            {{-- Summary Detail card --}}
            <div class="card border-0 shadow-sm p-4 text-start side-info-card" style="border-radius: var(--radius-md);">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-success me-2"></i>{{ __('messages.account_details') }}</h6>
                <div class="small">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">{{ __('messages.mobile_phone') }}</span>
                        <span class="fw-bold text-dark">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">{{ __('messages.current_city') }}</span>
                        <span class="fw-bold text-dark">{{ $customer->city ?? __('messages.phnom_penh_city') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('messages.registered_on') }}</span>
                        <span class="fw-bold text-dark">{{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side Tab Panels Column --}}
        <div class="col-lg-8">
            {{-- Order Stats Grid --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm p-3 text-center stat-widget-card bg-emerald" style="border-radius: var(--radius-md);">
                        <div class="stat-icon-wrapper text-success bg-success-light">
                            <i class="bi bi-basket3-fill"></i>
                        </div>
                        <div class="fw-extrabold fs-4 text-success mt-2">{{ $orderCount }}</div>
                        <small class="fw-bold text-muted small text-uppercase" style="font-size:0.65rem; letter-spacing:0.3px;">{{ __('messages.total_orders') }}</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm p-3 text-center stat-widget-card bg-blue" style="border-radius: var(--radius-md);">
                        <div class="stat-icon-wrapper text-primary bg-primary-light">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="fw-extrabold fs-4 text-primary mt-2">{{ $activeOrders }}</div>
                        <small class="fw-bold text-muted small text-uppercase" style="font-size:0.65rem; letter-spacing:0.3px;">{{ __('messages.active_orders') }}</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm p-3 text-center stat-widget-card bg-red" style="border-radius: var(--radius-md);">
                        <div class="stat-icon-wrapper text-danger bg-danger-light">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="fw-extrabold fs-4 text-danger mt-2">{{ $cancelledCount }}</div>
                        <small class="fw-bold text-muted small text-uppercase" style="font-size:0.65rem; letter-spacing:0.3px;">{{ __('messages.cancelled_label') }}</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm p-3 text-center stat-widget-card bg-amber" style="border-radius: var(--radius-md);">
                        <div class="stat-icon-wrapper text-warning bg-warning-light">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="fw-extrabold fs-4 text-warning mt-2">${{ number_format($totalSpent, 2) }}</div>
                        <small class="fw-bold text-muted small text-uppercase" style="font-size:0.65rem; letter-spacing:0.3px;">{{ __('messages.total_spent') }}</small>
                    </div>
                </div>
            </div>

            {{-- Form Panels --}}
            <div class="tab-content">
                {{-- Edit Profile Panel --}}
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="card border-0 shadow-sm form-panel-card" style="border-radius: var(--radius-md);">
                        <div class="card-header bg-white border-0 py-3 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-person-lines-fill text-success fs-5"></i>
                            <h6 class="fw-bold mb-0 text-dark">{{ __('messages.edit_profile') }}</h6>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.full_name') }}</label>
                                        <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required style="font-size:0.9rem;">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.email_address') }}</label>
                                        <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required style="font-size:0.9rem;">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.phone_number') }}</label>
                                        <input type="text" name="phone" class="form-control rounded-3" value="{{ old('phone', $user->phone) }}" style="font-size:0.9rem;">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.city_location') }}</label>
                                        <input type="text" name="city" class="form-control rounded-3" value="{{ old('city', $customer->city ?? __('messages.phnom_penh_city')) }}" style="font-size:0.9rem;">
                                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.shipping_address') }}</label>
                                        <textarea name="address" class="form-control rounded-3" rows="3" style="font-size:0.9rem;">{{ old('address', $customer->address ?? '') }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold mt-4 px-4 rounded-pill btn-gradient"><i class="bi bi-check-lg me-1"></i> {{ __('messages.save_changes') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Change Password Panel --}}
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card border-0 shadow-sm form-panel-card" style="border-radius: var(--radius-md);">
                        <div class="card-header bg-white border-0 py-3 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <h6 class="fw-bold mb-0 text-dark">{{ __('messages.change_password') }}</h6>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf @method('PUT')
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.current_password') }}</label>
                                        <input type="password" name="current_password" class="form-control rounded-3" required style="font-size:0.9rem;">
                                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.new_password') }}</label>
                                        <input type="password" name="password" class="form-control rounded-3" required style="font-size:0.9rem;">
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted small">{{ __('messages.confirm_new_password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control rounded-3" required style="font-size:0.9rem;">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold mt-4 px-4 rounded-pill btn-gradient"><i class="bi bi-shield-check me-1"></i> {{ __('messages.update_password') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Profile Header */
    .profile-header-card {
        border: 1px solid rgba(226, 232, 240, 0.4);
    }
    .profile-avatar-wrap {
        border-color: rgba(255, 255, 255, 0.4) !important;
        transition: all 0.3s ease;
    }
    .avatar-camera-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }
    .avatar-camera-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    /* Side menu card styling */
    .side-menu-card, .side-info-card, .form-panel-card {
        border: 1px solid var(--card-border);
    }
    #profileTabs .nav-link {
        border: none;
        border-radius: 12px;
        color: var(--gray-600);
        font-size: 0.92rem;
        transition: all 0.25s ease;
        background: transparent;
    }
    #profileTabs .nav-link:hover {
        background: #f1f5f9;
        color: var(--gray-900);
    }
    #profileTabs .nav-link.active {
        background: var(--primary-50) !important;
        color: var(--primary-dark) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
    }

    /* Stat Widgets */
    .stat-widget-card {
        border: 1px solid var(--card-border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .stat-widget-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.04) !important;
    }
    .stat-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    /* Widget Theme Colors */
    .bg-emerald {
        background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);
    }
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .bg-blue {
        background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
    }
    .bg-primary-light {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .bg-red {
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
    }
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    .bg-amber {
        background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);
    }
    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }

    /* Input design adjustments */
    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12) !important;
    }
    .btn-gradient {
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        transition: all 0.2s ease;
    }
    .btn-gradient:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25);
    }
</style>

@endsection