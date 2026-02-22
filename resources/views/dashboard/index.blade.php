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
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }} Â· Selamat
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
                    @if($monthlyComparison['transaction_change'] >= 0)
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-up text-[10px]"></i>
                            +{{ abs(round($monthlyComparison['transaction_change'], 1)) }}%
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-white/90 text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
                            <i class="fas fa-arrow-trend-down text-[10px]"></i>
                            {{ round($monthlyComparison['transaction_change'], 1) }}%
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
                    @if($monthlyComparison['expense_change'] <= 0)
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
                    <span class="text-xs font-semibold {{ $profit >= 0 ? 'text-white/90' : 'text-red-200' }} bg-white/20 px-2 py-0.5 rounded-full">
                        {{ $profit >= 0 ? 'âœ“ Laba' : 'â†“ Rugi' }}
                    </span>
                    <span class="text-white/50 text-xs">bulan ini</span>
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

    {{-- â”€â”€â”€ Charts Row â”€â”€â”€ --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">

        {{-- Transaction Chart --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Transaksi Harian</h3>
                    <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                </div>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #dbeafe;">
                    <i class="fas fa-chart-line text-blue-500 text-sm"></i>
                </div>
            </div>
            <div style="height: 220px;">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Pendapatan Harian</h3>
                    <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                </div>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #d1fae5;">
                    <i class="fas fa-chart-bar text-emerald-500 text-sm"></i>
                </div>
            </div>
            <div style="height: 220px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

    </div>

    {{-- â”€â”€â”€ Bottom Row: Best Selling + Quick Actions + Info Cards â”€â”€â”€ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- Best Selling Products --}}
        <div class="xl:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Produk Terlaris</h3>
                <a href="{{ route('reports.index') }}" class="text-xs text-emerald-600 hover:underline font-medium">Lihat
                    Laporan â†’</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse($bestSellers ?? [] as $i => $item)
                    <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                            {{ $i === 0 ? 'text-amber-700 bg-amber-100' : ($i === 1 ? 'text-gray-600 bg-gray-100' : ($i === 2 ? 'text-orange-700 bg-orange-100' : 'text-gray-400 bg-gray-50')) }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                {{ $item->product->name ?? $item->name ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->total_sold ?? 0 }} terjual</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-gray-800 dark:text-white">Rp
                                {{ number_format($item->total_revenue ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    @if($bestSellingProduct)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold text-amber-700 bg-amber-100">1</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                    {{ $bestSellingProduct->product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $bestSellingProduct->total_sold }} terjual</p>
                            </div>
                        </div>
                    @else
                        <div class="px-5 py-8 text-center text-gray-400 text-sm">
                            <i class="fas fa-box-open text-2xl mb-2 block opacity-30"></i>
                            <p>Belum ada data penjualan</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        {{-- Quick Actions + Shortcuts --}}
        <div class="space-y-4">
            {{-- Quick Actions Card --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-3">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-2">
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
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-3">Info Bisnis</h3>
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
                    @if($lowStock > 0)
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
            Chart.defaults.font.size = 11;

            // â”€â”€â”€ Transaction Chart â”€â”€â”€
            new Chart(document.getElementById('transactionChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Transaksi',
                        data: {!! json_encode($transactionChartData->pluck('total')) !!},
                        borderColor: '#14b890',
                        backgroundColor: (ctx) => {
                            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
                            g.addColorStop(0, 'rgba(20,184,144,0.25)');
                            g.addColorStop(1, 'rgba(20,184,144,0)');
                            return g;
                        },
                        borderWidth: 2.5, tension: 0.4, fill: true,
                        pointBackgroundColor: '#fff', pointBorderColor: '#14b890',
                        pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: tooltipBg, titleColor: dark ? '#e2e8f0' : '#1a202c', bodyColor: textClr, borderColor: tooltipBorder, borderWidth: 1, padding: 10, cornerRadius: 8 }
                    },
                    scales: {
                        x: { grid: { color: gridClr }, ticks: { color: textClr }, border: { display: false } },
                        y: { grid: { color: gridClr }, ticks: { color: textClr }, border: { display: false }, beginAtZero: true }
                    }
                }
            });

            // â”€â”€â”€ Revenue Chart â”€â”€â”€
            new Chart(document.getElementById('revenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($revenueChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($revenueChartData->pluck('total')) !!},
                        backgroundColor: (ctx) => {
                            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220);
                            g.addColorStop(0, 'rgba(99,102,241,0.85)');
                            g.addColorStop(1, 'rgba(139,92,246,0.5)');
                            return g;
                        },
                        borderRadius: 6, borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: tooltipBg, titleColor: dark ? '#e2e8f0' : '#1a202c',
                            bodyColor: textClr, borderColor: tooltipBorder, borderWidth: 1, padding: 10, cornerRadius: 8,
                            callbacks: { label: ctx => `Rp ${ctx.raw.toLocaleString('id-ID')}` }
                        }
                    },
                    scales: {
                        x: { grid: { color: gridClr }, ticks: { color: textClr }, border: { display: false } },
                        y: { grid: { color: gridClr }, ticks: { color: textClr, callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k' }, border: { display: false }, beginAtZero: true }
                    }
                }
            });

            // â”€â”€â”€ Realtime Polling â”€â”€â”€
            const fmtRp = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
            const fmtNum = n => parseInt(n).toLocaleString('id-ID');

            function flashUpdate(el) {
                if (!el) return;
                el.style.transition = 'opacity 0.2s';
                el.style.opacity = '0.3';
                setTimeout(() => { el.style.opacity = '1'; }, 200);
            }

            async function pollStats() {
                try {
                    const res = await fetch('/api/v1/dashboard/stats', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const d = await res.json();

                    // Update KPI cards
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

                    // Update sidebar badge (Riwayat tx count)
                    document.querySelectorAll('[data-sidebar-tx]').forEach(el => {
                        el.textContent = d.sidebar_tx_count;
                        el.style.display = d.sidebar_tx_count > 0 ? '' : 'none';
                    });

                    // Show live dot
                    const dot = document.getElementById('realtimeDot');
                    if (dot) {
                        dot.style.opacity = '1';
                        const lbl = document.getElementById('realtimeLabel');
                        if (lbl) {
                            lbl.textContent = 'Live';
                            setTimeout(() => { lbl.textContent = ''; setTimeout(() => { lbl.textContent = 'Live'; }, 800); }, 800);
                        }
                    }

                } catch(e) { /* silent fail â€” tetap jalan */ }
            }

            // Mulai polling setelah 5 detik (jangan langsung saat load)
            setTimeout(() => {
                pollStats();
                setInterval(pollStats, 30000); // setiap 30 detik
            }, 5000);

            // Tampilkan live dot setelah 1 detik
            setTimeout(() => {
                const dot = document.getElementById('realtimeDot');
                if (dot) dot.style.opacity = '1';
            }, 1000);
        });
    </script>

@endsection
