<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - FreshMart Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Khmer Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bayon&family=Bokor&family=Carter+One&family=Chenla&family=Cutive+Mono&family=Kdam+Thmor+Pro&family=Khmer&family=Koh+Santepheap:wght@100;300;400;700;900&family=Koulen&family=Luckiest+Guy&family=Merienda:wght@300..900&family=Metal&family=Rowdies:wght@300;400;700&family=Rubik+Vinyl&family=Young+Serif&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Leaflet.js for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        :root {
            --primary: #4f46e5; /* Indigo-600 */
            --primary-dark: #3730a3; /* Indigo-800 */
            --primary-light: #818cf8; /* Indigo-400 */
            --primary-50: #f5f3ff; /* Indigo-50 */
            --accent: #f59e0b; /* Amber-500 */
            --accent-dark: #b45309;
            --bg-body: #f8fafc; /* Slate-50 */
            --card-border: rgba(226, 232, 240, 0.8); /* Slate-200 */
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-900: #0f172a;
            --shadow-sm: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.06);
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
        }
        * { box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', 'Khmer', 'Koh Santepheap', sans-serif; 
            background: var(--bg-body); 
            color: var(--gray-900); 
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }
        .navbar-delivery { 
            background: rgba(55, 48, 163, 0.95) !important; 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 0; 
            box-shadow: var(--shadow-md); 
        }
        .navbar-delivery .navbar-brand { 
            font-weight: 900; 
            font-size: 1.35rem; 
            color: white !important; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            letter-spacing: -0.5px;
        }
        .navbar-delivery .nav-link { 
            color: rgba(255,255,255,0.8) !important; 
            font-weight: 600; 
            padding: 10px 18px !important; 
            transition: all 0.2s; 
            border-radius: var(--radius-sm); 
            font-size: 0.9rem;
        }
        .navbar-delivery .nav-link:hover { 
            background: rgba(255,255,255,0.08); 
            color: white !important; 
        }
        .navbar-delivery .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white !important; 
        }
        .navbar-delivery .dropdown-menu {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-md);
            padding: 8px;
        }
        .navbar-delivery .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.88rem;
            color: var(--gray-900);
            transition: all 0.15s ease;
        }
        .navbar-delivery .dropdown-item:hover {
            background: var(--primary-50);
            color: var(--primary-dark);
            transform: translateX(2px);
        }
        .content-wrap { max-width: 1200px; margin: 0 auto; padding: 32px 16px; }
        
        .stat-card { 
            border: 1px solid var(--card-border); 
            border-radius: var(--radius-md); 
            background: white;
            transition: all 0.25s ease; 
            height: 100%; 
            box-shadow: var(--shadow-sm); 
        }
        .stat-card:hover { 
            transform: translateY(-4px); 
            box-shadow: var(--shadow-md); 
        }
        .stat-card .stat-body { padding: 24px; position: relative; }
        .stat-card .stat-icon { 
            font-size: 2rem; 
            position: absolute; 
            right: 20px; 
            top: 20px; 
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-card .stat-number { font-size: 2.2rem; font-weight: 800; line-height: 1.1; color: var(--gray-900); }
        .stat-card .stat-label { font-size: 0.85rem; font-weight: 700; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }
        
        .table-container { 
            background: white; 
            border-radius: var(--radius-lg); 
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm); 
            padding: 24px; 
        }
        .table-container h5 { font-weight: 800; font-size: 1.2rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; color: var(--gray-900); }
        .table-custom { margin-bottom: 0; }
        .table-custom thead th { 
            background: var(--gray-50); 
            border-bottom: 1.5px solid var(--gray-200); 
            padding: 14px 18px; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.8px; 
            color: var(--gray-600); 
            font-weight: 700; 
        }
        .table-custom tbody td { padding: 14px 18px; vertical-align: middle; border-bottom: 1px solid var(--gray-200); font-size: 0.9rem; }
        .table-custom tbody tr:hover { background: var(--primary-50); }
        
        .badge-status { 
            padding: 6px 12px; 
            border-radius: 50px; 
            font-size: 0.72rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            display: inline-block;
            border-width: 1px;
            border-style: solid;
        }
        .status-assigned { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .status-on_the_way { background: #fffbeb; color: #d97706; border-color: #fef3c7; }
        .status-delivered { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
        .status-failed { background: #fef2f2; color: #dc2626; border-color: #fee2e2; }

        .card-custom { 
            border: 1px solid var(--card-border); 
            border-radius: var(--radius-md); 
            box-shadow: var(--shadow-sm); 
            background: white;
            overflow: hidden;
        }
        .card-custom .card-header { 
            background: white; 
            border-bottom: 1px solid var(--gray-200); 
            padding: 16px 24px; 
            font-weight: 700; 
            color: var(--gray-900);
            border-radius: var(--radius-md) var(--radius-md) 0 0 !important; 
        }
        .card-custom .card-body { padding: 24px; }
        
        .form-control, .form-select { 
            border-radius: var(--radius-sm); 
            border: 1.5px solid var(--gray-200); 
            padding: 10px 16px; 
            font-size: 0.88rem; 
            transition: all 0.2s ease; 
        }
        .form-control:focus, .form-select:focus { 
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12); 
            outline: none;
        }
        .form-label { font-weight: 700; font-size: 0.85rem; margin-bottom: 6px; color: var(--gray-900); }
        
        .btn { 
            border-radius: var(--radius-sm); 
            padding: 10px 22px; 
            font-weight: 700; 
            font-size: 0.88rem; 
            transition: all 0.2s ease; 
        }
        .btn-primary { 
            background: var(--primary); 
            border: none; 
            color: white;
        }
        .btn-primary:hover { 
            background: var(--primary-dark); 
            transform: translateY(-1px); 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); 
        }
        .btn-outline-secondary {
            border: 1.5px solid var(--gray-300);
            color: var(--gray-600);
            background: white;
        }
        .btn-outline-secondary:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }
        .btn-sm { padding: 8px 16px; font-size: 0.8rem; }
        
        .alert-custom { 
            border: none; 
            border-radius: var(--radius-sm); 
            padding: 16px 20px; 
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .content-wrap { padding: 16px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-delivery">
        <div class="container">
            <a class="navbar-brand" href="{{ route('delivery.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FreshMart Logo" style="height: 32px; width: auto; object-fit: contain;" class="d-inline-block align-top me-2 rounded-circle border bg-white">
                FreshMart Delivery
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#deliveryNav" style="color:white;font-size:1.5rem;">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="deliveryNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <!-- Language Dropdown -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-translate text-white"></i>
                            @if(App::getLocale() === 'km')
                                <span>ខ្មែរ</span>
                            @else
                                <span>English</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="border:none;box-shadow:var(--shadow-md);border-radius:var(--radius-sm);padding:8px;min-width:140px;">
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ App::getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                                    <span class="badge bg-light text-dark border">EN</span> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 {{ App::getLocale() === 'km' ? 'active' : '' }}" href="{{ route('lang.switch', 'km') }}">
                                    <span class="badge bg-light text-dark border">KM</span> ភាសាខ្មែរ
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}" href="{{ route('delivery.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> {{ __('messages.my_deliveries') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house me-1"></i> {{ __('messages.store') }}</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="border:none;box-shadow:var(--shadow-md);border-radius:var(--radius-sm);padding:8px;min-width:180px;">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrap">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-custom d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-custom d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
