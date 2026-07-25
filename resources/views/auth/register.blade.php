<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — FreshMart</title>
    <meta name="description" content="Join FreshMart — Cambodia's premium online grocery store. Register for free and enjoy fresh vegetables, fruits, and meats delivered to your door.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Koh+Santepheap:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-900: #0d3318;
            --green-800: #145022;
            --green-700: #1b6b2c;
            --green-600: #228B3A;
            --green-500: #2ea84a;
            --green-400: #4fc06a;
            --green-300: #7ed99a;
            --green-100: #e8f5ec;
        }

        html, body {
            min-height: 100%;
            font-family: 'Inter', 'Koh Santepheap', sans-serif;
        }

        /* ── BACKGROUND ── */
        .auth-bg {
            min-height: 100dvh;
            background: linear-gradient(135deg, #0d3318 0%, #145022 30%, #1b6b2c 65%, #0a2410 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 12s ease-in-out infinite;
            pointer-events: none;
        }
        .orb-1 { width: 600px; height: 600px; background: radial-gradient(circle, rgba(78,196,98,0.18) 0%, transparent 70%); top: -15%; left: -10%; animation-delay: 0s; }
        .orb-2 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(46,168,74,0.12) 0%, transparent 70%); bottom: -20%; right: -5%; animation-delay: -4s; }
        .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, rgba(126,217,154,0.10) 0%, transparent 70%); top: 40%; left: 40%; animation-delay: -8s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-40px) scale(1.05); }
        }

        /* Particles */
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: rise linear infinite;
            pointer-events: none;
        }
        @keyframes rise {
            0% { transform: translateY(110vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
        }

        /* ── AUTH WRAPPER ── */
        .auth-wrapper {
            position: relative; z-index: 10;
            display: flex;
            width: 1020px;
            max-width: 100%;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.08);
        }

        /* ── LEFT HERO PANEL ── */
        .hero-panel {
            flex: 1;
            background: linear-gradient(160deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0.04) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255,255,255,0.12);
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            min-height: 620px;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.5rem;
        }
        .brand-logo .logo-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--green-400), var(--green-500));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 4px 20px rgba(78,196,98,0.4);
            color: white;
        }

        .hero-main { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 20px 0; }
        .hero-main h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            line-height: 1.25;
            margin-bottom: 14px;
        }
        .hero-main h1 span {
            background: linear-gradient(90deg, var(--green-300), #a3e8b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-main p {
            color: rgba(255,255,255,0.65);
            font-size: 0.93rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* Stats row */
        .stats-row { display: flex; gap: 16px; margin-bottom: 28px; }
        .stat-card {
            flex: 1;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            padding: 14px 12px;
            text-align: center;
        }
        .stat-number { font-size: 1.5rem; font-weight: 800; color: var(--green-300); line-height: 1; }
        .stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.55); margin-top: 4px; }

        .feature-chips { display: flex; flex-direction: column; gap: 10px; }
        .feature-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 11px;
            padding: 10px 14px;
            color: rgba(255,255,255,0.85);
            font-size: 0.86rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .feature-chip:hover { background: rgba(255,255,255,0.12); transform: translateX(4px); }
        .feature-chip .chip-icon {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, rgba(78,196,98,0.3), rgba(46,168,74,0.2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
            color: white;
        }

        .hero-footer { color: rgba(255,255,255,0.35); font-size: 0.75rem; }

        /* ── RIGHT FORM PANEL ── */
        .form-panel {
            width: 460px;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(40px);
            padding: 44px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-panel h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f1a13;
            margin-bottom: 4px;
        }
        .form-subtitle {
            color: #6b7280;
            font-size: 0.87rem;
            margin-bottom: 24px;
        }

        /* Step progress */
        .progress-steps {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }
        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #e5e7eb;
            transition: all 0.3s;
        }
        .step-dot.active { background: var(--green-500); width: 24px; border-radius: 4px; }

        .form-group-custom { margin-bottom: 15px; }
        .form-label-custom {
            display: block;
            font-weight: 600;
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .input-wrap { position: relative; }
        .input-wrap .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.95rem;
            transition: color 0.2s;
            pointer-events: none;
        }
        .form-control-custom {
            width: 100%;
            padding: 11px 13px 11px 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 11px;
            font-size: 0.9rem;
            font-family: inherit;
            color: #111827;
            background: #f9fafb;
            transition: all 0.25s ease;
            outline: none;
        }
        .form-control-custom:focus {
            border-color: var(--green-500);
            background: white;
            box-shadow: 0 0 0 4px rgba(46,168,74,0.10);
        }
        .form-control-custom.is-invalid { border-color: #ef4444; }
        .input-wrap:focus-within .input-icon { color: var(--green-500); }

        .invalid-msg { color: #ef4444; font-size: 0.76rem; margin-top: 4px; }

        .row-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-register {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--green-600) 0%, var(--green-500) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.97rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.2px;
            box-shadow: 0 4px 20px rgba(34,139,58,0.3);
            font-family: inherit;
            margin-top: 6px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(34,139,58,0.4);
            background: linear-gradient(135deg, var(--green-700) 0%, var(--green-600) 100%);
        }
        .btn-register:active { transform: translateY(0); }

        .divider-or { display: flex; align-items: center; gap: 12px; color: #d1d5db; font-size: 0.77rem; margin: 16px 0; font-weight: 500; }
        .divider-or::before, .divider-or::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

        .google-btn {
            width: 100%;
            padding: 11px 16px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            color: #374151;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.22s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            font-family: inherit;
        }
        .google-btn:hover { background: #f9fafb; border-color: #d1d5db; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.07); color: #1f2937; }

        .terms-text { font-size: 0.75rem; color: #9ca3af; text-align: center; margin-top: 12px; line-height: 1.5; }
        .terms-text a { color: var(--green-600); text-decoration: none; }
        .terms-text a:hover { text-decoration: underline; }

        .signin-link { text-align: center; margin-top: 14px; font-size: 0.84rem; color: #6b7280; }
        .signin-link a { color: var(--green-600); font-weight: 600; text-decoration: none; }
        .signin-link a:hover { text-decoration: underline; }

        /* Mobile brand */
        .brand-mobile {
            display: none;
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-mobile a {
            color: var(--green-700);
            font-weight: 800;
            font-size: 1.4rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .brand-mobile .logo-icon-sm {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--green-500), var(--green-600));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: white;
        }

        @media (max-width: 820px) {
            .auth-wrapper { width: 100%; border-radius: 0; flex-direction: column; }
            .hero-panel { display: none; }
            .form-panel { width: 100%; padding: 36px 24px; }
            .brand-mobile { display: block; }
        }
    </style>
</head>
<body>
<div class="auth-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bg = document.querySelector('.auth-bg');
            for (let i = 0; i < 18; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.cssText = `left:${Math.random()*100}%; width:${3+Math.random()*5}px; height:${3+Math.random()*5}px; animation-duration:${8+Math.random()*14}s; animation-delay:${-Math.random()*15}s; opacity:${0.05+Math.random()*0.15}`;
                bg.appendChild(p);
            }
        });
    </script>

    <div class="auth-wrapper">

        <!-- ── HERO PANEL ── -->
        <div class="hero-panel">
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="logo-icon"><i class="bi bi-basket-fill"></i></div>
                FreshMart
            </a>

            <div class="hero-main">
                <h1>Join the freshest<br><span>community today.</span></h1>
                <p>Thousands of customers in Phnom Penh trust FreshMart for daily fresh groceries. Be part of it.</p>

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">2hr</div>
                        <div class="stat-label">Delivery</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Fresh</div>
                    </div>
                </div>

                <div class="feature-chips">
                    <div class="feature-chip">
                        <div class="chip-icon"><i class="bi bi-person-check-fill"></i></div>
                        Free to register, no hidden fees
                    </div>
                    <div class="feature-chip">
                        <div class="chip-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        Delivers across Phnom Penh
                    </div>
                    <div class="feature-chip">
                        <div class="chip-icon"><i class="bi bi-credit-card-2-front-fill"></i></div>
                        Cash, Bank Transfer & QR Pay
                    </div>
                </div>
            </div>

            <div class="hero-footer">© {{ date('Y') }} FreshMart. All rights reserved.</div>
        </div>

        <!-- ── FORM PANEL ── -->
        <div class="form-panel">
            <div class="brand-mobile">
                <a href="{{ route('home') }}">
                    <div class="logo-icon-sm"><i class="bi bi-basket-fill"></i></div>
                    FreshMart
                </a>
            </div>

            <h2>Create your account ✨</h2>
            <p class="form-subtitle">Fill in your details to get started for free.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group-custom">
                    <label class="form-label-custom">Full Name</label>
                    <div class="input-wrap">
                        <i class="bi bi-person-fill input-icon"></i>
                        <input type="text" name="name"
                               class="form-control-custom @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Your full name" required>
                    </div>
                    @error('name') <div class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="form-group-custom">
                    <label class="form-label-custom">Email Address</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" name="email"
                               class="form-control-custom @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com" required>
                    </div>
                    @error('email') <div class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>

                <!-- Phone -->
                <div class="form-group-custom">
                    <label class="form-label-custom">Phone Number <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-telephone-fill input-icon"></i>
                        <input type="text" name="phone"
                               class="form-control-custom @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}"
                               placeholder="012 345 678">
                    </div>
                    @error('phone') <div class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>

                <!-- Password row -->
                <div class="row-2col">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Password</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password"
                                   class="form-control-custom @error('password') is-invalid @enderror"
                                   placeholder="Min 8 chars" required>
                        </div>
                        @error('password') <div class="invalid-msg"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Confirm Password</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password_confirmation"
                                   class="form-control-custom"
                                   placeholder="Repeat password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus-fill"></i>
                    Create My Account
                </button>
            </form>

            <div class="divider-or">or sign up with</div>

            <a href="{{ route('auth.google') }}" class="google-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.5 24c0-1.61-.15-3.16-.42-4.69H24v8.89h12.65c-.55 2.94-2.2 5.44-4.69 7.11v5.91h7.59c4.44-4.09 7-10.11 7-17.22z"/>
                    <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.98-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.59-5.91c-2.1 1.41-4.79 2.22-8.3 2.22-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                <span>Sign up with Google</span>
            </a>

            <div class="terms-text">
                By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            </div>

            <div class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>

    </div>
</div>
</body>
</html>
