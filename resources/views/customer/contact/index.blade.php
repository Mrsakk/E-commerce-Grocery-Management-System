@extends('layouts.customer')
@section('title', __('messages.contact_support'))
@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.contact_support') }}</li>
        </ol>
    </nav>

    {{-- Contact Header --}}
    <div class="text-center mb-5">
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;">
            <i class="bi bi-headset fs-2"></i>
        </div>
        <h3 class="fw-bold text-dark">{{ __('messages.were_here_to_help') }}</h3>
        <p class="text-muted small mx-auto" style="max-width:500px;">{{ __('messages.contact_desc') }}</p>
    </div>

    {{-- Contact Info Cards --}}
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:44px; height:44px;">
                    <i class="bi bi-geo-alt fs-5"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ __('messages.our_location') }}</h6>
                <p class="text-muted small mb-0">{!! __('messages.our_address') !!}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:44px; height:44px;">
                    <i class="bi bi-telephone fs-5"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ __('messages.helpline_phone') }}</h6>
                <p class="text-muted small mb-0">+855 (0) 12 345 678<br>+855 (0) 15 987 654</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:44px; height:44px;">
                    <i class="bi bi-envelope fs-5"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ __('messages.email_address') }}</h6>
                <p class="text-muted small mb-0">info@freshmart.com.kh<br>support@freshmart.com.kh</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:44px; height:44px;">
                    <i class="bi bi-clock fs-5"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ __('messages.fulfillment_hours') }}</h6>
                <p class="text-muted small mb-0">{!! __('messages.fulfillment_desc') !!}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Contact Form Column --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-send text-success me-2"></i>{{ __('messages.send_message') }}</h5>
                    <p class="text-muted small mb-4">{{ __('messages.send_message_desc') }}</p>
                    
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small">{{ __('messages.your_name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name ?? '') }}" required style="font-size:0.9rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small">{{ __('messages.your_email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email ?? '') }}" required style="font-size:0.9rem;">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small">{{ __('messages.subject_topic') }}</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required style="font-size:0.9rem;">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small">{{ __('messages.message_description') }}</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="{{ __('messages.message_placeholder') }}" required style="font-size:0.9rem;"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success fw-bold mt-4 px-4"><i class="bi bi-send me-1"></i> {{ __('messages.send_message') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- FAQ Column --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius:var(--radius-md); border: 1px solid var(--card-border);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-patch-question text-success me-2"></i>{{ __('messages.general_faqs') }}</h5>
                    <p class="text-muted small mb-3">{{ __('messages.faq_desc') }}</p>
                    
                    <div class="accordion" id="faqAccordion">
                        {{-- Orders --}}
                        <div class="accordion-item mb-2 border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold small py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqOrders">
                                    <i class="bi bi-box-seam text-success me-2"></i> {{ __('messages.orders_tracking') }}
                                </button>
                            </h2>
                            <div id="faqOrders" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    <p class="mb-2"><strong>{{ __('messages.faq_track_q') }}</strong><br>{{ __('messages.faq_track_a') }}</p>
                                    <p class="mb-0"><strong>{{ __('messages.faq_cancel_q') }}</strong><br>{{ __('messages.faq_cancel_a') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Delivery --}}
                        <div class="accordion-item mb-2 border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold small py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqDelivery">
                                    <i class="bi bi-truck text-success me-2"></i> {{ __('messages.delivery_areas') }}
                                </button>
                            </h2>
                            <div id="faqDelivery" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    <p class="mb-2"><strong>{{ __('messages.faq_del_q') }}</strong><br>{{ __('messages.faq_del_a') }}</p>
                                    <p class="mb-0"><strong>{{ __('messages.faq_cost_q') }}</strong><br>{{ __('messages.faq_cost_a') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Payments --}}
                        <div class="accordion-item mb-2 border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold small py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqPayments">
                                    <i class="bi bi-credit-card text-success me-2"></i> {{ __('messages.payment_gateways') }}
                                </button>
                            </h2>
                            <div id="faqPayments" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    <p class="mb-0">{!! __('messages.faq_payment_a') !!}</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Returns --}}
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold small py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqReturns">
                                    <i class="bi bi-arrow-left-right text-success me-2"></i> {{ __('messages.freshness_guarantee') }}
                                </button>
                            </h2>
                            <div id="faqReturns" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-muted">
                                    <p class="mb-0">{{ __('messages.faq_refund_a') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .accordion-button:not(.collapsed) {
        background-color: var(--primary-50);
        color: var(--primary-dark);
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
    }
</style>

@endsection