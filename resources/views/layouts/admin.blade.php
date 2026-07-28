<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - FreshMart Admin</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bayon&family=Bokor&family=Carter+One&family=Chenla&family=Cutive+Mono&family=Kdam+Thmor+Pro&family=Khmer&family=Koh+Santepheap:wght@100;300;400;700;900&family=Koulen&family=Luckiest+Guy&family=Merienda:wght@300..900&family=Metal&family=Rowdies:wght@300;400;700&family=Rubik+Vinyl&family=Young+Serif&display=swap" rel="stylesheet">
    
    <!-- Chart.js & Leaflet -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 72px;
            --primary: #10b981;
            --primary-dark: #047857;
            --primary-light: #34d399;
            --primary-50: #ecfdf5;
            --primary-100: #d1fae5;
            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --bg-body: #f0f4f8;
            --card-border: rgba(226, 232, 240, 0.6);
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --blue-500: #3b82f6;
            --blue-50: #eff6ff;
            --red-500: #ef4444;
            --red-50: #fef2f2;
            --amber-500: #f59e0b;
            --amber-50: #fffbeb;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px -2px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);
            --shadow-lg: 0 12px 32px -4px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
            --shadow-xl: 0 20px 50px -12px rgba(0,0,0,0.12);
            --radius-xs: 6px;
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 24px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Khmer', 'Koh Santepheap', sans-serif;
            background: var(--bg-body);
            overflow-x: hidden;
            color: var(--gray-900);
            letter-spacing: -0.2px;
            -webkit-font-smoothing: antialiased;
        }
        
        .wrapper { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #064e3b 0%, #022c22 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 30px rgba(2, 44, 34, 0.12);
        }
        
        .sidebar .brand {
            padding: 22px 24px;
            font-size: 1.3rem;
            font-weight: 800;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }
        
        .sidebar .brand a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar .brand i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 10px;
        }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 4px; }
        
        .sidebar .nav-section {
            padding: 18px 14px 6px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            color: rgba(255,255,255,0.3);
            font-weight: 700;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 10px 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            margin-bottom: 1px;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.07);
            color: white;
            transform: translateX(3px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(52, 211, 153, 0.12);
            color: #34d399;
            font-weight: 600;
            box-shadow: inset 3px 0 0 #34d399;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }
        
        .sidebar .nav-link .badge {
            margin-left: auto;
            font-size: 0.68rem;
            padding: 4px 8px;
        }
        
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(0,0,0,0.1);
        }
        .sidebar-footer .nav-link {
            color: rgba(255,255,255,0.45);
            padding: 8px 0;
            background: transparent;
            border: none;
        }
        .sidebar-footer .nav-link:hover { color: white; transform: none; }
        
        /* Sidebar Collapsed */
        .sidebar.collapsed { width: var(--sidebar-collapsed-width); overflow: hidden; }
        .sidebar.collapsed .brand { padding: 20px 0; justify-content: center; }
        .sidebar.collapsed .brand a { justify-content: center; }
        .sidebar.collapsed .brand img { margin: 0; }
        .sidebar.collapsed .brand span { display: none; }
        .sidebar.collapsed .sidebar-scroll { padding: 12px 0; }
        .sidebar.collapsed .nav-section { display: none; }
        .sidebar.collapsed .nav-link {
            justify-content: center; padding: 11px 10px; gap: 0; font-size: 0;
            position: relative;
        }
        .sidebar.collapsed .nav-link i { font-size: 1.15rem; width: auto; margin: 0; }
        .sidebar.collapsed .nav-link .badge { display: none; }
        .sidebar.collapsed .nav-link.active { box-shadow: inset 3px 0 0 #34d399; }
        .sidebar.collapsed .sidebar-footer {
            padding: 12px 0; display: flex; flex-direction: column; align-items: center;
        }
        .sidebar.collapsed .sidebar-footer .nav-link,
        .sidebar.collapsed .sidebar-footer form button {
            font-size: 0; padding: 10px; justify-content: center;
        }
        .sidebar.collapsed .sidebar-footer .nav-link i,
        .sidebar.collapsed .sidebar-footer form button i { font-size: 1.15rem; width: auto; }
        .sidebar.collapsed .sidebar-footer form button { width: 40px; height: 40px; }
        .sidebar.collapsed .nav-link:hover { transform: none; }
        body.sidebar-collapsed .content { margin-left: var(--sidebar-collapsed-width); }
        .sidebar-collapse-toggle { display: none; }
        @media (min-width: 993px) { .sidebar-collapse-toggle { display: flex; } }
        
        /* ===== MAIN CONTENT & TOPBAR ===== */
        .content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .topbar {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .topbar-left .page-title { font-weight: 800; font-size: 1.2rem; margin: 0; letter-spacing: -0.4px; color: var(--gray-900); }
        
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        
        .topbar-right .btn-icon {
            width: 38px; height: 38px;
            border-radius: var(--radius-xs);
            border: 1px solid var(--gray-200);
            background: white;
            display: flex; align-items: center; justify-content: center;
            color: var(--gray-500);
            transition: all 0.2s ease;
            position: relative;
        }
        
        .topbar-right .btn-icon:hover {
            background: var(--primary-50);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateY(-1px);
        }
        
        .topbar-right .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 5px 12px;
            border-radius: var(--radius-xs);
            background: white;
            border: 1px solid var(--gray-200);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .topbar-right .user-info:hover {
            background: var(--primary-50);
            border-color: var(--primary-light);
        }
        
        .topbar-right .user-info .avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.78rem;
        }
        
        .page-content { padding: 28px; flex: 1; }

        /* ===== CARDS ===== */
        .card-custom {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
            overflow: hidden;
        }
        
        .card-custom:hover { box-shadow: var(--shadow-md); }
        
        .card-custom .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-100);
            padding: 18px 22px;
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-custom .card-body { padding: 22px; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none;
            border-radius: var(--radius-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
            box-shadow: var(--shadow-sm);
            background: white;
            border: 1px solid var(--card-border);
        }
        
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        
        .stat-card .stat-body { padding: 22px; position: relative; z-index: 2; }
        
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        
        .stat-card .stat-number { font-size: 1.8rem; font-weight: 800; line-height: 1.2; margin-top: 6px; letter-spacing: -1px; }
        .stat-card .stat-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== TABLES ===== */
        .table-container {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        
        .table-custom { margin-bottom: 0; }
        .table-custom thead th {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            padding: 12px 18px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-500);
            font-weight: 700;
            white-space: nowrap;
        }
        
        .table-custom tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.85rem;
            color: var(--gray-800);
        }
        
        .table-custom tbody tr { transition: background 0.15s ease; }
        .table-custom tbody tr:hover { background: #f8fafc; }
        .table-custom tbody tr:last-child td { border-bottom: none; }

        /* ===== BADGES ===== */
        .badge-status {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-btns {
            display: inline-flex;
            gap: 6px;
            align-items: center;
        }
        
        .action-btns .btn-action {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-xs);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
            padding: 0;
        }
        
        .action-btns .btn-action:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        
        .action-btns .btn-view {
            background: var(--blue-50);
            color: var(--blue-500);
        }
        .action-btns .btn-view:hover { background: var(--blue-500); color: white; }
        
        .action-btns .btn-edit {
            background: var(--amber-50);
            color: var(--amber-500);
        }
        .action-btns .btn-edit:hover { background: var(--amber-500); color: white; }
        
        .action-btns .btn-upload {
            background: var(--primary-50);
            color: var(--primary);
        }
        .action-btns .btn-upload:hover { background: var(--primary); color: white; }
        
        .action-btns .btn-delete {
            background: var(--red-50);
            color: var(--red-500);
        }
        .action-btns .btn-delete:hover { background: var(--red-500); color: white; }

        /* ===== FORMS ===== */
        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--gray-200);
            padding: 10px 16px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            background-color: white;
            color: var(--gray-800);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        
        .form-label { font-weight: 600; font-size: 0.8rem; color: var(--gray-700); margin-bottom: 6px; }

        .form-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 28px;
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--radius-sm);
            padding: 10px 22px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }
        
        .btn-success { background: var(--primary); border: none; color: white; }
        .btn-success:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-md); }
        
        .btn-primary { background: var(--blue-500); border: none; color: white; }
        .btn-primary:hover { background: #2563eb; transform: translateY(-1px); box-shadow: var(--shadow-md); }
        
        .btn-warning { background: var(--accent); border: none; color: white; }
        .btn-warning:hover { background: #d97706; transform: translateY(-1px); box-shadow: var(--shadow-md); }
        
        .btn-danger { background: var(--red-500); border: none; color: white; }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); box-shadow: var(--shadow-md); }
        
        .btn-info { background: #06b6d4; border: none; color: white; }
        .btn-info:hover { background: #0891b2; transform: translateY(-1px); box-shadow: var(--shadow-md); }
        
        .btn-outline-primary { border: 1.5px solid var(--primary); color: var(--primary); background: transparent; }
        .btn-outline-primary:hover { background: var(--primary); color: white; border-color: var(--primary); }
        
        .btn-outline-secondary { border: 1.5px solid var(--gray-300); color: var(--gray-600); background: white; }
        .btn-outline-secondary:hover { background: var(--gray-100); color: var(--gray-800); border-color: var(--gray-400); }
        
        .btn-sm { padding: 7px 16px; font-size: 0.78rem; border-radius: 8px; }
        .btn-xs { padding: 4px 10px; font-size: 0.72rem; border-radius: 6px; }

        /* ===== UPLOAD ZONE ===== */
        .upload-zone {
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-md);
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--gray-50);
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: var(--primary);
            background: var(--primary-50);
        }
        .upload-zone i { font-size: 2.5rem; color: var(--gray-400); margin-bottom: 10px; }
        .upload-zone:hover i, .upload-zone.dragover i { color: var(--primary); }
        .upload-zone p { color: var(--gray-500); margin: 0; font-size: 0.85rem; }
        .upload-zone .btn { margin-top: 10px; }

        /* ===== MODAL CUSTOM ===== */
        .modal-custom .modal-header {
            border-bottom: 1px solid var(--gray-100);
            padding: 18px 24px;
            background: white;
        }
        .modal-custom .modal-title { font-weight: 700; font-size: 1rem; }
        .modal-custom .modal-body { padding: 24px; }
        .modal-custom .modal-footer {
            border-top: 1px solid var(--gray-100);
            padding: 14px 24px;
            background: var(--gray-50);
        }


        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-header-left h4 {
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-header-left p {
            margin: 4px 0 0;
            font-size: 0.82rem;
            color: var(--gray-500);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }
        .empty-state i {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 12px;
        }
        .empty-state h5 { color: var(--gray-600); font-weight: 700; margin-bottom: 6px; }
        .empty-state p { color: var(--gray-400); font-size: 0.85rem; }

        /* ===== PAGINATION ===== */
        .pagination { gap: 4px; margin-bottom: 0; }
        .pagination .page-item .page-link {
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 7px 14px;
            font-weight: 600;
            font-size: 0.82rem;
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

        /* ===== FOOTER ===== */
        .footer-admin {
            text-align: center;
            padding: 20px;
            color: var(--gray-400);
            font-size: 0.75rem;
            border-top: 1px solid var(--gray-200);
            background: white;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        /* ===== ALERTS ===== */
        .alert-success-custom {
            background-color: var(--primary-50);
            color: var(--primary-dark);
            border: 1px solid var(--primary-100);
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error-custom {
            background-color: var(--red-50);
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ===== RESPONSIVE ===== */

        /* --- Large Tablet (≤1200px) --- */
        @media (max-width: 1200px) {
            :root { --sidebar-width: 240px; }
            .page-content { padding: 24px 20px; }
            .topbar { padding: 12px 20px; }
            .card-custom .card-header { padding: 16px 20px; }
            .card-custom .card-body { padding: 20px; }
        }

        /* --- Tablet (≤992px): Sidebar becomes a slide-out drawer --- */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 270px !important;
                z-index: 1100;
            }
            .sidebar.show { transform: translateX(0); }
            .sidebar.collapsed { transform: translateX(-100%); }
            .content { margin-left: 0 !important; }
            .sidebar-overlay.show { display: block; }
            .topbar { padding: 12px 16px; }
            .page-content { padding: 20px 14px; }
            .sidebar-collapse-toggle { display: none !important; }

            /* Sidebar touch-friendly nav links */
            .sidebar .nav-link {
                padding: 12px 16px;
                min-height: 44px;
                font-size: 0.9rem;
            }

            /* Page header stacks on tablet */
            .page-header { flex-wrap: wrap; gap: 10px; }
            .page-header-left { flex: 1 1 100%; }
            .page-header > a,
            .page-header > button,
            .page-header > div:last-child { flex-shrink: 0; }
            .page-header .btn { white-space: nowrap; }

            /* Stat cards - 2 columns */
            .row .col-lg-3,
            .row .col-xl-3 { flex: 0 0 50%; max-width: 50%; }

            /* Dashboard grid columns */
            .row .col-lg-6,
            .row .col-xl-8,
            .row .col-xl-4,
            .row .col-lg-7,
            .row .col-lg-5 { flex: 0 0 100%; max-width: 100%; }

            /* Topbar page title shorter on tablet */
            .topbar-left .page-title { font-size: 1rem; }

            /* Welcome bar on dashboard */
            .welcome-bar, .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4.p-4 { gap: 10px; }

            /* Charts responsive height */
            .card-body canvas { max-height: 280px; }

            /* Card header: prevent overflow */
            .card-custom .card-header { font-size: 0.88rem; }
            .card-custom .card-header .fw-bold { font-size: 0.88rem; }

            /* Detail page columns */
            .row .col-md-8,
            .row .col-md-4 { flex: 0 0 100%; max-width: 100%; }

            /* Filter form rows */
            .row.g-3 .col-md-4,
            .row.g-3 .col-md-3,
            .row.g-3 .col-md-2 { flex: 0 0 100%; max-width: 100%; }

            /* Status filter tabs wrap */
            .d-flex.flex-wrap.gap-2 { gap: 6px !important; }

            /* Reports page */
            .row .col-md-6 { flex: 0 0 50%; max-width: 50%; }
        }

        /* --- Small Tablet / Large Phone (≤768px) --- */
        @media (max-width: 768px) {
            .topbar { padding: 10px 14px; }
            .topbar-left .page-title { font-size: 0.92rem; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .page-content { padding: 14px 12px; }

            /* Hide username text in topbar, show only avatar */
            .topbar-right .user-info span { display: none !important; }
            .topbar-right .user-info { padding: 4px 10px; gap: 6px; min-height: 40px; }
            .topbar-right .user-info .bi-chevron-down { display: none; }
            .topbar-right { gap: 8px; }

            /* Topbar buttons larger for touch */
            .topbar-right .btn-icon { width: 40px; height: 40px; }

            /* Stat cards - 2 columns */
            .row .col-lg-3,
            .row .col-xl-3,
            .row .col-sm-6 { flex: 0 0 50%; max-width: 50%; }
            .row .col-md-6 { flex: 0 0 100%; max-width: 100%; }

            /* Stat card compact */
            .stat-card .stat-body { padding: 16px; }
            .stat-card .stat-number { font-size: 1.5rem; }
            .stat-card .stat-icon { width: 38px; height: 38px; font-size: 1rem; }
            .stat-card .stat-label { font-size: 0.72rem; }
            .stat-card .px-4.pb-3 { padding-left: 12px !important; padding-right: 12px !important; }

            /* Tables: horizontal scroll */
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .table-custom thead th { padding: 10px 12px; font-size: 0.65rem; }
            .table-custom tbody td { padding: 11px 12px; font-size: 0.8rem; }

            /* Action buttons smaller */
            .action-btns .btn-action { width: 36px; height: 36px; font-size: 0.82rem; }

            /* Page header fully stacked */
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .page-header > a,
            .page-header > button,
            .page-header > form,
            .page-header > div:last-child { width: 100%; }

            /* Form card padding reduced */
            .form-card { padding: 18px 16px; }

            /* Buttons full-width on mobile pages */
            .page-header .btn { width: 100%; justify-content: center; }

            /* Card body reduced padding */
            .card-custom .card-body { padding: 16px; }
            .card-custom .card-header { padding: 14px 16px; }

            /* Badge status smaller */
            .badge-status { font-size: 0.65rem; padding: 4px 9px; }

            /* Modal full width */
            .modal-custom .modal-dialog { margin: 8px; }
            .modal-custom .modal-body { padding: 16px; }
            .modal-custom .modal-header { padding: 14px 16px; }
            .modal-custom .modal-footer { padding: 12px 16px; }

            /* Pagination compact */
            .pagination .page-item .page-link { padding: 5px 10px; font-size: 0.77rem; }

            /* Charts responsive height */
            .card-body canvas { max-height: 240px; }
            .card-body > div[style*="height: 320px"],
            .card-body > div[style*="height: 300px"],
            .card-body > div[style*="height: 260px"] { height: 220px !important; }

            /* Delivery zone map */
            #zonesMap { height: 280px !important; }
            #adminOrderMap { height: 180px !important; }

            /* Filter form rows stack */
            .row.g-3 .col-md-4,
            .row.g-3 .col-md-3,
            .row.g-3 .col-md-2 { flex: 0 0 100%; max-width: 100%; }

            /* Reports stat cards */
            .row .col-md-6 { flex: 0 0 100%; max-width: 100%; }

            /* Dashboard table columns */
            .row .col-lg-6,
            .row .col-lg-7,
            .row .col-lg-5 { flex: 0 0 100%; max-width: 100%; }

            /* Welcome bar */
            .p-4.bg-white.rounded-3.shadow-sm.border { padding: 16px !important; }
            .p-4.bg-white.rounded-3.shadow-sm.border h4 { font-size: 1rem; }
            .p-4.bg-white.rounded-3.shadow-sm.border p { font-size: 0.8rem; }

            /* Status filter pills */
            .btn-xs.rounded-pill { font-size: 0.72rem !important; padding: 5px 12px !important; }

            /* Card header title + action layout */
            .card-custom .card-header { gap: 8px; }
            .card-custom .card-header .btn { font-size: 0.72rem; padding: 4px 10px; }

            /* Table header entry count */
            .card-custom .card-header .text-muted.small { font-size: 0.7rem; }

            /* Alert messages */
            .alert-success-custom,
            .alert-error-custom { padding: 10px 14px; font-size: 0.82rem; }

            /* Empty state */
            .empty-state { padding: 32px 14px; }
            .empty-state h5 { font-size: 1rem; }
            .empty-state p { font-size: 0.8rem; }

            /* Upload zone */
            .upload-zone { padding: 22px 14px; }
            .upload-zone i { font-size: 2rem; }
            .upload-zone p { font-size: 0.8rem; }

            /* SweetAlert2 popup responsive */
            .swal2-popup { width: 90vw !important; max-width: 360px !important; padding: 1.5em !important; }
            .swal2-title { font-size: 1rem !important; }
            .swal2-html-container { font-size: 0.82rem !important; margin: 0.5em 0 !important; }
            .swal2-actions { gap: 0.5rem !important; }
            .swal2-styled { font-size: 0.82rem !important; padding: 0.5em 1.2em !important; }

            /* Detail page: sidebar cards */
            .row .col-md-4 .card-custom { margin-bottom: 12px; }
            .modal-detail-row { padding: 8px 0; }
            .modal-detail-label { font-size: 0.72rem; }
            .modal-detail-value { font-size: 0.82rem; }

            /* Form actions */
            .d-flex.gap-2.mt-4 { flex-direction: column; gap: 8px !important; }
            .d-flex.gap-2.mt-4 .btn { width: 100%; }

            /* Card footer */
            .card-footer { padding: 10px 14px; }
        }

        /* --- Mobile (≤576px): Single column, minimal padding --- */
        @media (max-width: 576px) {
            .topbar { padding: 9px 12px; gap: 8px; }
            .topbar-left { gap: 8px; min-width: 0; }
            .topbar-left .page-title { font-size: 0.85rem; max-width: 120px; }
            .page-content { padding: 12px 10px; }

            /* All columns full-width */
            .row .col-lg-3,
            .row .col-xl-3,
            .row .col-md-6,
            .row .col-sm-6,
            .row .col-lg-6,
            .row .col-xl-8,
            .row .col-xl-4,
            .row .col-lg-7,
            .row .col-lg-5,
            .row .col-md-8,
            .row .col-md-4 { flex: 0 0 100%; max-width: 100%; }

            /* Stat cards full width */
            .stat-card .stat-body { padding: 14px; }
            .stat-card .stat-number { font-size: 1.4rem; }
            .stat-card .stat-label { font-size: 0.72rem; }
            .stat-card .stat-icon { width: 34px; height: 34px; font-size: 0.95rem; }

            /* Table cells even more compact */
            .table-custom thead th { padding: 8px 10px; font-size: 0.6rem; }
            .table-custom tbody td { padding: 10px 10px; font-size: 0.77rem; }

            /* Action buttons tiny */
            .action-btns .btn-action { width: 34px; height: 34px; font-size: 0.78rem; }
            .action-btns { gap: 4px; }

            /* Buttons full-width stacked */
            .btn { font-size: 0.82rem; padding: 9px 16px; }
            .btn-sm { padding: 6px 12px; font-size: 0.75rem; }

            /* Cards */
            .card-custom .card-body { padding: 12px; }
            .card-custom .card-header { padding: 12px 14px; font-size: 0.85rem; }
            .card-custom .card-header .fw-bold { font-size: 0.82rem; }

            /* Form controls */
            .form-control, .form-select { padding: 9px 12px; font-size: 0.82rem; }
            .form-label { font-size: 0.77rem; }
            .form-card { padding: 14px 12px; }

            /* Page header */
            .page-header { margin-bottom: 16px; }
            .page-header-left h4 { font-size: 1rem; }
            .page-header-left p { font-size: 0.78rem; }

            /* Topbar right: larger buttons for touch */
            .topbar-right .btn-icon { width: 40px; height: 40px; }

            /* Hide language label, show only icon */
            .topbar-right .dropdown button span { display: none; }

            /* Modals */
            .modal-custom .modal-dialog { margin: 6px; }
            .modal-custom .modal-body { padding: 14px; }
            .modal-custom .modal-header { padding: 12px 14px; }

            /* Pagination */
            .pagination .page-item .page-link { padding: 4px 8px; font-size: 0.72rem; border-radius: 6px; }

            /* Empty state compact */
            .empty-state { padding: 32px 12px; }
            .empty-state i { font-size: 2.4rem; }

            /* Upload zone */
            .upload-zone { padding: 20px 14px; }
            .upload-zone i { font-size: 2rem; }

            /* Alert compact */
            .alert-success-custom,
            .alert-error-custom { padding: 10px 14px; font-size: 0.82rem; }

            /* Charts fully responsive */
            .card-body canvas { max-height: 200px; }
            .card-body > div[style*="height: 320px"],
            .card-body > div[style*="height: 300px"],
            .card-body > div[style*="height: 260px"],
            .card-body > div[style*="height: 230px"] { height: 180px !important; }

            /* Map containers */
            #zonesMap { height: 240px !important; }
            #adminOrderMap { height: 160px !important; }

            /* Welcome bar */
            .p-4.bg-white.rounded-3.shadow-sm.border { padding: 14px !important; }
            .p-4.bg-white.rounded-3.shadow-sm.border h4 { font-size: 0.92rem; }
            .p-4.bg-white.rounded-3.shadow-sm.border .d-flex.gap-2 { gap: 6px !important; }
            .p-4.bg-white.rounded-3.shadow-sm.border .btn { font-size: 0.75rem; padding: 6px 12px; }

            /* Status filter pills */
            .btn-xs.rounded-pill { font-size: 0.68rem !important; padding: 4px 10px !important; }

            /* Table entry count text */
            .card-custom .card-header .text-muted.small { font-size: 0.65rem; }

            /* SweetAlert2 popup */
            .swal2-popup { width: 88vw !important; max-width: 320px !important; padding: 1.2em !important; }
            .swal2-title { font-size: 0.92rem !important; }
            .swal2-html-container { font-size: 0.78rem !important; }

            /* Detail page */
            .modal-detail-row { padding: 6px 0; }
            .modal-detail-label { font-size: 0.68rem; }
            .modal-detail-value { font-size: 0.78rem; }

            /* Reports page header */
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4 h4 { font-size: 1rem; }

            /* Footer */
            .footer-admin { padding: 14px; font-size: 0.7rem; }
        }

        /* --- Extra Small (≤420px): Ultra compact --- */
        @media (max-width: 420px) {
            .topbar-left .page-title { display: none; }
            .page-content { padding: 10px 8px; }
            .card-custom .card-body { padding: 10px; }
            .table-custom thead th { padding: 6px 8px; }
            .table-custom tbody td { padding: 8px; }

            /* Stat cards ultra compact */
            .stat-card .stat-body { padding: 12px; }
            .stat-card .stat-number { font-size: 1.2rem; }
            .stat-card .stat-icon { width: 30px; height: 30px; font-size: 0.85rem; border-radius: 6px; }

            /* Topbar buttons always touch-friendly */
            .topbar-right .btn-icon { width: 38px; height: 38px; font-size: 0.82rem; }

            /* Cards */
            .card-custom .card-header { padding: 10px 12px; font-size: 0.8rem; }

            /* Form */
            .form-control, .form-select { padding: 8px 10px; font-size: 0.78rem; }
            .form-label { font-size: 0.72rem; }
            .form-card { padding: 12px 10px; }

            /* Buttons */
            .btn { font-size: 0.78rem; padding: 8px 14px; }
            .btn-sm { padding: 5px 10px; font-size: 0.72rem; }

            /* Charts */
            .card-body canvas { max-height: 170px; }
            .card-body > div[style*="height"] { height: 160px !important; }

            /* Map */
            #zonesMap { height: 200px !important; }

            /* Action buttons minimal gap */
            .action-btns { gap: 3px; }
            .action-btns .btn-action { width: 32px; height: 32px; font-size: 0.74rem; }

            /* Alert compact */
            .alert-success-custom,
            .alert-error-custom { padding: 8px 10px; font-size: 0.78rem; gap: 6px; }

            /* SweetAlert2 */
            .swal2-popup { width: 85vw !important; max-width: 280px !important; padding: 1em !important; }
            .swal2-title { font-size: 0.85rem !important; }

            /* Pagination */
            .pagination .page-item .page-link { padding: 3px 6px; font-size: 0.68rem; }

            /* Welcome bar */
            .p-4.bg-white.rounded-3.shadow-sm.border { padding: 10px !important; }
            .p-4.bg-white.rounded-3.shadow-sm.border h4 { font-size: 0.85rem; }

            /* Upload zone */
            .upload-zone { padding: 16px 10px; }
            .upload-zone p { font-size: 0.75rem; }
        }


        /* ===================================================================
           PAGE-LEVEL RESPONSIVE — Targets all admin pages via shared classes
           =================================================================== */

        /* --- Card header search bar (input + button row) --- */
        @media (max-width: 768px) {
            /* Card header flex row wraps on tablet */
            .card-custom .card-header { flex-wrap: wrap; row-gap: 10px; }
            .card-custom .card-header > form,
            .card-custom .card-header > div.input-group { width: 100%; }
            .card-custom .card-header .input-group { min-width: 0; }
            .card-custom .card-header .input-group input { min-width: 0; flex: 1; }
        }

        @media (max-width: 576px) {
            /* Card header: title left, count right on same row; search below */
            .card-custom .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-custom .card-header > form,
            .card-custom .card-header > div.input-group,
            .card-custom .card-header > div.d-flex { width: 100%; }

            /* Inline search buttons become full-width */
            .card-custom .card-header .btn-sm { width: auto; min-width: 80px; }
        }

        /* --- Table: hide secondary columns on small screens --- */
        @media (max-width: 576px) {
            /* Columns to hide on mobile — secondary info */
            .table-custom th.d-none-mobile,
            .table-custom td.d-none-mobile { display: none !important; }

            /* Table min-width forces horizontal scroll safely */
            .table-custom { min-width: 480px; }
        }
        @media (max-width: 420px) {
            .table-custom { min-width: 380px; }
        }

        /* --- Products page: action button group in page header --- */
        @media (max-width: 576px) {
            .page-header > div.d-flex.gap-2 {
                width: 100%;
                flex-wrap: wrap;
            }
            .page-header > div.d-flex.gap-2 > .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* --- Orders / Deliveries / Payments: status pill filter bar --- */
        @media (max-width: 576px) {
            /* Status filter tabs: wrap, smaller pills */
            .d-flex.flex-wrap.gap-2.mb-4 { gap: 5px !important; margin-bottom: 14px !important; }
            .d-flex.flex-wrap.gap-2.mb-4 .btn-xs { font-size: 0.66rem !important; padding: 4px 9px !important; }

            /* Advanced filter form */
            #advancedFilters .card-body { padding: 14px !important; }
            #advancedFilters .row.g-3 > [class*="col-"] { flex: 0 0 100%; max-width: 100%; }
            #advancedFilters .d-flex.align-items-end.gap-2 { gap: 6px !important; }
        }
        @media (max-width: 768px) {
            /* Advanced filter rows always stack on small tablet too */
            #advancedFilters .row.g-3 > .col-md-4,
            #advancedFilters .row.g-3 > .col-md-3,
            #advancedFilters .row.g-3 > .col-md-2 { flex: 0 0 100%; max-width: 100%; }
        }

        /* --- Dashboard: welcome bar buttons --- */
        @media (max-width: 576px) {
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4.p-4.bg-white {
                flex-direction: column;
                align-items: flex-start !important;
            }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4.p-4.bg-white .d-flex.gap-2 {
                width: 100%;
            }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4.p-4.bg-white .d-flex.gap-2 .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* --- Detail/Show pages: two-column sidebar layout --- */
        @media (max-width: 768px) {
            /* col-lg-8 / col-lg-4 detail page splits → full width */
            .row > .col-lg-8,
            .row > .col-lg-4 { flex: 0 0 100%; max-width: 100%; }
        }

        /* --- Create/Edit form pages: 2-col form grids → 1-col --- */
        @media (max-width: 768px) {
            .form-card .row.g-3 > .col-md-6,
            .form-card .row.g-3 > .col-md-4,
            .form-card .row.g-3 > .col-md-3,
            .form-card .row.g-3 > .col-md-8 { flex: 0 0 100%; max-width: 100%; }

            /* Form action buttons (Save / Cancel) at bottom */
            .form-card .d-flex.gap-2.mt-4,
            .form-card .d-flex.gap-3.mt-4 { flex-direction: column; gap: 8px !important; }
            .form-card .d-flex.gap-2.mt-4 .btn,
            .form-card .d-flex.gap-3.mt-4 .btn { width: 100%; justify-content: center; }
        }
        @media (max-width: 576px) {
            /* Outside form-card too (e.g. bottom-of-page form actions) */
            .d-flex.gap-2.mt-4 .btn,
            .d-flex.gap-3.mt-4 .btn { width: 100%; }
        }

        /* --- Reports page: date range / filter header bar --- */
        @media (max-width: 768px) {
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4:not(.p-4) {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4:not(.p-4) > form { width: 100%; }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4:not(.p-4) form .d-flex { flex-wrap: wrap; gap: 8px !important; }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4:not(.p-4) form input[type="date"] { flex: 1 1 120px; min-width: 120px; }
            .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4:not(.p-4) form .btn { flex-shrink: 0; }
        }

        /* --- Inventory / Purchase Orders: stat/info mini-cards --- */
        @media (max-width: 576px) {
            /* Info row with multiple small badges or stat rows */
            .d-flex.flex-wrap.gap-3.mb-4,
            .d-flex.flex-wrap.gap-4.mb-4 { gap: 10px !important; }
        }

        /* --- Notification / Activity log detail rows --- */
        @media (max-width: 576px) {
            .d-flex.align-items-start.gap-3.py-3 { gap: 10px !important; }
            .d-flex.align-items-start.gap-3.py-3 .flex-grow-1 p,
            .d-flex.align-items-start.gap-3.py-3 .flex-grow-1 small { font-size: 0.77rem; }
        }

        /* --- Settings page: payment method checkboxes --- */
        @media (max-width: 576px) {
            .d-flex.flex-wrap.gap-3.align-items-center { gap: 8px !important; }
        }

        /* --- Delivery & Order detail: info grid --- */
        @media (max-width: 576px) {
            .row.g-3 .col-sm-6 { flex: 0 0 100%; max-width: 100%; }
        }

        /* --- Global: ensure all images are max-width --- */
        .card-custom img,
        .card-body img { max-width: 100%; height: auto; }

        /* --- Mobile bottom nav hint (visible only on small screens) --- */
        @media (max-width: 576px) {
            /* Add bottom padding to avoid content cut behind browser UI */
            .page-content { padding-bottom: 24px; }
        }



        /* ===== SWEET ALERT OVERRIDES ===== */
        .swal2-popup { border-radius: var(--radius-md) !important; font-family: 'Inter', sans-serif !important; }
        .swal2-title { font-weight: 700 !important; font-size: 1.1rem !important; }
        .swal2-html-container { font-size: 0.88rem !important; }

        /* ===== MOBILE BOTTOM NAVIGATION ===== */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid var(--gray-200);
            z-index: 1050;
            padding: 6px 0;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
        }
        .bottom-nav .nav-item { flex: 1; }
        .bottom-nav .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            padding: 6px 0;
            color: var(--gray-500) !important;
            text-decoration: none;
            font-size: 0.62rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border-radius: 8px;
            min-height: 48px;
        }
        .bottom-nav .nav-link i {
            font-size: 1.15rem;
            transition: all 0.2s ease;
        }
        .bottom-nav .nav-link.active,
        .bottom-nav .nav-link:hover {
            color: var(--primary) !important;
            background: var(--primary-50);
        }
        .bottom-nav .nav-link.active i {
            transform: scale(1.1);
        }

        @media (max-width: 992px) {
            .bottom-nav { display: flex; }
            body { padding-bottom: 68px; }
        }

        /* ===== BACK TO TOP BUTTON ===== */
        .back-to-top {
            position: fixed;
            bottom: 84px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 1040;
            box-shadow: 0 4px 12px rgba(16,185,129,0.35);
            transition: all 0.3s ease;
        }
        .back-to-top:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16,185,129,0.45); }
        .back-to-top.show { display: flex; }
        @media (max-width: 992px) { .back-to-top { bottom: 80px; right: 16px; width: 42px; height: 42px; } }

        /* ===== ENHANCED MOBILE TOUCH TARGETS ===== */
        @media (max-width: 992px) {
            .btn-xs { min-height: 36px; font-size: 0.76rem !important; padding: 6px 14px !important; }
            .d-flex.flex-wrap.gap-2.mb-4 .btn-xs { min-height: 38px; }
            .topbar-right .btn-icon { min-width: 40px; min-height: 40px; }
            .topbar-right .dropdown .btn-icon { min-width: 40px; min-height: 40px; }
        }
        @media (max-width: 576px) {
            .btn-xs { min-height: 38px; font-size: 0.74rem !important; padding: 7px 12px !important; border-radius: 8px; }
            .topbar-right .btn-icon { width: 40px; height: 40px; }
        }

        /* ===== ENHANCED MOBILE TABLE COLUMNS ===== */
        @media (max-width: 576px) {
            .d-none-mobile { display: none !important; }
        }
        @media (max-width: 768px) {
            .d-none-tablet { display: none !important; }
        }

        /* ===== ENHANCED MOBILE STAT CARDS ===== */
        @media (max-width: 576px) {
            .stat-card .stat-body { padding: 14px 12px; }
            .stat-card .stat-number { font-size: 1.3rem; letter-spacing: -0.5px; }
            .stat-card .stat-label { font-size: 0.68rem; letter-spacing: 0.3px; }
            .stat-card .stat-icon { width: 32px; height: 32px; font-size: 0.9rem; border-radius: 8px; }
        }

        /* ===== ENHANCED MOBILE FILTER PILLS ===== */
        @media (max-width: 576px) {
            .d-flex.flex-wrap.gap-2 { gap: 6px !important; }
            .btn-xs.rounded-pill {
                min-height: 34px;
                font-size: 0.72rem !important;
                padding: 5px 12px !important;
                border-radius: 20px !important;
            }
        }

        /* ===== MOBILE WELCOME BAR ===== */
        @media (max-width: 576px) {
            .welcome-bar-mobile-stack {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 12px !important;
            }
            .welcome-bar-mobile-stack .d-flex.gap-2 {
                width: 100%;
            }
            .welcome-bar-mobile-stack .d-flex.gap-2 .btn {
                flex: 1;
                min-height: 40px;
            }
        }

        /* ===== MOBILE CHART LEGEND ===== */
        @media (max-width: 576px) {
            .chart-legend-row .col-3 { flex: 0 0 50%; max-width: 50%; }
        }

        /* ===== SMOOTH SCROLL ===== */
        html { scroll-behavior: smooth; }

        /* ===== SCROLLBAR TOUCH IMPROVEMENTS ===== */
        @media (hover: none) and (pointer: coarse) {
            .action-btns .btn-action { min-width: 40px; min-height: 40px; }
            .btn-xs { min-height: 36px; }
            .topbar-right .btn-icon { min-width: 42px; min-height: 42px; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <div class="wrapper">
        <nav class="sidebar" id="sidebar">
            <div class="brand">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="FreshMart Logo" style="height: 32px; width: auto; object-fit: contain;" class="d-inline-block align-top me-2 rounded-circle border bg-white">
                    <span>FreshMart Admin</span>
                </a>
            </div>
             <div class="sidebar-scroll">
                <div class="nav-section">{{ __('messages.main') ?? 'Main' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> {{ __('messages.dashboard') }}
                        </a>
                    </li>
                </ul>
                <div class="nav-section">{{ __('messages.catalog') ?? 'Catalog' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-grid"></i> {{ __('messages.categories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="bi bi-box"></i> {{ __('messages.products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">
                            <i class="bi bi-building"></i> {{ __('messages.suppliers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="bi bi-star"></i> {{ __('messages.reviews') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                            <i class="bi bi-tag"></i> {{ __('messages.coupons') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                            <i class="bi bi-images"></i> {{ __('messages.banners') }}
                        </a>
                    </li>
                </ul>
                <div class="nav-section">{{ __('messages.inventory') ?? 'Inventory' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">
                            <i class="bi bi-boxes"></i> {{ __('messages.stock_levels') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.stock_movements.*') ? 'active' : '' }}" href="{{ route('admin.stock_movements.index') }}">
                            <i class="bi bi-arrow-left-right"></i> {{ __('messages.stock_movements') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.purchase-orders.*') ? 'active' : '' }}" href="{{ route('admin.purchase-orders.index') }}">
                            <i class="bi bi-cart-plus"></i> {{ __('messages.purchase_orders') }}
                        </a>
                    </li>
                </ul>
                <div class="nav-section">{{ __('messages.sales') ?? 'Sales' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-cart-check"></i> {{ __('messages.orders') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <i class="bi bi-credit-card"></i> {{ __('messages.payments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}" href="{{ route('admin.deliveries.index') }}">
                            <i class="bi bi-truck"></i> {{ __('messages.deliveries') }}
                        </a>
                    </li>
                </ul>
                <div class="nav-section">{{ __('messages.insights') ?? 'Insights' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> {{ __('messages.reports') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}" href="{{ route('admin.activity_logs.index') }}">
                            <i class="bi bi-clock-history"></i> {{ __('messages.activity_logs') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.exports.*') ? 'active' : '' }}" href="{{ route('admin.exports.index') }}">
                            <i class="bi bi-download"></i> {{ __('messages.export_backup') }}
                        </a>
                    </li>
                </ul>
                <div class="nav-section">{{ __('messages.system') ?? 'System' }}</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                            <i class="bi bi-people"></i> {{ __('messages.customers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person-gear"></i> {{ __('messages.user_management') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.delivery-zones.*') ? 'active' : '' }}" href="{{ route('admin.delivery-zones.index') }}">
                            <i class="bi bi-geo-alt"></i> {{ __('messages.delivery_zones') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.admin_notifications.*') ? 'active' : '' }}" href="{{ route('admin.admin_notifications.index') }}">
                            <i class="bi bi-bell"></i> {{ __('messages.notifications') }}
                            @php $adminNotifCount = \App\Models\AppNotification::where('is_read', false)->count(); @endphp
                            @if($adminNotifCount > 0)
                                <span class="badge bg-danger rounded-pill ms-auto">{{ $adminNotifCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear"></i> {{ __('messages.settings') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-footer">
                <a class="nav-link d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <i class="bi bi-house"></i> {{ __('messages.view_store') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="nav-link d-flex align-items-center gap-2 text-start w-100" style="color:rgba(255,255,255,0.5);">
                        <i class="bi bi-box-arrow-right"></i> {{ __('messages.logout') }}
                    </button>
                </form>
            </div>
        </nav>

        <div class="content">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="btn d-lg-none btn-icon" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <button class="btn btn-icon sidebar-collapse-toggle" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="page-title">@yield('title', 'Dashboard')</h5>
                </div>
                <div class="topbar-right">
                    <div class="dropdown me-1">
                        <button class="btn btn-icon dropdown-toggle d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid var(--gray-200); background: white;">
                            <i class="bi bi-translate fs-5" style="color: var(--primary);"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow py-2" style="min-width: 140px; z-index: 1050;">
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

                    @php $lowStockCount = \App\Models\Inventory::whereColumn('qty_in_stock','<=','reorder_level')->count(); @endphp
                    @if($lowStockCount > 0)
                        <a href="{{ route('admin.inventory.low_stock') }}" class="btn-icon position-relative" title="Low Stock">
                            <i class="bi bi-exclamation-triangle-fill" style="color: var(--accent);"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.58rem;">{{ $lowStockCount }}</span>
                        </a>
                    @endif
                    <a href="{{ route('admin.admin_notifications.index') }}" class="btn-icon position-relative" title="Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if($adminNotifCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.58rem;">{{ $adminNotifCount }}</span>
                        @endif
                    </a>
                    
                    <div class="dropdown">
                        <div class="user-info dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            <span class="d-none d-md-inline fw-semibold" style="font-size: 0.85rem;">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down text-muted" style="font-size:0.65rem;"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" style="border-radius: var(--radius-sm); min-width:180px;">
                            <li><a class="dropdown-item py-2" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear me-2 text-muted"></i>{{ __('messages.settings') }}</a></li>
                            <li><hr class="dropdown-divider my-1" style="border-color: var(--gray-100);"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="page-content">
                @if(session('success'))
                    <div class="alert-success-custom mb-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span class="fw-medium">{{ session('success') }}</span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="box-shadow: none;"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-error-custom mb-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill fs-5"></i>
                        <span class="fw-medium">{{ session('error') }}</span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="box-shadow: none;"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <div class="footer-admin">
                &copy; {{ date('Y') }} FreshMart Admin Panel. All rights reserved. Built with Laravel.
            </div>
        </div>
    </div>


    <!-- Shared Upload Modal -->
    <div class="modal fade modal-custom" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-cloud-upload me-2 text-success"></i><span id="uploadModalTitle">Upload File</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadModalForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="upload-zone" id="uploadDropZone">
                            <i class="bi bi-cloud-arrow-up d-block"></i>
                            <p class="mb-1">Drag & drop your file here, or click to browse</p>
                            <small class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max 5MB)</small>
                            <input type="file" id="uploadFileInput" name="file" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('uploadFileInput').click()">
                                <i class="bi bi-folder2-open me-1"></i> Browse Files
                            </button>
                        </div>
                        <div id="uploadPreview" class="mt-3 text-center" style="display:none;">
                            <img id="uploadPreviewImg" src="" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 180px;">
                            <button type="button" class="btn btn-xs btn-outline-danger mt-2" onclick="clearUploadPreview()">
                                <i class="bi bi-x-lg me-1"></i>Remove
                            </button>
                        </div>
                        <input type="hidden" name="uploadable_type" id="uploadableType" value="">
                        <input type="hidden" name="uploadable_id" id="uploadableId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-upload me-1"></i> Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @yield('modals')

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="bottom-nav d-lg-none">
        <ul class="nav justify-content-around mb-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>{{ __('messages.dashboard') ?? 'Dashboard' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-cart-check"></i>
                    <span>{{ __('messages.orders') ?? 'Orders' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box"></i>
                    <span>{{ __('messages.products') ?? 'Products' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">
                    <i class="bi bi-boxes"></i>
                    <span>{{ __('messages.stock') ?? 'Stock' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear"></i>
                    <span>{{ __('messages.settings') ?? 'Settings' }}</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="bi bi-chevron-up"></i>
    </button>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function toggleSidebarCollapse() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        (function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
            }
        })();

        /* ===== BACK TO TOP BUTTON ===== */
        (function() {
            var btn = document.getElementById('backToTop');
            if (btn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        btn.classList.add('show');
                    } else {
                        btn.classList.remove('show');
                    }
                });
            }
        })();

        /* ===== CLOSE SIDEBAR ON MOBILE WHEN CLICKING A LINK ===== */
        (function() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.querySelectorAll('.nav-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 992) {
                            sidebar.classList.remove('show');
                            document.getElementById('sidebarOverlay').classList.remove('show');
                        }
                    });
                });
            }
        })();

        /* ===== SWEETALERT2 DELETE CONFIRMATION ===== */
        function confirmDelete(formId) {
            var form = document.getElementById(formId);
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone. The record will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: { popup: 'swal2-popup' }
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmDeleteUrl(url, title) {
            title = title || 'Are you sure?';
            Swal.fire({
                title: title,
                text: "This action cannot be undone. The record will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: { popup: 'swal2-popup' }
            }).then(function(result) {
                if (result.isConfirmed) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        /* ===== UPLOAD MODAL ===== */
        var uploadModalInstance = null;
        function showUploadModal(url, title, type, id) {
            var modal = document.getElementById('uploadModal');
            document.getElementById('uploadModalTitle').textContent = title || 'Upload File';
            document.getElementById('uploadModalForm').action = url;
            document.getElementById('uploadableType').value = type || '';
            document.getElementById('uploadableId').value = id || '';
            clearUploadPreview();
            
            if (!uploadModalInstance) {
                uploadModalInstance = new bootstrap.Modal(modal);
            }
            uploadModalInstance.show();
        }

        var dropZone = document.getElementById('uploadDropZone');
        var fileInput = document.getElementById('uploadFileInput');
        
        if (dropZone) {
            dropZone.addEventListener('click', function() { fileInput.click(); });
            dropZone.addEventListener('dragover', function(e) { e.preventDefault(); dropZone.classList.add('dragover'); });
            dropZone.addEventListener('dragleave', function() { dropZone.classList.remove('dragover'); });
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; handleUploadPreview(e.dataTransfer.files[0]); }
            });
        }
        if (fileInput) {
            fileInput.addEventListener('change', function() { if (this.files.length) handleUploadPreview(this.files[0]); });
        }

        function handleUploadPreview(file) {
            if (!file.type.startsWith('image/')) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadPreviewImg').src = e.target.result;
                document.getElementById('uploadPreview').style.display = 'block';
                document.getElementById('uploadDropZone').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        function clearUploadPreview() {
            document.getElementById('uploadPreview').style.display = 'none';
            document.getElementById('uploadDropZone').style.display = 'block';
            document.getElementById('uploadFileInput').value = '';
            document.getElementById('uploadPreviewImg').src = '';
        }

        /* ===== ACTION TOOLTIP INIT ===== */
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.forEach(function(el) {
            if (!el.closest('.btn-action')) return;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
