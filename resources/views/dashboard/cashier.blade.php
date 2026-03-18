@extends('layouts.app')

@section('content')
    {{-- ─── Page Header ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                Dashboard Kasir
                <span id="realtimeDot" class="flex items-center gap-1 text-xs font-normal text-emerald-500 ml-1 opacity-0 transition-opacity duration-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                    <span id="realtimeLabel">Live</span>
                </span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }} Selamat
                datang, <span class="font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span></p>
        </div>
        <a href="{{ route('cashier.index') }}"
            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white text-sm font-bold transition-all"
            style="background: linear-gradient(135deg, #0d9373, #14b890); box-shadow: 0 4px 14px rgba(13,147,115,0.35);"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(13,147,115,0.45)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(13,147,115,0.35)'">
            <i class="fas fa-cash-register"></i>
            Buka Kasir Sekarang
        </a>
    </div>

    {{-- ─── KPI Cards ─── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Transaksi Saya Hari Ini --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                <i class="fas fa-receipt text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Transaksi Anda</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="kpi-today-tx">{{ $todayTransactions }}</p>
            </div>
        </div>

        {{-- Omzet Saya Hari Ini --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Omzet Anda</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="kpi-today-rev">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Produk Terlaris Saya --}}
        <div class="md:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                <i class="fas fa-star text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Produk Terlaris Anda</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                    @if ($bestSellingProduct)
                        {{ $bestSellingProduct->product->name }} ({{ $bestSellingProduct->total_sold }})
                    @else
                        Belum ada data
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Progress Penjualan 7 Hari --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800/80 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Performa Mingguan Anda</h3>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-[10px] text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Transaksi
                    </span>
                </div>
            </div>
            <div style="height: 250px;">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>

        {{-- Transaksi Terakhir --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Transaksi Terakhir</h3>
                <a href="{{ route('cashier.orders') }}" class="text-[10px] font-bold text-emerald-600 hover:underline">Semua</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @forelse ($recentTransactions as $item)
                    <div class="p-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-gray-800 dark:text-white">{{ $item->invoice_number }}</span>
                            <span class="text-[10px] text-gray-500">{{ $item->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] text-gray-400">{{ $item->customer->name ?? 'Umum' }} • {{ $item->paymentMethod->name ?? 'Tunai' }}</p>
                            <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400">
                        <i class="fas fa-receipt text-3xl mb-2 opacity-20 block"></i>
                        <p class="text-xs text-gray-500">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dark = document.documentElement.classList.contains('dark');
            const textClr = dark ? '#9ca3af' : '#6b7280';
            const gridClr = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.font.size = 11;

            // ─── Transaction Chart ───
            new Chart(document.getElementById('transactionChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Transaksi',
                        data: {!! json_encode($transactionChartData->pluck('total')) !!},
                        borderColor: '#14b890',
                        backgroundColor: 'rgba(20,184,144,0.1)',
                        borderWidth: 3, tension: 0.4, fill: true,
                        pointBackgroundColor: '#fff', pointBorderColor: '#14b890',
                        pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textClr } },
                        y: { grid: { color: gridClr }, ticks: { color: textClr }, beginAtZero: true }
                    }
                }
            });

            // ─── Realtime status ───
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

                    const todayTx  = document.getElementById('kpi-today-tx');
                    const todayRev = document.getElementById('kpi-today-rev');

                    if (todayTx && todayTx.textContent != d.today_transactions) {
                        flashUpdate(todayTx);
                        todayTx.textContent = fmtNum(d.today_transactions);
                    }
                    if (todayRev) {
                        const newRev = fmtRp(d.today_revenue);
                        if (todayRev.textContent !== newRev) { flashUpdate(todayRev); todayRev.textContent = newRev; }
                    }

                    // Update live dot
                    const dot = document.getElementById('realtimeDot');
                    if (dot) dot.style.opacity = '1';

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
