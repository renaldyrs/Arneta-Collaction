@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Pesanan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Riwayat transaksi dan analitik penjualan</p>
        </div>
        <div class="flex gap-2 no-print">
            <button onclick="window.print()" class="btn-secondary">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('reports.export-csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="btn-primary" title="Download data transaksi sebagai file CSV">
                <i class="fas fa-file-excel"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- KPI Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-receipt text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-dollar-sign text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk Terlaris</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-trophy text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-base font-bold text-gray-900 dark:text-white truncate">{{ $mostSoldProduct['name'] ?? '—' }}</p>
            <p class="text-xs text-amber-600 font-medium">{{ $mostSoldProduct['sold'] ?? 0 }} terjual</p>
        </div>
    </div>

    {{-- Filter + Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
        {{-- Filter --}}
        <div class="lg:col-span-1 no-print">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-filter text-emerald-500"></i> Filter Tanggal
                    </h3>
                </div>
                <div class="p-5">
                    <form method="GET" class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                                Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-input">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                                Akhir</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-input">
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center"><i class="fas fa-filter"></i>
                            Terapkan</button>
                        <a href="{{ route('reports.index') }}"
                            class="btn-secondary w-full justify-center text-center block">Reset</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="lg:col-span-3">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list text-emerald-500"></i> Riwayat Transaksi
                        @if($startDate || $endDate)
                            <span class="badge badge-blue ml-1">{{ $startDate }} – {{ $endDate }}</span>
                        @endif
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Invoice</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Item</th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Bayar</th>
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
                                            class="text-xs font-mono font-semibold text-emerald-600 dark:text-emerald-400">{{ $tx->invoice_number }}</code>
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $tx->created_at->format('d M Y · H:i') }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($tx->details as $d)
                                                <span class="badge badge-gray text-xs">{{ $d->product->name }}
                                                    ×{{ $d->quantity }}{{ $d->size ? " ($d->size)" : '' }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-gray-800 dark:text-white">Rp
                                        {{ number_format($tx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        @php $pm = $tx->paymentMethod->name ?? ''; @endphp
                                        <span
                                            class="badge {{ $pm == 'Tunai' ? 'badge-green' : ($pm == 'Transfer Bank' ? 'badge-blue' : 'badge-purple') }}">{{ $pm }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center no-print">
                                        <a href="{{ route('cashier.invoice', $tx->id) }}"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                            title="Lihat Invoice">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                        <i class="fas fa-receipt text-3xl mb-2 block opacity-20"></i>
                                        <p class="text-sm">Tidak ada transaksi untuk periode ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                        {{ $transactions->appends(request()->query())->links('vendor.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
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