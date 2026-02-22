@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Analisis pendapatan, pengeluaran & profit bisnis</p>
        </div>
        <div class="flex gap-2 no-print">
            <button onclick="window.print()" class="btn-secondary"><i class="fas fa-print"></i> Cetak</button>
            <a href="{{ route('financial-reports.export-excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn-primary" title="Download laporan sebagai file CSV/Excel">
               <i class="fas fa-file-excel"></i> Export CSV
            </a>
            <a href="{{ route('financial-reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn-secondary" title="Download laporan sebagai PDF">
               <i class="fas fa-file-pdf text-red-500"></i> PDF
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-4 mb-5 no-print">
        <form action="{{ route('reports.financial') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-36">
                <label
                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                    Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-input">
            </div>
            <div class="flex-1 min-w-36">
                <label
                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                    Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-input">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <a href="{{ route('reports.financial') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        {{-- Total Pendapatan --}}
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5"
                style="background: #10b981; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pendapatan
                </p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-wallet text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1">
                <i class="fas fa-arrow-up text-[10px]"></i> Periode yang dipilih
            </p>
        </div>

        {{-- Jumlah Transaksi --}}
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5"
                style="background: #6366f1; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Transaksi
                </p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-receipt text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->count() }}</p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-1">transaksi terkonfirmasi</p>
        </div>

        {{-- Rata-rata --}}
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5"
                style="background: #f59e0b; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rata-rata
                    Transaksi</p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-calculator text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                Rp {{ $transactions->count() > 0 ? number_format($totalIncome / $transactions->count(), 0, ',', '.') : 0 }}
            </p>
            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1">per transaksi</p>
        </div>
    </div>

    {{-- Payment Method Summary --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-credit-card text-emerald-500"></i> Ringkasan Metode Pembayaran
            </h3>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @php
                    $methodColors = [
                        'Tunai' => ['color' => '#10b981', 'bg' => 'rgba(16,185,129,0.1)'],
                        'Transfer Bank' => ['color' => '#6366f1', 'bg' => 'rgba(99,102,241,0.1)'],
                        'QRIS' => ['color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.1)']
                    ];
                @endphp
                @foreach($paymentMethodSummary as $i => $method)
                    @php
                        $colors = ['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6'];
                        $c = $colors[$i % count($colors)];
                        $pct = $totalIncome > 0 ? round(($method['total'] / $totalIncome) * 100, 1) : 0;
                    @endphp
                    <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-semibold text-gray-800 dark:text-white text-sm">{{ $method['name'] }}</span>
                            <span class="badge badge-blue">{{ $method['count'] }} tx</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white mb-2">Rp
                            {{ number_format($method['total'], 0, ',', '.') }}</p>
                        <div class="relative">
                            <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700"
                                    style="width: {{ $pct }}%; background: {{ $c }};"></div>
                            </div>
                            <p class="text-right text-xs text-gray-400 mt-1">{{ $pct }}% dari total</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i> Detail Transaksi
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No. Invoice</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kasir</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Metode</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider no-print">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $tx->invoice_number }}</code>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 dark:text-gray-400">
                                {{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400">{{ auth()->user()->name }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @php $mn = $tx->paymentMethod->name ?? ''; @endphp
                                <span
                                    class="badge {{ $mn === 'Tunai' ? 'badge-green' : ($mn === 'Transfer Bank' ? 'badge-blue' : 'badge-purple') }}">{{ $mn }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-gray-800 dark:text-white">Rp
                                {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-center no-print">
                                <a href="{{ route('cashier.invoice', $tx->id) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-chart-line text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Tidak ada data transaksi pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection