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

    @if(!empty($productsMissingCostCount) && $productsMissingCostCount > 0)
        <div class="mb-4 p-4 border-l-4 border-amber-400 bg-amber-50 text-amber-800 rounded">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <strong>Peringatan:</strong> Terdapat <strong>{{ $productsMissingCostCount }}</strong> produk dengan nilai `cost` kosong/0.
                    @if(!empty($productsMissingCostSamples))
                        <div class="text-sm mt-1">Contoh:
                            @foreach($productsMissingCostSamples as $idx => $prod)
                                @if(is_array($prod) && isset($prod['id']))
                                    <a href="{{ route('products.edit', $prod['id']) }}" class="underline text-amber-700">{{ $prod['name'] }}</a>@if($idx < count($productsMissingCostSamples)-1), @endif
                                @else
                                    {{ is_array($prod) ? ($prod['name'] ?? '#') : $prod }}@if($idx < count($productsMissingCostSamples)-1), @endif
                                @endif
                            @endforeach
                            @if($productsMissingCostCount > count($productsMissingCostSamples)) , ... @endif
                        </div>
                    @endif
                    <div class="text-xs mt-2 text-gray-600">Silakan perbarui nilai `cost` pada produk yang bersangkutan untuk perhitungan persediaan akurat.</div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('products.fill-missing-cost') }}" onsubmit="return confirm('Isi nilai cost default untuk produk yang belum memiliki cost?');">
                        @csrf
                        <button type="submit" class="btn-danger">Isi cost default</button>
                    </form>
                    <a href="{{ route('products.index') }}" class="btn-secondary">Kelola Produk</a>
                </div>
            </div>
        </div>
    @endif

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

    {{-- KPI Cards - Laporan Laba Rugi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        {{-- Pendapatan Kotor --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5" style="background: #3b82f6; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pend. Kotor</p>
                <i class="fas fa-arrow-trending-up text-blue-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mt-1">sebelum diskon</p>
        </div>

        {{-- Total Diskon --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5" style="background: #f59e0b; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Diskon</p>
                <i class="fas fa-tag text-amber-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1">{{ $totalGrossRevenue > 0 ? number_format(($totalDiscount/$totalGrossRevenue)*100, 1) : 0 }}% dari gross</p>
        </div>

        {{-- Pendapatan Bersih --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5" style="background: #10b981; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pend. Bersih</p>
                <i class="fas fa-wallet text-emerald-500 text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1">
                <i class="fas fa-arrow-up text-[10px]"></i> 
                @if($revenueChange >= 0)
                    {{ number_format($revenueChange, 1) }}% vs periode lalu
                @else
                    {{ number_format($revenueChange, 1) }}% vs periode lalu
                @endif
            </p>
        </div>

        {{-- Laba Bersih --}}
        <div class="bg-gradient-to-br {{ $netProfit >= 0 ? 'from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20' : 'from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20' }} rounded-2xl p-5 border {{ $netProfit >= 0 ? 'border-emerald-200 dark:border-emerald-800/50' : 'border-red-200 dark:border-red-800/50' }} shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5" style="background: {{ $netProfit >= 0 ? '#059669' : '#dc2626' }}; transform: translate(30%,-30%);"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laba Bersih</p>
                <i class="fas {{ $netProfit >= 0 ? 'fa-chart-line text-emerald-500' : 'fa-arrow-down text-red-500' }} text-lg"></i>
            </div>
            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
            <p class="text-xs {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-medium mt-1">
                Margin: {{ number_format($marginPercentage, 1) }}%
                @if($profitChange >= 0 && $profitChange != 0)
                    | <i class="fas fa-arrow-up text-[10px]"></i> {{ number_format($profitChange, 1) }}%
                @elseif($profitChange < 0)
                    | <i class="fas fa-arrow-down text-[10px]"></i> {{ number_format($profitChange, 1) }}%
                @endif
            </p>
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
                        $pct = $totalNetRevenue > 0 ? round(($method['total'] / $totalNetRevenue) * 100, 1) : 0;
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

    {{-- Breakdown Pengeluaran --}}
    @if($expensesByCategory->count() > 0 || $totalExpenses > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-money-bill text-red-500"></i> Breakdown Pengeluaran
                </h3>
            </div>
            <div class="p-5">
                @if($expensesByCategory->count() > 0)
                    <div class="space-y-3">
                        @php
                            $expenseColors = ['#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16'];
                        @endphp
                        @foreach($expensesByCategory as $idx => $expense)
                            @php
                                $color = $expenseColors[$idx % count($expenseColors)];
                                $percentage = $totalExpenses > 0 ? ($expense->total / $totalExpenses) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($expense->category) ?? 'Lainnya' }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($expense->total, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="relative h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700" 
                                        style="width: {{ $percentage }}%; background: {{ $color }};"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ number_format($percentage, 1) }}% ({{ $expense->count }} item)</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Total Pengeluaran</span>
                            <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-400 text-sm text-center py-4">Tidak ada pengeluaran pada periode ini</p>
                @endif
            </div>
        </div>

        {{-- Ringkasan Laporan Laba Rugi --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i> Ringkasan Laba Rugi
                </h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Pendapatan Kotor</span>
                    <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Diskon</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400">- Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Nilai Persediaan (stok x cost)</span>
                    <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($inventoryValue ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 px-2 rounded">
                    <span class="font-semibold text-gray-800 dark:text-white">Pendapatan Bersih</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Pengeluaran</span>
                    <span class="font-bold text-red-600 dark:text-red-400">- Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 bg-gradient-to-r {{ $netProfit >= 0 ? 'from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30' : 'from-red-50 to-rose-50 dark:from-red-900/30 dark:to-rose-900/30' }} px-3 rounded-lg">
                    <span class="font-bold text-gray-900 dark:text-white">Laba Bersih</span>
                    <span class="text-lg font-bold {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs pt-2">
                    <span class="text-gray-600 dark:text-gray-400">Margin Keuntungan</span>
                    <span class="font-bold {{ $marginPercentage >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ number_format($marginPercentage, 2) }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif

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