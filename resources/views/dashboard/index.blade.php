@extends('layouts.app')

@section('content')
    {{-- â”€â”€â”€ Page Header â”€â”€â”€ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                Dashboard
                <span id="realtimeDot" class="flex items-center gap-1 text-xs font-normal text-emerald-500 ml-1 opacity-0 transition-opacity duration-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                    <span id="realtimeLabel">Live</span>
                </span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }} Selamat
                datang, <span class="font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span></p>
        </div>
        <a href="{{ route('cashier.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all"
            style="background: linear-gradient(135deg, #0d9373, #14b890); box-shadow: 0 4px 14px rgba(13,147,115,0.35);"
            onmouseover="this.style.boxShadow='0 6px 20px rgba(13,147,115,0.5)'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.boxShadow='0 4px 14px rgba(13,147,115,0.35)'; this.style.transform='translateY(0)'">
            <i class="fas fa-cash-register"></i>
            Buka Kasir
        </a>
    </div>

    {{-- â”€â”€â”€ KPI Cards Row 1 â”€â”€â”€ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">

        {{-- Pendapatan Bulan Ini --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #0d9373 0%, #14b890 60%, #10b981 100%); box-shadow: 0 8px 24px rgba(13,147,115,0.35);">
            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-15"
                style="background: rgba(255,255,255,0.5);"></div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full opacity-10"
                style="background: rgba(255,255,255,0.4);"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Pendapatan Bulan Ini</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-wallet text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" id="kpi-income">Rp {{ number_format($income, 0, ',', '.') }}</p>
                <div class="flex items-center gap-1 mt-2">
                    @if ($monthlyComparison['revenue_change'] >= 0)
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-up text-[10px]"></i>
                            +{{ abs(round($monthlyComparison['revenue_change'], 1)) }}%
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-down text-[10px]"></i>
                            {{ round($monthlyComparison['revenue_change'], 1) }}%
                        </div>
                    @endif
                    <span class="text-white/50 text-xs">vs bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%); box-shadow: 0 8px 24px rgba(239,68,68,0.3);">
            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-15"
                style="background: rgba(255,255,255,0.5);"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Total Pengeluaran</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight">Rp {{ number_format($expenses, 0, ',', '.') }}</p>
                <div class="flex items-center gap-1 mt-2">
                    @if ($monthlyComparison['expense_change'] <= 0)
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-down text-[10px]"></i>
                            {{ abs(round($monthlyComparison['expense_change'], 1)) }}%
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-up text-[10px]"></i>
                            +{{ round($monthlyComparison['expense_change'], 1) }}%
                        </div>
                    @endif
                    <span class="text-white/50 text-xs">vs bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Laba/Rugi --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); box-shadow: 0 8px 24px rgba(99,102,241,0.35);">
            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-15"
                style="background: rgba(255,255,255,0.5);"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Laba / Rugi</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight {{ $profit < 0 ? 'text-red-200' : '' }}">
                    {{ $profit < 0 ? '-' : '' }}Rp {{ number_format(abs($profit), 0, ',', '.') }}
                </p>
                <div class="flex items-center gap-1 mt-2">
                    <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                        <i class="fas fa-{{ $profitComparison >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }} text-[10px]"></i>
                        {{ $profitComparison >= 0 ? '+' : '' }}{{ round($profitComparison, 1) }}%
                    </div>
                    <span class="text-xs font-semibold {{ $profit >= 0 ? 'text-white/90' : 'text-red-200' }} bg-white/20 px-2 py-0.5 rounded-full">
                        {{ $profit >= 0 ? ' Laba' : ' Rugi' }}
                    </span>
                    <span class="text-white/50 text-xs">vs bln lalu</span>
                </div>
            </div>
        </div>

        {{-- Transaksi Hari Ini --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); box-shadow: 0 8px 24px rgba(245,158,11,0.35);">
            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-15"
                style="background: rgba(255,255,255,0.5);"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Transaksi Hari Ini</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-receipt text-sm"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" id="kpi-today-tx">{{ $todayTransactions }}</p>
                <div class="flex items-center gap-1 mt-2">
                    <span class="text-white/50 text-xs">Revenue:</span>
                    <span class="text-white font-semibold text-xs" id="kpi-today-rev">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- --- Retail Insights (Hutang & Opname) --- --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        {{-- Total Hutang Supplier --}}
        <a href="{{ route('suppliers.debt-summary') }}" class="group rounded-2xl p-5 bg-white dark:bg-gray-800/80 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:border-red-200 dark:hover:border-red-900/30 transition-all overflow-hidden relative">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-red-500/5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Potensi Hutang Supplier</p>
                    <p class="text-xl font-black text-red-600 dark:text-red-400">Rp {{ number_format($totalSupplierDebt) }}</p>
                    <p class="text-[10px] text-gray-400 mt-1 italic">*Jumlah tagihan yang belum lunas</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-900/30 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
            </div>
        </a>

        {{-- Stock Opname Aktif --}}
        <a href="{{ route('stock-opnames.index') }}" class="group rounded-2xl p-5 bg-white dark:bg-gray-800/80 shadow-sm border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-900/30 transition-all overflow-hidden relative">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Opname Berjalan</p>
                    <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $activeOpnamesCount }} Sesi</p>
                    <p class="text-[10px] text-gray-400 mt-1 italic">*Sesi opname yang belum selesai</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-boxes-stacked text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- --- Charts Row: Trends & Distribution --- --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        {{-- Hourly Sales Trend (Waktu Ramai) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Tren Penjualan Hari Ini</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">Distribusi transaksi per jam</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                    <i class="fas fa-clock text-xs"></i>
                </div>
            </div>
            <div style="height: 250px;">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>

        {{-- Category Distribution (Donut) --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Kontribusi Kategori</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">Bulan: {{ now()->translatedFormat('F') }}</p>
                </div>
            </div>
            <div style="height: 250px;" class="relative pt-2">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- --- Admin Insights Row: Cashiers & Activity --- --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Top Cashiers --}}
        <div class="xl:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Performa Kasir (Hari Ini)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700/30">
                            <th class="px-5 py-3 font-semibold">Kasir</th>
                            <th class="px-5 py-3 font-semibold text-center">Nota</th>
                            <th class="px-5 py-3 font-semibold text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/30">
                        @forelse ($cashierPerformance as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/10 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($item->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ $item->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center text-xs text-gray-500">{{ $item->total_transactions }} tx</td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-xs font-bold text-emerald-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-gray-400 text-[10px]">Belum ada data kasir hari ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Activity Timeline --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Aktivitas Terbaru</h3>
            </div>
            <div class="p-5 space-y-5 flex-1 overflow-y-auto">
                @forelse ($recentActivities as $act)
                    <div class="flex gap-3 relative">
                        @if (!$loop->last)
                            <div class="absolute left-[13px] top-7 bottom-[-15px] w-[1px] bg-gray-100 dark:bg-gray-700/50"></div>
                        @endif
                        <div class="w-7 h-7 rounded-full bg-{{ $act->action_color }}-100 dark:bg-{{ $act->action_color }}-900/30 text-{{ $act->action_color }}-600 dark:text-{{ $act->action_color }}-400 flex items-center justify-center flex-shrink-0 z-10">
                            <i class="fas fa-bolt text-[10px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-200 truncate">{{ $act->description }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[9px] text-gray-400">{{ $act->user->name ?? 'System' }} • {{ $act->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-[10px]">Belum ada aktivitas</div>
                @endforelse
            </div>
            <a href="{{ route('activity-logs.index') }}" class="block text-center py-3 border-t border-gray-50 dark:border-gray-700/30 text-[10px] font-bold text-emerald-600 hover:bg-gray-50 dark:hover:bg-gray-700/10 transition-all uppercase tracking-widest">Lihat Semua History</a>
        </div>
    </div>

    {{-- --- Bottom Row: Quick Actions + Info Bisnis --- --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        {{-- Quick Actions Card --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('cashier.index') }}"
                    class="flex flex-col items-center gap-2 p-3 rounded-xl text-center hover:scale-105 transition-all"
                    style="background: linear-gradient(135deg, rgba(13,147,115,0.1), rgba(20,184,144,0.05)); border: 1px solid rgba(13,147,115,0.15);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(13,147,115,0.15);">
                        <i class="fas fa-cash-register text-emerald-600 text-base"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kasir</span>
                </a>
                <a href="{{ route('products.create') }}"
                    class="flex flex-col items-center gap-2 p-3 rounded-xl text-center hover:scale-105 transition-all"
                    style="background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.05)); border: 1px solid rgba(99,102,241,0.15);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.15);">
                        <i class="fas fa-plus text-indigo-600 text-base"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Produk Baru</span>
                </a>
                <a href="{{ route('purchase-orders.create') }}"
                    class="flex flex-col items-center gap-2 p-3 rounded-xl text-center hover:scale-105 transition-all"
                    style="background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(249,115,22,0.05)); border: 1px solid rgba(245,158,11,0.15);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.15);">
                        <i class="fas fa-file-invoice text-amber-600 text-base"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">PO Baru</span>
                </a>
                <a href="{{ route('reports.financial') }}"
                    class="flex flex-col items-center gap-2 p-3 rounded-xl text-center hover:scale-105 transition-all"
                    style="background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(244,63,94,0.05)); border: 1px solid rgba(239,68,68,0.15);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.15);">
                        <i class="fas fa-chart-pie text-red-600 text-base"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Laporan</span>
                </a>
            </div>
        </div>

        {{-- Info: Stok & Pelanggan --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider mb-4">Info Bisnis</h3>
            <div class="space-y-3">
                @php
                    try { $totalProducts  = \App\Models\Product::count(); } catch (\Exception $e) { $totalProducts = 0; }
                    try { $totalCustomers = \App\Models\Customer::count(); } catch (\Exception $e) { $totalCustomers = 0; }
                    try { $lowStock = \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>=', 0)->count(); } catch (\Exception $e) { $lowStock = 0; }
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-box text-emerald-500 w-4 text-center"></i>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total Produk</span>
                    </div>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">{{ number_format($totalProducts) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-blue-500 w-4 text-center"></i>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total Pelanggan</span>
                    </div>
                    <span class="text-sm font-bold text-gray-800 dark:text-white" id="kpi-customers">{{ number_format($totalCustomers) }}</span>
                </div>
                @if ($lowStock > 0)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation text-amber-500 w-4 text-center"></i>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Stok Menipis</span>
                        </div>
                        <a href="{{ route('low-stock.index') }}" class="text-xs font-bold text-amber-600 hover:underline" id="kpi-lowstock">{{ $lowStock }} produk</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dark = document.documentElement.classList.contains('dark');
            const textClr    = dark ? '#9ca3af' : '#6b7280';
            const gridClr    = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const tooltipBg     = dark ? '#1e2d3d' : '#fff';
            const tooltipBorder = dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)';

            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.font.size = 10;

            // --- Hourly Sales Chart (Trend Penjualan) ---
            const hourlyCtx = document.getElementById('hourlySalesChart').getContext('2d');
            new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(range(0, 23)) !!}.map(h => h + ':00'),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: (() => {
                            const raw = {!! json_encode($hourlySales) !!};
                            const data = Array(24).fill(0);
                            raw.forEach(item => data[item.hour] = item.total);
                            return data;
                        })(),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249,115,22,0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false, callbacks: { label: ctx => `Rp ${ctx.raw.toLocaleString('id-ID')}` } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textClr, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                        y: { 
                            grid: { color: gridClr }, 
                            ticks: { color: textClr, callback: v => v >= 1000 ? (v/1000) + 'k' : v },
                            beginAtZero: true 
                        }
                    }
                }
            });

            // --- Category Distribution Chart (Donut) ---
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryDistribution->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($categoryDistribution->pluck('total')) !!},
                        backgroundColor: ['#14b890', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 8, padding: 15, color: textClr, font: { size: 9 } } },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: Rp ${ctx.raw.toLocaleString('id-ID')}` } }
                    }
                }
            });

            // --- Realtime Polling ---
            const fmtRp = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
            const fmtNum = n => parseInt(n).toLocaleString('id-ID');

            function flashUpdate(el) {
                if (!el) return;
                el.style.transition = 'opacity 0.2s';
                el.style.opacity = '0.3';
                setTimeout(() => { el.style.opacity = '1'; }, 200);
            }

            async function pollStats() {
                if (document.visibilityState !== 'visible') return;
                try {
                    const res = await fetch('/api/v1/dashboard/stats', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const d = await res.json();

                    const todayTx  = document.getElementById('kpi-today-tx');
                    const todayRev = document.getElementById('kpi-today-rev');
                    const income   = document.getElementById('kpi-income');
                    const custs    = document.getElementById('kpi-customers');

                    if (todayTx && todayTx.textContent != d.today_transactions) {
                        flashUpdate(todayTx);
                        todayTx.textContent = fmtNum(d.today_transactions);
                    }
                    if (todayRev) {
                        const newRev = fmtRp(d.today_revenue);
                        if (todayRev.textContent !== newRev) { flashUpdate(todayRev); todayRev.textContent = newRev; }
                    }
                    if (income) {
                        const newInc = fmtRp(d.month_revenue);
                        if (income.textContent !== newInc) { flashUpdate(income); income.textContent = newInc; }
                    }
                    if (custs) {
                        const newCust = fmtNum(d.total_customers);
                        if (custs.textContent !== newCust) { flashUpdate(custs); custs.textContent = newCust; }
                    }

                } catch(e) {}
            }

            setTimeout(() => {
                pollStats();
                setInterval(pollStats, 30000);
            }, 5000);

            setTimeout(() => {
                const dot = document.getElementById('realtimeDot');
                if (dot) dot.style.opacity = '1';
            }, 1000);
        });
    </script>

@endsection
