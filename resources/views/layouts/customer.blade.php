<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Grocery Store') - FreshMart</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    
    <!-- Khmer Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bayon&family=Bokor&family=Carter+One&family=Chenla&family=Cutive+Mono&family=Kdam+Thmor+Pro&family=Khmer&family=Koh+Santepheap:wght@100;300;400;700;900&family=Koulen&family=Luckiest+Guy&family=Merienda:wght@300..900&family=Metal&family=Rowdies:wght@300;400;700&family=Rubik+Vinyl&family=Young+Serif&display=swap" rel="stylesheet">
    
    <!-- Leaflet.js for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        :root {
            --primary: #10b981; /* Emerald-500 */
            --primary-dark: #064e3b; /* Emerald-800 / Forest */
            --primary-light: #34d399; /* Emerald-400 */
            --primary-50: #ecfdf5; /* Emerald-50 */
            --accent: #f59e0b; /* Amber-500 */
            --accent-dark: #d97706; /* Amber-600 */
            --accent-50: #fffbeb;
            --bg-body: #f8fafc; /* Slate-50 */
            --card-border: rgba(226, 232, 240, 0.7); /* Slate-200 */
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
            --radius-lg: 24px;
        }
 
        * { box-sizing: border-box; }
        body { 
            font-family: 'Kantumruy Pro', 'Plus Jakarta Sans', 'Koh Santepheap', sans-serif; 
            background: var(--bg-body); 
            color: var(--gray-900); 
            -webkit-font-smoothing: antialiased; 
            letter-spacing: -0.1px;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand, .hero-title, .section-title {
            font-family: 'Outfit', 'Kantumruy Pro', 'Bokor', sans-serif;
            font-weight: 700;
        }
        
        /* Premium custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Premium Floating & Glassmorphic Navbar */
        .navbar { 
            background: rgba(255, 255, 255, 0.88) !important; 
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            padding: 16px 0; 
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.01);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: sticky !important;
        }
        
        /* Thin Top Accent Gradient strip */
        .navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 50%, var(--accent) 100%);
            z-index: 10;
        }

        .navbar-scrolled {
            padding: 8px 0; 
            background: rgba(255, 255, 255, 0.94) !important;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08) !important;
            border-bottom: 1px solid rgba(16, 185, 129, 0.15);
        }
        
        .navbar-brand { 
            font-weight: 900; 
            font-size: 1.55rem; 
            color: var(--primary-dark) !important; 
            letter-spacing: -0.8px; 
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        .navbar-brand:hover {
            transform: translateY(-0.5px);
        }
        
        .navbar-brand i {
            font-size: 1.7rem;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(16, 185, 129, 0.15));
        }
        
        /* Pill shaped premium navigation links */
        .navbar .nav-link { 
            font-weight: 700; 
            color: var(--gray-600) !important; 
            padding: 8px 18px !important; 
            border-radius: 50px; 
            font-size: 0.9rem;
            position: relative;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .navbar .nav-link:hover, .navbar .nav-link.active { 
            color: var(--primary-dark) !important; 
            background: var(--primary-50) !important; 
            transform: translateY(-0.5px);
        }

        /* Header Search Trigger & Dropdown */
        .search-trigger-btn {
            background: transparent !important;
            color: var(--gray-600) !important;
        }
        .search-trigger-btn:hover {
            background: var(--primary-50) !important;
            color: var(--primary-dark) !important;
            transform: scale(1.05);
        }
        .search-dropdown-menu {
            transform: translateY(10px);
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--gray-200) !important;
        }
        .search-dropdown-input:focus {
            background: white !important;
            border: 1.5px solid var(--primary) !important;
        }
        
        .cart-icon-wrap { 
            position: relative; 
            padding: 8px 12px; 
            border-radius: var(--radius-sm); 
            transition: all 0.2s ease; 
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cart-icon-wrap:hover { 
            background: var(--primary-50); 
            color: var(--primary-dark);
            transform: scale(1.05);
        }
        
        .cart-badge { 
            position: absolute; 
            top: -2px; 
            right: -2px; 
            font-size: 0.65rem; 
            padding: 3px 6px; 
            min-width: 18px; 
            background: linear-gradient(135deg, var(--accent), var(--accent-dark)); 
            color: white;
            border: 2px solid white; 
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(245, 158, 11, 0.25);
            transition: all 0.2s ease;
        }

        .wishlist-icon-wrap { 
            position: relative; 
            padding: 8px 12px; 
            border-radius: var(--radius-sm); 
            transition: all 0.2s ease; 
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .wishlist-icon-wrap:hover { 
            background: #fff5f5; 
            color: #ef4444;
            transform: scale(1.05);
        }

        .wishlist-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 0.65rem;
            padding: 3px 6px;
            min-width: 18px;
            background: #ef4444;
            color: white;
            border: 2px solid white;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.25);
            transition: all 0.2s ease;
        }

        @keyframes badgePop {
            0% { transform: scale(0.6); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .badge-pop {
            animation: badgePop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Responsive Mobile Navbar overrides */
        @media (max-width: 991.98px) {
            .navbar {
                padding: 10px 0 !important;
            }
            .wishlist-icon-wrap, .cart-icon-wrap, .search-trigger-btn, .language-dropdown button, .user-dropdown button {
                width: 40px !important;
                height: 40px !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .navbar-avatar {
                width: 36px !important;
                height: 36px !important;
                font-size: 0.8rem !important;
            }
            .wishlist-badge, .cart-badge {
                top: -3px !important;
                right: -3px !important;
                font-size: 0.6rem !important;
                padding: 2px 5px !important;
                min-width: 16px !important;
            }
            .language-dropdown button i {
                font-size: 1.1rem !important;
            }
            .search-nav-dropdown {
                display: none !important;
            }
            .navbar-scrolled .search-nav-dropdown {
                display: block !important;
            }
            .search-dropdown-menu {
                position: fixed !important;
                top: 62px !important;
                left: 5% !important;
                right: 5% !important;
                width: 90% !important;
                max-width: none !important;
                transform: none !important;
                border-radius: var(--radius-md) !important;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15) !important;
            }
        }

        /* Mobile Search Subheader Styles */
        .mobile-search-container {
            background: #ffffff;
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            padding: 12px 16px;
        }
        .mobile-search-wrap {
            position: relative;
            width: 100%;
        }
        .mobile-search-input {
            border-radius: 50px;
            border: 1.5px solid var(--gray-200);
            padding: 10px 18px;
            padding-right: 48px;
            font-size: 0.85rem;
            width: 100%;
            background: #f8fafc;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        .mobile-search-input:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        .mobile-search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
        }

        /* Dropdowns */
        .dropdown-menu { 
            border: 1px solid var(--gray-200); 
            box-shadow: var(--shadow-lg); 
            border-radius: var(--radius-md); 
            padding: 8px; 
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }
        
        .dropdown-item { 
            border-radius: var(--radius-sm); 
            padding: 8px 16px; 
            font-weight: 500; 
            font-size: 0.88rem;
            color: var(--gray-900);
            transition: all 0.15s ease;
        }
        
        .dropdown-item:hover { 
            background: var(--primary-50); 
            color: var(--primary-dark); 
            transform: translateX(2px);
        }
        
        .navbar-avatar {
            background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
            border: 1.5px solid white;
        }

        /* Landing Layouts styles */
        .hero-section { 
            background: radial-gradient(circle at 80% 20%, #e6fcf5 0%, #f1fcf9 50%, #f8fafc 100%); 
            padding: 90px 0 60px; 
            position: relative; 
            overflow: hidden; 
            border-bottom: 1px solid var(--gray-200);
        }
        
        .hero-title { 
            font-weight: 800; 
            font-size: 3.2rem; 
            line-height: 1.15; 
            color: var(--primary-dark); 
            letter-spacing: -1.5px;
        }
        
        .hero-subtitle { 
            font-size: 1.1rem; 
            color: var(--gray-600); 
            max-width: 550px;
        }
        
        .hero-stats .stat-item { text-align: left; border-right: 1px solid var(--gray-200); padding-right: 24px; }
        .hero-stats .stat-item:last-child { border-right: none; }
        .hero-stats .stat-number { font-size: 1.6rem; font-weight: 800; color: var(--primary-dark); }
        .hero-stats .stat-label { font-size: 0.8rem; color: var(--gray-600); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .section-title { 
            font-weight: 800; 
            font-size: 1.4rem; 
            color: var(--gray-900);
            letter-spacing: -0.4px;
            margin-bottom: 24px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
        }
        
        .section-title .see-all { 
            font-size: 0.88rem; 
            font-weight: 700; 
            color: var(--primary); 
            text-decoration: none; 
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .section-title .see-all:hover { color: var(--primary-dark); }

        /* Minimal Product Card */
        .product-card { 
            background: white; 
            border: 1px solid var(--card-border); 
            border-radius: var(--radius-md); 
            overflow: hidden; 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
            height: 100%; 
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover { 
            transform: translateY(-4px); 
            box-shadow: var(--shadow-md); 
            border-color: var(--gray-300);
        }
        
        .product-card .card-img-wrap { 
            position: relative;
            padding-bottom: 90%;
            background: var(--gray-50);
            overflow: hidden;
            display: block;
        }
        
        .product-card .card-img-wrap img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        
        .product-card:hover .card-img-wrap img {
            transform: scale(1.05);
        }
        
        .product-card .card-body { 
            padding: 16px; 
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-card .card-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .product-card .card-title { 
            font-size: 0.95rem; 
            font-weight: 700; 
            color: var(--gray-900);
            line-height: 1.4;
            margin-bottom: 8px; 
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
        }
        
        .product-card .price-row {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .product-card .price { 
            color: var(--gray-900); 
            font-weight: 800; 
            font-size: 1.15rem; 
            letter-spacing: -0.5px;
        }
        
        .product-card .unit { 
            font-size: 0.75rem; 
            color: var(--gray-600); 
            font-weight: 500; 
        }
        
        .product-card .btn-add-cart { 
            width: 36px;
            height: 36px;
            border-radius: 50%; 
            background: var(--primary-50); 
            color: var(--primary); 
            border: none; 
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease; 
        }
        
        .product-card .btn-add-cart:hover { 
            background: var(--primary); 
            color: white; 
            transform: scale(1.05);
        }

        /* Footer System */
        .footer { 
            background: var(--gray-900); 
            color: rgba(255, 255, 255, 0.95); 
            padding: 70px 0 30px; 
            font-size: 0.88rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .footer h5 { 
            font-weight: 700; 
            margin-bottom: 20px; 
            font-size: 1.05rem; 
            color: white;
        }
        
        .footer a { 
            color: rgba(255, 255, 255, 0.75); 
            text-decoration: none; 
            transition: all 0.15s ease; 
        }
        
        .footer a:hover { 
            color: white; 
        }

        .footer .text-white-50 {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .footer .social-link { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            background: rgba(255,255,255,0.04); 
            color: white; 
            margin-right: 8px; 
            transition: all 0.2s ease; 
        }
        
        .footer .social-link:hover { 
            background: var(--primary); 
            transform: translateY(-2px);
        }
        
        .footer hr { border-color: rgba(255,255,255,0.08); }
        
        /* Step indicators for Checkout */
        .step-indicator { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 40px; 
        }
        
        .step-item { display: flex; align-items: center; gap: 8px; }
        
        .step-circle { 
            width: 30px; 
            height: 30px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 700; 
            font-size: 0.8rem; 
            border: 2px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
        }
        
        .step-circle.active { border-color: var(--primary); background: var(--primary); color: white; }
        .step-circle.done { border-color: var(--primary); background: var(--primary-50); color: var(--primary); }
        
        .step-line { width: 60px; height: 2px; background: var(--gray-200); margin: 0 10px; }
        .step-line.done { background: var(--primary); }
        
        .step-label { font-size: 0.8rem; font-weight: 700; color: var(--gray-600); }
        .step-label.active { color: var(--primary-dark); }

        /* Telegram support bubble */
        .live-chat-btn { 
            position: fixed; 
            bottom: 84px; 
            right: 24px; 
            width: 54px; 
            height: 54px; 
            border-radius: 50%; 
            background: linear-gradient(135deg, #0088cc, #00a8ff);
            color: white; 
            border: none; 
            box-shadow: 0 8px 24px rgba(0, 136, 204, 0.4); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            z-index: 1020; 
            font-size: 1.6rem; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); 
        }
        
        .live-chat-btn:hover { 
            transform: scale(1.1) translateY(-3px); 
            box-shadow: 0 12px 30px rgba(0, 136, 204, 0.6); 
        }

        .live-chat-btn::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 136, 204, 0.4);
            animation: pulse-ring 2s infinite;
            z-index: -1;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        /* Tooltip next to Telegram button */
        .live-chat-tooltip {
            position: fixed;
            bottom: 92px;
            right: 90px;
            background: #0f172a;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 700;
            box-shadow: var(--shadow-md);
            z-index: 1010;
            display: flex;
            align-items: center;
            gap: 6px;
            pointer-events: none;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .live-chat-btn-container:hover .live-chat-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        .back-to-top { 
            position: fixed; 
            bottom: 148px; 
            right: 24px; 
            width: 42px; 
            height: 42px; 
            border-radius: 50%; 
            background: white; 
            color: var(--gray-900); 
            border: 1px solid var(--gray-200); 
            box-shadow: var(--shadow-md); 
            display: none; 
            align-items: center; 
            justify-content: center; 
            z-index: 1020; 
            cursor: pointer; 
            transition: all 0.2s ease; 
        }
        
        .back-to-top:hover { 
            background: var(--gray-100); 
            transform: translateY(-2px); 
        }

        /* Cambodian Local Payment Badge Icon */
        .payment-icon { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            padding: 4px 10px; 
            background: rgba(255,255,255,0.06); 
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 6px; 
            margin-right: 6px; 
            font-size: 0.72rem; 
            color: white; 
            font-weight: 700; 
        }

        /* Mobile layout styling */
        .bottom-nav { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-top: 1px solid var(--gray-200); 
            display: none; 
            z-index: 1030; 
            padding: 6px 0; 
            box-shadow: 0 -2px 12px rgba(0,0,0,0.04); 
        }
        
        .bottom-nav .nav-item { flex: 1; text-align: center; }
        
        .bottom-nav .nav-link { 
            color: var(--gray-600); 
            font-size: 0.68rem; 
            padding: 8px 0 !important; 
            font-weight: 600; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            gap: 3px; 
            min-height: 48px;
            border-radius: 0; 
            background: transparent !important;
        }
        
        .bottom-nav .nav-link i { font-size: 1.4rem; }
        .bottom-nav .nav-link.active { color: var(--primary); }
        
        .bottom-nav .cart-badge-bottom { 
            position: absolute; 
            top: 2px; 
            right: 50%; 
            transform: translateX(18px); 
            font-size: 0.58rem; 
            padding: 2px 5px; 
            min-width: 16px; 
            background: var(--accent); 
            color: white;
            border: 2px solid white; 
            border-radius: 50px; 
            line-height: 1; 
        }

        /* Free Delivery Progress widgets */
        .free-delivery-bar { 
            background: var(--accent-50); 
            border-radius: var(--radius-sm); 
            padding: 14px; 
        }
        
        .free-delivery-progress { 
            height: 6px; 
            border-radius: 3px; 
            background: var(--gray-200); 
            overflow: hidden; 
        }
        
        .free-delivery-progress .bar { 
            height: 100%; 
            border-radius: 3px; 
            background: var(--primary); 
            transition: width 0.4s ease; 
        }

        .alert-custom { 
            border-radius: var(--radius-sm); 
            border: none; 
            padding: 16px 20px; 
            font-weight: 500;
        }

        .hero-chip { 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            background: white; 
            color: var(--gray-900); 
            padding: 8px 18px; 
            border: 1px solid var(--gray-200);
            border-radius: 50px; 
            font-size: 0.85rem; 
            font-weight: 600; 
            text-decoration: none; 
            transition: all 0.2s ease; 
            white-space: nowrap; 
        }
        
        .hero-chip:hover { 
            background: var(--primary-50); 
            color: var(--primary-dark); 
            border-color: var(--primary-light);
            transform: translateY(-1px); 
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-section { padding: 50px 0; }
            .search-box-wrap { min-width: 100%; }
        }

        @media (min-width: 992px) {
            .col-lg-5-custom {
                flex: 0 0 20% !important;
                max-width: 20% !important;
            }
            .container {
                max-width: 96% !important;
                width: 96% !important;
            }
        }
        @media (max-width: 991.98px) {
            .bottom-nav { display: flex; }
            body { padding-bottom: 70px; }
            .back-to-top { bottom: 144px; right: 16px; }
            .live-chat-btn { bottom: 80px; right: 16px; width: 48px; height: 48px; font-size: 1.4rem; }
        }
        /* Custom Pagination Styling */
        .pagination {
            gap: 5px;
            margin-bottom: 0;
        }
        .pagination .page-item .page-link {
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
        }
        .pagination .page-item:hover .page-link:not(.active) {
            background-color: var(--primary-50);
            color: var(--primary);
            border-color: var(--primary-light);
        }
        .pagination .page-link svg {
            width: 1rem !important;
            height: 1rem !important;
        }

        /* Custom Global Button Styling */
        .btn {
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 8px 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }
        
        .btn:active {
            transform: scale(0.97);
        }

        .btn:focus, .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        .btn-success, .btn-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        }

        .btn-success:hover, .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
            transform: translateY(-2px);
            color: white !important;
        }

        .btn-outline-success {
            border: 2px solid var(--primary) !important;
            color: var(--primary) !important;
            background: transparent !important;
            font-weight: 700;
        }

        .btn-outline-success:hover {
            background: var(--primary) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f87171, #ef4444) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25) !important;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35) !important;
            transform: translateY(-2px);
        }
        
        .btn-light {
            background: white !important;
            border: 1px solid var(--gray-200) !important;
            color: var(--gray-900) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .btn-light:hover {
            background: var(--gray-50) !important;
            border-color: var(--gray-300) !important;
            box-shadow: var(--shadow-md) !important;
            transform: translateY(-2px);
        }

        .btn-outline-danger {
            border: 2px solid #ef4444 !important;
            color: #ef4444 !important;
            background: transparent !important;
            font-weight: 700;
        }

        .btn-outline-danger:hover {
            background: #ef4444 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25) !important;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-dark) !important;
            color: var(--primary-dark) !important;
            background: transparent !important;
            font-weight: 700;
        }

        .btn-outline-primary:hover {
            background: var(--primary-dark) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(6, 78, 59, 0.25) !important;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid var(--gray-400) !important;
            color: var(--gray-700) !important;
            background: transparent !important;
            font-weight: 600;
        }

        .btn-outline-secondary:hover {
            background: var(--gray-100) !important;
            color: var(--gray-900) !important;
            border-color: var(--gray-500) !important;
            box-shadow: var(--shadow-sm) !important;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--gray-600) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.25) !important;
        }

        .btn-secondary:hover {
            background: var(--gray-700) !important;
            box-shadow: 0 6px 16px rgba(100, 116, 139, 0.35) !important;
            transform: translateY(-2px);
            color: white !important;
        }

        /* Search Autocomplete Suggestions Styles */
        .search-suggestions-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            z-index: 1050;
            max-height: 280px;
            overflow-y: auto;
            margin-top: 5px;
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            color: var(--gray-900);
            text-decoration: none;
            transition: background 0.2s;
            border-bottom: 1px solid var(--gray-100);
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-item:hover {
            background: var(--primary-50);
            color: var(--primary-dark) !important;
        }
        .suggestion-img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--gray-200);
        }
        .suggestion-name {
            font-size: 0.85rem;
            font-weight: 600;
            flex-grow: 1;
            text-align: left;
        }
        .suggestion-price {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Main Sticky Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FreshMart Logo" style="height: 38px; width: auto; object-fit: contain;" class="d-inline-block align-top me-2 rounded-circle border">
                <span class="d-none d-sm-inline">FreshMart</span>
            </a>
            
            <!-- Navbar Actions Wrapper (Visible on both Mobile and Desktop) -->
            <div class="d-flex align-items-center gap-1 gap-md-2 ms-auto me-2 order-lg-3">
                
                <!-- 1. Search Dropdown -->
                <div class="dropdown search-nav-dropdown">
                    <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center border-0 search-trigger-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="width: 40px; height: 40px;" title="Search Products">
                        <i class="bi bi-search fs-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 search-dropdown-menu" style="width: 320px; max-width: 90vw; margin-top: 10px;">
                        <form action="{{ route('products.search') }}" method="GET" class="position-relative">
                            <input class="form-control rounded-pill pe-5 py-2.5 bg-light border-0 search-dropdown-input" type="search" name="q" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('q') }}" style="font-size: 0.9rem; box-shadow: none;">
                            <button class="btn btn-success rounded-circle position-absolute end-0 top-50 translate-middle-y me-1.5 d-flex align-items-center justify-content-center" type="submit" style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);">
                                <i class="bi bi-search text-white" style="font-size: 0.85rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Language Selector -->
                <div class="dropdown language-dropdown">
                    <button class="btn btn-light rounded-pill border-0 d-flex align-items-center gap-2 px-2.5 py-1.5" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem; height: 40px;">
                        <i class="bi bi-translate text-success fs-5"></i>
                        <span class="d-none d-md-inline">{{ App::getLocale() === 'km' ? 'ខ្មែរ' : 'English' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow py-2" style="min-width: 140px;">
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
                </div>

                @auth
                    <!-- 3. Wishlist Link -->
                    <a class="nav-link wishlist-icon-wrap" href="{{ route('wishlist.index') }}" title="{{ __('messages.my_wishlist') }}">
                        <i class="bi bi-heart fs-5"></i>
                        @php
                            $customerWishlist = auth()->user()->customer;
                            $wishlistCount = $customerWishlist ? \App\Models\Wishlist::where('customer_id', $customerWishlist->id)->count() : 0;
                        @endphp
                        @if($wishlistCount > 0)
                            <span class="badge rounded-pill wishlist-badge bg-danger text-white">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    <!-- 4. Cart Link -->
                    <a class="nav-link cart-icon-wrap" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3 fs-5"></i>
                        @php
                            $customerCart = auth()->user()->customer;
                            $cart = $customerCart ? \App\Models\Cart::where('customer_id', $customerCart->id)->first() : null;
                            $cartCount = $cart ? $cart->items->count() : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="badge rounded-pill cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <!-- 5. Profile Dropdown -->
                    <div class="dropdown user-dropdown">
                        <button class="btn btn-light rounded-circle p-0 border-0 d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
                            @if(Auth::user()->avatar)
                                <img src="{{ str_starts_with(Auth::user()->avatar, 'data:') ? Auth::user()->avatar : asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover;">
                            @else
                                <div class="rounded-circle navbar-avatar text-white d-flex align-items-center justify-content-center fw-bold" style="width:34px; height:34px; font-size:0.9rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow py-2" style="min-width: 190px;">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <span class="d-block fw-bold text-dark text-truncate" style="font-size: 0.88rem;">{{ Auth::user()->name }}</span>
                                <span class="d-block text-muted small text-truncate" style="font-size: 0.72rem;">{{ Auth::user()->email }}</span>
                            </li>
                            @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2 text-danger"></i>{{ __('messages.admin_dashboard') }}</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @endif
                            @if(Auth::user()->role === 'delivery')
                            <li><a class="dropdown-item py-2" href="{{ route('delivery.dashboard') }}"><i class="bi bi-truck me-2 text-primary"></i>{{ __('messages.logistics_portal') }}</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @endif
                            <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="bi bi-person me-2 text-muted"></i>{{ __('messages.my_profile') }}</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2 text-muted"></i>{{ __('messages.my_wishlist') }}</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('customer.orders.index') }}"><i class="bi bi-box me-2 text-muted"></i>{{ __('messages.order_history') }}</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Simple Profile Login Icon for guest on mobile/desktop -->
                    <a class="nav-link wishlist-icon-wrap" href="{{ route('login') }}" title="{{ __('messages.login') }}">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-success btn-sm d-none d-md-inline-block px-3 rounded-pill" style="height: 34px; line-height: 22px; font-weight: 600; background: linear-gradient(135deg, var(--primary-light), var(--primary)); border: none; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);">{{ __('messages.register') }}</a>
                @endauth
            </div>



            <!-- Collapsed Menu (only contains routing links) -->
            <div class="collapse navbar-collapse order-lg-2" id="navbarNav">
                <ul class="navbar-nav me-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.*') && !request()->routeIs('promotions.index') ? 'active' : '' }}" href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('promotions.index') ? 'active' : '' }}" href="{{ route('promotions.index') }}"><i class="bi bi-tag-fill text-danger me-1"></i> {{ __('messages.special_deals') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">{{ __('messages.support') }}</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ __('messages.categories') }}
                        </a>
                        <ul class="dropdown-menu border-0 shadow">
                            @php $categories = \App\Models\Category::where('status','active')->get(); @endphp
                            @foreach($categories as $cat)
                                <li><a class="dropdown-item" href="{{ route('products.category', $cat->id) }}">
                                    {{ Lang::has('messages.' . $cat->category_name) ? __('messages.' . $cat->category_name) : $cat->category_name }}
                                </a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Subheader Search Bar -->
    <div class="d-lg-none mobile-search-container">
        <form action="{{ route('products.search') }}" method="GET" class="mobile-search-wrap">
            <input class="mobile-search-input" type="search" name="q" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('q') }}">
            <button class="mobile-search-btn" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <!-- Mobile Sticky Tab bar -->
    @php
        $mobileCartCount = 0;
        if (auth()->check()) {
            $mobileCustomer = auth()->user()->customer;
            $mobileCart = $mobileCustomer ? \App\Models\Cart::where('customer_id', $mobileCustomer->id)->first() : null;
            $mobileCartCount = $mobileCart ? $mobileCart->items->count() : 0;
        }
    @endphp
    <div class="bottom-nav">
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <i class="bi bi-house{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
                <span>{{ __('messages.home') }}</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') && !request()->routeIs('promotions.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-grid{{ request()->routeIs('products.*') && !request()->routeIs('promotions.index') ? '-fill' : '' }}"></i>
                <span>{{ __('messages.products') }}</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                <i class="bi bi-cart{{ request()->routeIs('cart.*') ? '-fill' : '3' }}"></i>
                @if($mobileCartCount > 0)<span class="cart-badge-bottom">{{ $mobileCartCount }}</span>@endif
                <span>{{ __('messages.cart') }}</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" href="{{ route('wishlist.index') }}">
                <i class="bi bi-heart{{ request()->routeIs('wishlist.*') ? '-fill' : '' }}"></i>
                <span>{{ __('messages.wishlist') }}</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.*') || request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                <i class="bi bi-person{{ request()->routeIs('profile.*') || request()->routeIs('customer.orders.*') ? '-fill' : '' }}"></i>
                <span>{{ __('messages.profile') }}</span>
            </a>
        </div>
    </div>

    <!-- Floating Interactive Buttons -->
    <button class="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="bi bi-chevron-up"></i></button>
    <div class="live-chat-btn-container">
        <div class="live-chat-tooltip"><i class="bi bi-chat-dots-fill text-info"></i> ទំនាក់ទំនងមកយើងខ្ញុំ! (Chat with us)</div>
        <a href="https://t.me/freshmart_cambodia_support" target="_blank" class="live-chat-btn" title="Telegram Customer Support"><i class="bi bi-telegram"></i></a>
    </div>

    <!-- Page Content Container -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show alert-custom d-flex align-items-center gap-2" style="background-color: #ecfdf5; color: #047857;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="box-shadow:none;"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show alert-custom d-flex align-items-center gap-2" style="background-color: #fef2f2; color: #b91c1c;">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="box-shadow:none;"></button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Global Customer Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-basket-fill me-2"></i> FreshMart Cambodia</h5>
                    <p class="text-white-50 small mb-4">{{ __('messages.footer_tagline') }}</p>
                    <div class="d-flex">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="https://t.me/freshmart_cambodia" target="_blank" class="social-link"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>{{ __('messages.catalog') }}</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('products.index') }}">{{ __('messages.all_groceries') }}</a></li>
                        <li><a href="{{ route('promotions.index') }}">{{ __('messages.deals_sales') }}</a></li>
                        @foreach(\App\Models\Category::where('status','active')->take(3)->get() as $cat)
                            <li><a href="{{ route('products.category', $cat->id) }}">{{ Lang::has('messages.' . $cat->category_name) ? __('messages.' . $cat->category_name) : $cat->category_name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>{{ __('messages.account_portal') }}</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        @auth
                            <li><a href="{{ route('profile.index') }}">{{ __('messages.my_profile') }}</a></li>
                            <li><a href="{{ route('wishlist.index') }}">{{ __('messages.my_wishlist') }}</a></li>
                            <li><a href="{{ route('customer.orders.index') }}">{{ __('messages.track_orders') }}</a></li>
                            <li><a href="{{ route('cart.index') }}">{{ __('messages.shopping_basket') }}</a></li>
                        @else
                            <li><a href="{{ route('login') }}">{{ __('messages.access_account') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('messages.join_freshmart') }}</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>{{ __('messages.helplines_faqs') }}</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('contact.index') }}">{{ __('messages.support') }}</a></li>
                        <li><a href="{{ route('contact.index') }}#faqAccordion">{{ __('messages.order_guides') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>{{ __('messages.contact_info') }}</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-white-50 small">
                        <li><i class="bi bi-geo-alt me-2"></i> {!! __('messages.our_address') !!}</li>
                        <li><i class="bi bi-telephone me-2"></i> +855 (0) 12 345 678</li>
                        <li><i class="bi bi-envelope me-2"></i> orders@freshmart.com.kh</li>
                        <li><i class="bi bi-clock me-2"></i> {{ __('messages.daily_hours_footer') }}</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center g-3">
                <div class="col-md-4 text-center text-md-start">
                    <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} {{ __('messages.all_rights_reserved') }}</p>
                </div>
                <div class="col-md-4 text-center">
                    <span class="payment-icon">COD</span>
                    <span class="payment-icon">ABA PAY</span>
                    <span class="payment-icon">WING</span>
                    <span class="payment-icon">BAKONG</span>
                    <span class="payment-icon">VISA</span>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <span class="text-white-50 small">{{ __('messages.veggies_with_love') }} <i class="bi bi-heart-fill text-danger"></i></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Beautiful Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 60px;">
        <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="backdrop-filter: blur(10px); border-radius: var(--radius-sm); box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="d-flex p-1">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i id="toastIcon" class="bi fs-5"></i>
                    <span id="toastMessage" class="fw-medium" style="font-size: 0.9rem;"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="box-shadow: none;"></button>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('scroll', function() {
            var btn = document.querySelector('.back-to-top');
            if (window.scrollY > 300) { btn.style.display = 'flex'; } else { btn.style.display = 'none'; }

            var nav = document.querySelector('.navbar');
            if (nav) {
                if (window.scrollY > 50) {
                    nav.classList.add('navbar-scrolled');
                } else {
                    nav.classList.remove('navbar-scrolled');
                }
            }
        });

        // Auto focus search input when dropdown is shown
        document.addEventListener('shown.bs.dropdown', function (event) {
            const dropdownContainer = event.target;
            const trigger = dropdownContainer.querySelector('.search-trigger-btn');
            if (trigger) {
                const input = dropdownContainer.querySelector('.search-dropdown-input');
                if (input) {
                    input.focus();
                }
            }
        });

        // Global AJAX Toast helper
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            if (!toastEl) return;
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            
            toastMessage.textContent = message;
            
            toastEl.className = 'toast align-items-center text-white border-0';
            if (type === 'success') {
                toastEl.classList.add('bg-success');
                toastIcon.className = 'bi bi-check-circle-fill text-white fs-5';
            } else if (type === 'danger' || type === 'error') {
                toastEl.classList.add('bg-danger');
                toastIcon.className = 'bi bi-exclamation-triangle-fill text-white fs-5';
            } else {
                toastEl.classList.add('bg-warning', 'text-dark');
                toastIcon.className = 'bi bi-info-circle-fill text-dark fs-5';
            }
            
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        }

        // Global Cart Badge updater
        function updateCartBadge(count) {
            const cartWrap = document.querySelector('.cart-icon-wrap');
            if (!cartWrap) return;
            
            let badge = cartWrap.querySelector('.cart-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'badge rounded-pill cart-badge';
                    cartWrap.appendChild(badge);
                }
                badge.textContent = count;
                badge.classList.remove('badge-pop');
                void badge.offsetWidth; // trigger reflow
                badge.classList.add('badge-pop');
            } else {
                if (badge) badge.remove();
            }
        }

        // Global Wishlist Badge updater
        function updateWishlistBadge(count) {
            const wishWrap = document.querySelector('.wishlist-icon-wrap');
            if (!wishWrap) return;
            
            let badge = wishWrap.querySelector('.wishlist-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'badge rounded-pill wishlist-badge';
                    wishWrap.appendChild(badge);
                }
                badge.textContent = count;
                badge.classList.remove('badge-pop');
                void badge.offsetWidth; // trigger reflow
                badge.classList.add('badge-pop');
            } else {
                if (badge) badge.remove();
            }
        }

        // Global Form Interceptor for Add to Cart and Wishlist Toggle
        document.addEventListener('submit', function (event) {
            const form = event.target;
            const action = form.getAttribute('action');
            if (!action) return;

            const isCartAdd = action.includes('/cart/add');
            const isWishlistAdd = action.includes('/wishlist/add');

            if (isCartAdd || isWishlistAdd) {
                // If it is the "Buy Now" button in cart form, let it submit normally
                const submitter = event.submitter;
                if (submitter && submitter.getAttribute('name') === 'buy_now') {
                    return;
                }

                event.preventDefault();
                
                // Disable button to prevent double click
                const submitBtn = submitter || form.querySelector('button[type="submit"]');
                const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (!isWishlistAdd) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    } else {
                        submitBtn.style.opacity = '0.6';
                    }
                }

                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    if (data.success) {
                        showToast(data.message, 'success');
                        
                        if (isCartAdd && typeof data.cart_count !== 'undefined') {
                            updateCartBadge(data.cart_count);
                        }
                        
                        if (isWishlistAdd) {
                            if (typeof data.wishlist_count !== 'undefined') {
                                updateWishlistBadge(data.wishlist_count);
                            }
                            // Update heart icons across the page for this product
                            const prodId = formData.get('product_id');
                            const allWishForms = document.querySelectorAll(`form[action*="/wishlist/add"] input[name="product_id"][value="${prodId}"]`);
                            allWishForms.forEach(input => {
                                const parentForm = input.closest('form');
                                if (parentForm) {
                                    const btn = parentForm.querySelector('button');
                                    const heartIcon = btn ? btn.querySelector('i') : null;
                                    if (heartIcon) {
                                        if (data.in_wishlist) {
                                            heartIcon.className = 'bi bi-heart-fill text-danger';
                                            btn.title = 'Remove from wishlist';
                                        } else {
                                            heartIcon.className = 'bi bi-heart text-muted';
                                            btn.title = 'Add to wishlist';
                                        }
                                    }
                                }
                            });
                            
                            // Also update detail page wishlist button if on product detail page
                            const detailBtn = document.querySelector('button[title*="Wishlist"]');
                            if (detailBtn) {
                                const heartIcon = detailBtn.querySelector('i');
                                if (heartIcon) {
                                    if (data.in_wishlist) {
                                        heartIcon.className = 'bi bi-heart-fill text-danger fs-5';
                                        detailBtn.title = 'Remove from Wishlist';
                                    } else {
                                        heartIcon.className = 'bi bi-heart fs-5';
                                        detailBtn.title = 'Add to Wishlist';
                                    }
                                }
                            }
                        }
                    } else {
                        showToast(data.message || 'Error occurred', 'danger');
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    form.submit();
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (!isWishlistAdd) {
                            submitBtn.innerHTML = originalBtnHtml;
                        } else {
                            submitBtn.style.opacity = '';
                        }
                    }
                });
            }
        });

        // Autocomplete live search suggestions
        const searchInputs = document.querySelectorAll('.search-dropdown-input, .mobile-search-input');
        searchInputs.forEach(input => {
            const form = input.closest('form');
            if (!form) return;

            const suggBox = document.createElement('div');
            suggBox.className = 'search-suggestions-box d-none';
            form.appendChild(suggBox);

            let timeout = null;
            let activeController = null; // Store reference to current AbortController

            input.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(timeout);

                // Cancel any pending fetch request
                if (activeController) {
                    activeController.abort();
                    activeController = null;
                }

                if (query.length < 2) {
                    suggBox.innerHTML = '';
                    suggBox.classList.add('d-none');
                    return;
                }

                timeout = setTimeout(() => {
                    activeController = new AbortController();
                    const signal = activeController.signal;

                    fetch(`/search-suggestions?q=${encodeURIComponent(query)}`, { signal })
                        .then(res => res.json())
                        .then(products => {
                            if (products.length === 0) {
                                suggBox.innerHTML = '<div class="text-muted text-center p-3 small">No products found</div>';
                                suggBox.classList.remove('d-none');
                                return;
                            }

                            suggBox.innerHTML = '';
                            products.forEach(product => {
                                const a = document.createElement('a');
                                a.href = product.url;
                                a.className = 'suggestion-item';
                                a.innerHTML = `
                                    <img src="${product.image_url}" class="suggestion-img">
                                    <span class="suggestion-name">${product.product_name}</span>
                                    <span class="suggestion-price">$${product.price}</span>
                                `;
                                suggBox.appendChild(a);
                            });
                            suggBox.classList.remove('d-none');
                        })
                        .catch(err => {
                            if (err.name === 'AbortError') {
                                // Request was cancelled, ignore
                                return;
                            }
                            console.error('Suggestions error:', err);
                            suggBox.innerHTML = '<div class="text-danger text-center p-3 small">Error fetching suggestions</div>';
                            suggBox.classList.remove('d-none');
                        });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!form.contains(e.target)) {
                    suggBox.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>
