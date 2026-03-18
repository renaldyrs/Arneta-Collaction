<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Arneta Collection — POS System</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#14b890">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('pwa-icon.png') }}">

    <!-- Resource Hints -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#f0fdf9',
                            100: '#ccfbee',
                            200: '#99f5dd',
                            300: '#5de9c6',
                            400: '#2dd4aa',
                            500: '#14b890',
                            600: '#0d9373',
                            700: '#0d755e',
                            800: '#0e5d4d',
                            900: '#0e4d40',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: { from: { opacity: '0', transform: 'translateY(8px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                        slideIn: { from: { opacity: '0', transform: 'translateX(-10px)' }, to: { opacity: '1', transform: 'translateX(0)' } },
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f0f4f8; /* default light mode - bisa di class */
        }

        /* ─── Base Page Wrapper ─── */
        :root {
            --swal-bg: #ffffff;
            --swal-text: #1f2937;
            --swal-border: #e2e8f0;
        }

        .dark {
            --swal-bg: #1e293b;
            --swal-text: #f3f4f6;
            --swal-border: #334155;
        }

        /* ─── SweetAlert2 Theme Sync ─── */
        .swal2-popup {
            background: var(--swal-bg) !important;
            color: var(--swal-text) !important;
            border: 1px solid var(--swal-border) !important;
            border-radius: 1rem !important;
        }
        .swal2-title, .swal2-html-container, .swal2-content {
            color: var(--swal-text) !important;
        }
        .swal2-confirm {
            background-color: #10b981 !important; /* emerald-500 */
            border-radius: 0.5rem !important;
        }
        .swal2-cancel {
            border-radius: 0.5rem !important;
        }

        .bg-page {
            background: #f0f4f8;
            transition: background-color 0.3s ease;
        }

        .dark .bg-page {
            background: #0f1923;
        }

        .transition-theme {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* ─── Sidebar ─── */
        #sidebar {
            background: linear-gradient(180deg, #0a1628 0%, #0d1f35 55%, #091524 100%);
            border-right: 1px solid rgba(255,255,255,0.04);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* ─── Nav item ─── */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.7rem;
            border-radius: 0.75rem;
            font-size: 0.815rem;
            font-weight: 500;
            color: rgba(255,255,255,0.52);
            cursor: pointer;
            transition: background 0.18s, color 0.18s, transform 0.15s, box-shadow 0.18s;
            text-decoration: none;
            gap: 0.65rem;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at left center, rgba(13,147,115,0.15) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.9);
            transform: translateX(3px);
        }

        .nav-item:hover::before { opacity: 1; }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(13,147,115,0.9) 0%, rgba(13,117,94,0.8) 100%);
            color: #fff;
            box-shadow: 0 4px 18px rgba(13,147,115,0.38), inset 0 1px 0 rgba(255,255,255,0.12);
            transform: translateX(0);
        }

        .nav-item.active::before { opacity: 0; }
        .nav-item.active .nav-icon { color: #fff; }

        /* Icon container */
        .nav-icon-wrap {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(255,255,255,0.05);
            transition: background 0.18s, box-shadow 0.18s;
        }
        .nav-item:hover .nav-icon-wrap {
            background: rgba(255,255,255,0.09);
        }
        .nav-item.active .nav-icon-wrap {
            background: rgba(255,255,255,0.15);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .nav-icon {
            width: 14px;
            text-align: center;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.45);
            flex-shrink: 0;
            transition: color 0.18s;
        }

        .nav-item:hover .nav-icon  { color: rgba(255,255,255,0.8); }
        .nav-item.active .nav-icon { color: #fff; }

        /* Section label */
        .nav-section {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
            padding: 0.6rem 0.85rem 0.3rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-section::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        }

        /* Sub-items */
        .sub-item {
            display: flex;
            align-items: center;
            padding: 0.42rem 0.7rem 0.42rem 2.4rem;
            border-radius: 0.625rem;
            font-size: 0.785rem;
            font-weight: 450;
            color: rgba(255,255,255,0.44);
            cursor: pointer;
            transition: background 0.18s, color 0.18s, transform 0.15s;
            text-decoration: none;
            gap: 0.55rem;
            position: relative;
        }

        .sub-item:hover {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.82);
            transform: translateX(3px);
        }

        .sub-item.active {
            color: #34d399;
            background: rgba(52,211,153,0.1);
            font-weight: 500;
        }

        .sub-item.active::before {
            content: '';
            position: absolute;
            left: 0.6rem;
            width: 2.5px;
            height: 1rem;
            background: linear-gradient(180deg, #10b981, #34d399);
            border-radius: 2px;
        }

        /* Dropdown */
        .dropdown-menu {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease;
            opacity: 0;
        }
        .dropdown-menu.open {
            max-height: 700px;
            opacity: 1;
        }

        /* ─── Glassmorphism navbar ─── */
        .glass-navbar {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .dark .glass-navbar {
            background: rgba(15, 25, 35, 0.88);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* ─── Cards ─── */
        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.10);
            transform: translateY(-1px);
        }

        .dark .card {
            background: #1a2535;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* ─── Stat cards gradient ─── */
        .stat-card-green {
            background: linear-gradient(135deg, #0d9373, #10b981);
        }

        .stat-card-blue {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
        }

        .stat-card-amber {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
        }

        .stat-card-purple {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
        }

        /* ─── Table ─── */
        .modern-table thead tr {
            background: rgba(15, 25, 35, 0.03);
        }

        .dark .modern-table thead tr {
            background: rgba(255, 255, 255, 0.04);
        }

        /* ─── Form inputs ─── */
        .form-input {
            border: 1.5px solid #e2e8f0;
            border-radius: 0.6rem;
            padding: 0.55rem 0.85rem;
            font-size: 0.875rem;
            background: #fff;
            color: #1a202c;
            transition: border-color 0.18s, box-shadow 0.18s;
            width: 100%;
            outline: none;
        }

        .form-input:focus {
            border-color: #14b890;
            box-shadow: 0 0 0 3px rgba(20, 184, 144, 0.12);
        }

        .dark .form-input {
            background: #151f2d;
            border-color: #2d3f55;
            color: #e2e8f0;
        }

        .dark .form-input:focus {
            border-color: #14b890;
        }

        /* ─── Form select ─── */
        .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 0.6rem;
            padding: 0.55rem 0.85rem;
            font-size: 0.875rem;
            background: #f9fafb;
            color: #1a202c;
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
            cursor: pointer;
        }

        .form-select:focus {
            border-color: #14b890;
            box-shadow: 0 0 0 3px rgba(20, 184, 144, 0.12);
        }

        .dark .form-select {
            background: #151f2d;
            border-color: #2d3f55;
            color: #e2e8f0;
        }

        /* ─── Buttons ─── */
        .btn-primary {
            background: linear-gradient(135deg, #0d9373, #14b890);
            color: #fff;
            border: none;
            border-radius: 0.6rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.18s, transform 0.15s, box-shadow 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(13, 147, 115, 0.35);
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #4a5568;
            border-radius: 0.6rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-secondary:hover {
            background: #f7fafc;
        }

        .dark .btn-secondary {
            border-color: #2d3f55;
            color: #a0aec0;
        }

        .dark .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-purple {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #374151;
        }

        .dark .badge-green {
            background: rgba(6, 95, 70, 0.3);
            color: #6ee7b7;
        }

        .dark .badge-red {
            background: rgba(153, 27, 27, 0.3);
            color: #fca5a5;
        }

        .dark .badge-yellow {
            background: rgba(92, 64, 14, 0.3);
            color: #fde68a;
        }

        .dark .badge-blue {
            background: rgba(30, 64, 175, 0.3);
            color: #93c5fd;
        }

        .dark .badge-purple {
            background: rgba(91, 33, 182, 0.3);
            color: #c4b5fd;
        }

        .dark .badge-gray {
            background: rgba(55, 65, 81, 0.4);
            color: #9ca3af;
        }

        /* Gradient divider */
        .gradient-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.07), transparent);
            margin: 0.5rem 0;
        }

        /* ─── Page animation ─── */
        main>div {
            animation: fadeIn 0.3s ease-out;
        }

        /* ─── Dark body ─── */
        body { transition: background-color 0.3s; }
        .dark body,
        .dark {
            background: #0f1923;
        }

    </style>

    <!-- Pre-load Dark Mode Script -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    @stack('styles')
</head>

<body class="min-h-screen transition-theme bg-page text-gray-800 dark:text-gray-100">
    <!-- Mobile Overlay -->
    <div id="sidebarOverlay"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden transition-opacity duration-300 opacity-0"
        onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">

        <!-- ═══ SIDEBAR ═══ -->
        <div id="sidebar"
            class="fixed md:relative w-[240px] md:w-[220px] lg:w-[240px] h-full z-40 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out flex flex-col">

            @php
                try {
                    $sideProfile = \App\Models\StoreProfile::first();
                } catch (\Exception $e) {
                    $sideProfile = null;
                }
                if (!$sideProfile) {
                    $sideProfile = (object) ['name' => 'Arneta Collection', 'logo' => null];
                }
                $sideLogoUrl = null;
                if (!empty($sideProfile->logo)) {
                    $sideLogoUrl = filter_var($sideProfile->logo, FILTER_VALIDATE_URL)
                        ? $sideProfile->logo
                        : asset('storage/' . $sideProfile->logo);
                }
                $sideUser = Auth::user();
                $sideIsAdmin = $sideUser && ($sideUser->role === 'admin');
            @endphp

            {{-- ── Sidebar Header ── --}}
            <div class="flex items-center justify-between px-4 py-4 flex-shrink-0"
                 style="border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.18);">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 relative"
                         style="background: linear-gradient(135deg,#0d9373 0%,#065f46 100%); box-shadow: 0 4px 14px rgba(13,147,115,0.5), inset 0 1px 0 rgba(255,255,255,0.2);">
                        @if ($sideLogoUrl)
                            <img src="{{ $sideLogoUrl }}" alt="Logo" class="w-full h-full object-cover"
                                onerror="this.style.display='none'; this.nextSibling.style.display='flex'">
                            <span class="hidden absolute inset-0 items-center justify-center">
                                <i class="fas fa-store text-white text-sm"></i>
                            </span>
                        @else
                            <i class="fas fa-store text-white text-sm"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-snug">{{ Str::limit($sideProfile->name ?? 'Arneta', 14) }}</p>
                        <div class="flex items-center gap-1 mt-0.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <p class="text-[10px] font-medium" style="color: rgba(255,255,255,0.35);">POS System</p>
                        </div>
                    </div>
                </div>
                <button onclick="toggleSidebar()"
                    class="md:hidden w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/10 transition-colors">
                    <i class="fas fa-times text-white/50 text-xs"></i>
                </button>
            </div>

            {{-- ── Sidebar Nav ── --}}
            <div class="flex-1 overflow-y-auto py-3 px-2.5 space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon-wrap">
                        <i class="nav-icon fas fa-grid-2"></i>
                    </span>
                    <span>Dashboard</span>
                </a>

                @if ($sideIsAdmin)

                    <div class="nav-section">Master Data</div>

                    {{-- Master Data Dropdown --}}
                    @php $masterActive = request()->routeIs(['store-profile.*','users.*','categories.*','suppliers.*','products.*','payment.*','discounts.*','purchase-orders.*']); @endphp
                    <button onclick="toggleNav('nav-master','arr-master')" class="nav-item w-full {{ $masterActive ? 'text-white/85' : '' }}">
                        <span class="nav-icon-wrap">
                            <i class="nav-icon fas fa-database"></i>
                        </span>
                        <span class="flex-1 text-left">Master Data</span>
                        <svg id="arr-master" class="w-3 h-3 transition-transform duration-200 flex-shrink-0 opacity-50 {{ $masterActive ? 'rotate-180' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="nav-master" class="dropdown-menu space-y-0.5 {{ $masterActive ? 'open' : '' }}">
                        <a href="{{ route('store-profile.index') }}" class="sub-item {{ request()->routeIs('store-profile.*') ? 'active' : '' }}">
                            <i class="fas fa-store text-[10px] w-3.5 text-center"></i> Profil Toko
                        </a>
                        <a href="{{ route('users.index') }}" class="sub-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users text-[10px] w-3.5 text-center"></i> Pengguna
                        </a>
                        <a href="{{ route('categories.index') }}" class="sub-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group text-[10px] w-3.5 text-center"></i> Kategori
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="sub-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="fas fa-truck text-[10px] w-3.5 text-center"></i> Supplier
                        </a>
                        <a href="{{ route('products.index') }}" class="sub-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-box text-[10px] w-3.5 text-center"></i> Produk
                        </a>
                        <a href="{{ route('payment.index') }}" class="sub-item {{ request()->routeIs('payment.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card text-[10px] w-3.5 text-center"></i> Metode Bayar
                        </a>
                        <a href="{{ route('discounts.index') }}" class="sub-item {{ request()->routeIs('discounts.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt text-[10px] w-3.5 text-center"></i> Diskon & Promo
                        </a>
                        <a href="{{ route('purchase-orders.index') }}" class="sub-item {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice text-[10px] w-3.5 text-center"></i> Purchase Order
                        </a>
                    </div>

                @endif

                <div class="nav-section">Transaksi</div>

                <a href="{{ route('cashier.index') }}" class="nav-item {{ request()->routeIs('cashier.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-cash-register"></i></span>
                    <span>Kasir</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-receipt"></i></span>
                    <span class="flex-1">Riwayat</span>
                    @php
                        try {
                            $todayTxCount = \App\Models\Transaction::whereDate('created_at', today())
                                ->when(auth()->user()?->role !== 'admin', fn($q) => $q->where('user_id', auth()->id()))
                                ->count();
                        } catch (\Exception $e) { $todayTxCount = 0; }
                    @endphp
                    @if ($todayTxCount > 0)
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full tabular-nums"
                              style="background: rgba(52,211,153,0.18); color: #34d399;">{{ $todayTxCount }}</span>
                    @endif
                </a>

                <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-users"></i></span>
                    <span>Pelanggan</span>
                </a>

                <a href="{{ route('shifts.index') }}" class="nav-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-business-time"></i></span>
                    <span>Shift Kasir</span>
                </a>

                <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-money-bill-wave"></i></span>
                    <span>Pengeluaran</span>
                </a>

                <a href="{{ route('returns.index') }}" class="nav-item {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap"><i class="nav-icon fas fa-rotate-left"></i></span>
                    <span>Return</span>
                </a>

                @if ($sideIsAdmin)
                    <div class="nav-section">Pengaturan</div>

                    <a href="{{ route('settings.print') }}" class="nav-item {{ request()->routeIs('settings.print') ? 'active' : '' }}">
                        <span class="nav-icon-wrap"><i class="nav-icon fas fa-print"></i></span>
                        <span>Manajemen Print</span>
                    </a>

                    <div class="nav-section">Laporan</div>

                    <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                        <span class="nav-icon-wrap"><i class="nav-icon fas fa-clipboard-list"></i></span>
                        <span>Lap. Pesanan</span>
                    </a>

                    <a href="{{ route('reports.financial') }}" class="nav-item {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                        <span class="nav-icon-wrap"><i class="nav-icon fas fa-chart-line"></i></span>
                        <span>Lap. Keuangan</span>
                    </a>

                    <a href="{{ route('activity-logs.index') }}" class="nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <span class="nav-icon-wrap"><i class="nav-icon fas fa-history"></i></span>
                        <span>Log Aktivitas</span>
                    </a>

                    <a href="{{ route('low-stock.index') }}" class="nav-item {{ request()->routeIs('low-stock.*') ? 'active' : '' }}">
                        <span class="nav-icon-wrap" style="background: rgba(245,158,11,0.12);">
                            <i class="nav-icon fas fa-triangle-exclamation" style="color: #f59e0b;"></i>
                        </span>
                        <span class="flex-1">Stok Menipis</span>
                        @php
                            try { $lowStockCount = \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>=', 0)->count(); }
                            catch (\Exception $e) { $lowStockCount = 0; }
                        @endphp
                        @if ($lowStockCount > 0)
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full tabular-nums"
                                  style="background: rgba(245,158,11,0.18); color: #f59e0b;">{{ $lowStockCount }}</span>
                        @endif
                    </a>
                @endif

            </div>

            {{-- ── Sidebar Footer ── --}}
            <div class="flex-shrink-0 px-3 pb-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.05);">
                @auth
                <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-xs font-bold"
                         style="background: linear-gradient(135deg, #0d9373 0%, #6366f1 100%); box-shadow: 0 2px 8px rgba(13,147,115,0.3);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-semibold truncate">{{ explode(' ', Auth::user()->name)[0] }}</p>
                        <p class="text-[10px] truncate capitalize" style="color: rgba(255,255,255,0.3);">{{ Auth::user()->role ?? 'User' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-red-500/20 transition-colors group"
                            title="Keluar">
                            <i class="fas fa-sign-out-alt text-[11px] group-hover:text-red-400 transition-colors" style="color: rgba(255,255,255,0.3);"></i>
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>

        <!-- ═══ MAIN CONTENT ═══ -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Navbar -->
            <header class="glass-navbar z-30 flex-shrink-0 h-14">
                <div class="flex items-center justify-between h-full px-4 md:px-6">
                    <!-- Left -->
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSidebar()"
                            class="md:hidden p-2 rounded-lg hover:bg-black/5 transition-colors">
                            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                        </button>
                        <!-- Breadcrumb -->
                        <nav class="hidden md:flex items-center gap-1.5 text-xs text-gray-400">
                            <span>Arneta Collection</span>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                            <span class="text-gray-700 dark:text-gray-200 font-medium capitalize">
                                {{ str_replace(['-', '_', '.'], ' ', request()->segment(1) ?: 'Dashboard') }}
                            </span>
                        </nav>
                    </div>

                    <!-- Right -->
                    <div class="flex items-center gap-2">
                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationButton"
                                class="p-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors group relative"
                                title="Notifikasi">
                                <i class="fas fa-bell text-gray-500 dark:text-gray-400 group-hover:text-amber-500 transition-colors"></i>
                                <span id="notificationBadge"
                                    class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-gray-800 hidden">0</span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown"
                                class="hidden absolute right-0 mt-1.5 w-72 rounded-2xl shadow-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-wider">Notifikasi</h3>
                                    <span id="notifCountLabel" class="text-[10px] bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-full font-bold">0 Baru</span>
                                </div>
                                <div id="notificationList" class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700/50">
                                    <div class="p-6 text-center text-gray-400">
                                        <i class="fas fa-bell-slash text-2xl mb-2 block opacity-20"></i>
                                        <p class="text-xs">Tidak ada notifikasi baru</p>
                                    </div>
                                </div>
                                <div class="px-3 py-2 bg-gray-50/50 dark:bg-gray-700/30 text-center border-t border-gray-100 dark:border-gray-700">
                                    <button onclick="document.getElementById('notificationDropdown').classList.add('hidden')" class="text-[10px] text-gray-500 hover:text-emerald-500 font-medium">Tutup</button>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode -->
                        <button id="darkModeToggle"
                            class="p-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                            title="Tema">
                            <i class="fas fa-moon text-gray-500 dark:hidden"></i>
                            <i class="fas fa-sun text-yellow-400 hidden dark:block"></i>
                        </button>

                        <!-- User Avatar -->
                        @auth
                            <div class="relative">
                                <button id="userMenuBtn"
                                    class="flex items-center gap-2 py-1.5 px-2.5 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                        style="background: linear-gradient(135deg, #0d9373, #6366f1);">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden sm:block">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                    <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden sm:block"></i>
                                </button>
                                <!-- User Dropdown -->
                                <div id="userDropdown"
                                    class="hidden absolute right-0 mt-1.5 w-48 rounded-xl shadow-xl overflow-hidden z-50"
                                    style="background: #fff; border: 1px solid rgba(0,0,0,0.08);">
                                    <div class="px-4 py-3" style="border-bottom: 1px solid #f3f4f6;">
                                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 capitalize">
                                            {{ Auth::user()->role ?? 'User' }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('profile.show') }}"
                                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-user-circle w-4 text-gray-400"></i> Profil Saya
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt w-4"></i> Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto bg-page transition-theme">
                <div class="p-4 md:p-6">
                    @if (session('success'))
                        <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
                            style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;" x-data="{ show: true }"
                            x-show="show">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button onclick="this.parentElement.remove()"
                                class="ml-auto opacity-60 hover:opacity-100">×</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
                            style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                            <button onclick="this.parentElement.remove()"
                                class="ml-auto opacity-60 hover:opacity-100">×</button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // ─── Sidebar Toggle ───
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            const isHidden = sb.classList.contains('-translate-x-full');
            if (isHidden) {
                ov.classList.remove('hidden');
                setTimeout(() => ov.classList.add('opacity-100'), 10);
                sb.classList.remove('-translate-x-full');
                document.body.style.overflow = 'hidden';
            } else {
                ov.classList.remove('opacity-100');
                sb.classList.add('-translate-x-full');
                setTimeout(() => ov.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebar').classList.remove('-translate-x-full');
                document.getElementById('sidebarOverlay').classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // ─── Dropdown Nav ───
        function toggleNav(id, arrowId) {
            const el = document.getElementById(id);
            const ar = document.getElementById(arrowId);
            el.classList.toggle('open');
            ar.classList.toggle('rotate-180');
        }

        // ─── Dropdowns & Modal ───
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // User Dropdown Linkage
            const userBtn = document.getElementById('userMenuBtn');
            const userDD  = document.getElementById('userDropdown');
            if (userBtn && userDD) {
                userBtn.addEventListener('click', (e) => { 
                    e.stopPropagation(); 
                    userDD.classList.toggle('hidden'); 
                    if (notifDD) notifDD.classList.add('hidden');
                });
            }

            // Notifications Dropdown Linkage
            const notifBtn = document.getElementById('notificationButton');
            const notifDD  = document.getElementById('notificationDropdown');
            if (notifBtn && notifDD) {
                notifBtn.addEventListener('click', (e) => { 
                    e.stopPropagation(); 
                    notifDD.classList.toggle('hidden'); 
                    if (userDD) userDD.classList.add('hidden');
                });
            }

            document.addEventListener('click', (e) => {
                if (userDD && !userBtn.contains(e.target)) userDD.classList.add('hidden');
                if (notifDD && !notifBtn.contains(e.target)) notifDD.classList.add('hidden');
            });

            // Dark Mode
            const dmBtn = document.getElementById('darkModeToggle');
            if (dmBtn) {
                dmBtn.addEventListener('click', () => {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
                });
            }

            // ─── Notification Polling ───
            const fmtRp = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
            const fmtNum = n => parseInt(n).toLocaleString('id-ID');

            let pollingInterval = null;

            async function updateGlobalNotifications() {
                // Smart Polling: Only fetch if tab is active
                if (document.visibilityState !== 'visible') return;

                try {
                    const res = await fetch('/api/v1/dashboard/stats', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const d = await res.json();

                    const badge = document.getElementById('notificationBadge');
                    const label = document.getElementById('notifCountLabel');
                    const list  = document.getElementById('notificationList');
                    
                    if (!badge || !label || !list) return;

                    let totalNotifs = 0;
                    let html = '';

                    // 1. Low Stock Notif
                    if (d.low_stock_count > 0) {
                        totalNotifs++;
                        html += `
                            <a href="/low-stock" class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-amber-500 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white">Stok Menipis!</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">${d.low_stock_count} produk butuh restock.</p>
                                </div>
                            </a>`;
                    }

                    // 2. Pending Returns Notif (For Admin)
                    if (d.pending_returns_count > 0) {
                        totalNotifs++;
                        html += `
                            <a href="/returns" class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-undo text-blue-500 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white">Retur Pending</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">${d.pending_returns_count} pengajuan butuh review.</p>
                                </div>
                            </a>`;
                    }

                    // 3. Sales Summary (Today)
                    if (d.today_transactions > 0) {
                        html += `
                            <div class="flex items-start gap-3 p-4 bg-emerald-50/30 dark:bg-emerald-900/10">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-chart-line text-emerald-500 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-800 dark:text-white">Omzet Hari Ini</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">${d.today_transactions} transaksi: <span class="text-emerald-600 dark:text-emerald-400 font-bold">${fmtRp(d.today_revenue)}</span></p>
                                </div>
                            </div>`;
                    }

                    if (totalNotifs > 0) {
                        badge.textContent = totalNotifs;
                        badge.classList.remove('hidden');
                        label.textContent = `${totalNotifs} Baru`;
                        list.innerHTML = html;
                    } else if (d.today_transactions > 0) {
                        badge.classList.add('hidden');
                        label.textContent = `Ringkasan`;
                        list.innerHTML = html;
                    }

                } catch(e) { console.error('Notif error:', e); }
            }

            // Start/Stop polling based on visibility
            function managePolling() {
                if (document.visibilityState === 'visible') {
                    updateGlobalNotifications();
                    if (!pollingInterval) {
                        pollingInterval = setInterval(updateGlobalNotifications, 120000); // 2 minutes interval
                    }
                } else {
                    if (pollingInterval) {
                        clearInterval(pollingInterval);
                        pollingInterval = null;
                    }
                }
            }

            document.addEventListener('visibilitychange', managePolling);
            managePolling(); // Initial check
        });
    </script>

    @include('sweetalert::alert')
    <!-- Printer Bridge -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script src="{{ asset('js/qz-bridge.js') }}"></script>

    @stack('scripts')
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    console.log('Service worker registered.', reg);
                }).catch(err => {
                    console.error('Service worker registration failed:', err);
                });
            });
        }
    </script>
</body>

</html>