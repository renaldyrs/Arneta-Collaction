@extends('layouts.app')
@section('content')

    {{-- ─── Header ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Semua transaksi
                @if(auth()->user()->role !== 'admin')
                    oleh <span class="font-semibold text-emerald-600">{{ auth()->user()->name }}</span>
                @endif
                · Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} –
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('transactions.export-csv', request()->query()) }}" class="btn-primary"
                title="Export ke CSV/Excel">
                <i class="fas fa-file-excel"></i> Export CSV
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            @endif
        </div>
    </div>

    {{-- ─── KPI Stats ─── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-10" style="background:#0d9373;"></div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Total Transaksi
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1">transaksi periode ini</p>
        </div>
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-10" style="background:#6366f1;"></div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Total Pendapatan
            </p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-1">omzet periode ini</p>
        </div>
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-10" style="background:#f59e0b;"></div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Rata-rata</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($avgTransaction, 0, ',', '.') }}
            </p>
            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1">per transaksi</p>
        </div>
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden">
            <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full opacity-10" style="background:#ef4444;"></div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Total Diskon</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalDiscount, 0, ',', '.') }}
            </p>
            <p class="text-xs text-red-500 font-medium mt-1">diskon diberikan</p>
        </div>
    </div>

    {{-- ─── Filter Panel ─── --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden mb-5">
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-2">
            <i class="fas fa-filter text-emerald-500 text-sm"></i>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Filter & Pencarian</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('transactions.index') }}" id="filterForm" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Dari
                            Tanggal</label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-input pl-8 text-sm">
                        </div>
                    </div>
                    {{-- Tanggal Akhir --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Sampai
                            Tanggal</label>
                        <div class="relative">
                            <i
                                class="fas fa-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-input pl-8 text-sm">
                        </div>
                    </div>
                    {{-- Cari Invoice --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Cari</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="No. Invoice / Pelanggan..."
                                class="form-input pl-8 text-sm">
                        </div>
                    </div>
                    {{-- Metode Bayar --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Metode
                            Bayar</label>
                        <select name="payment_method_id" class="form-select text-sm w-full">
                            <option value="">Semua Metode</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}" {{ $paymentId == $pm->id ? 'selected' : '' }}>{{ $pm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Pelanggan --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Pelanggan</label>
                        <select name="customer_id" class="form-select text-sm w-full">
                            <option value="">Semua Pelanggan</option>
                            <option value="0" {{ request('customer_id') === '0' ? 'selected' : '' }}>Tanpa Pelanggan (Umum)
                            </option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ $customerId == $cust->id ? 'selected' : '' }}>{{ $cust->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Kasir (Admin Only) --}}
                    @if(auth()->user()->role === 'admin')
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Kasir</label>
                            <select name="user_id" class="form-select text-sm w-full">
                                <option value="">Semua Kasir</option>
                                @foreach($kasirList as $kasir)
                                    <option value="{{ $kasir->id }}" {{ $kasirId == $kasir->id ? 'selected' : '' }}>{{ $kasir->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- Urutan --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Urutkan</label>
                        <select name="sort" class="form-select text-sm w-full">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Tertinggi</option>
                            <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Terendah</option>
                        </select>
                    </div>
                    {{-- Shortcut Tanggal --}}
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Cepat</label>
                        <div class="flex gap-1 flex-wrap">
                            <button type="button" onclick="setDateRange('today')" class="shortcut-btn">Hari Ini</button>
                            <button type="button" onclick="setDateRange('week')" class="shortcut-btn">7 Hari</button>
                            <button type="button" onclick="setDateRange('month')" class="shortcut-btn">Bulan Ini</button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Terapkan</button>
                    <a href="{{ route('transactions.index') }}" class="btn-secondary"><i class="fas fa-times"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Tabel Transaksi ─── --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center gap-2">
                <i class="fas fa-receipt text-emerald-500"></i>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Daftar Transaksi</h3>
                <span class="badge badge-green">{{ $transactions->total() }} transaksi</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Invoice</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal & Waktu</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                            Pelanggan</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">
                            Item</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Metode</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total</th>
                        @if(auth()->user()->role === 'admin')
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">
                                Kasir</th>
                        @endif
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($transactions as $tx)
                        @php
                            $pmName = $tx->paymentMethod->name ?? '';
                            $pmBadge = match (true) {
                                str_contains($pmName, 'Tunai') => 'badge-green',
                                str_contains($pmName, 'Transfer') || str_contains($pmName, 'Bank') => 'badge-blue',
                                str_contains($pmName, 'QRIS') || str_contains($pmName, 'QR') => 'badge-purple',
                                default => 'badge-gray'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors group" id="tx-{{ $tx->id }}">
                            {{-- Invoice --}}
                            <td class="px-4 py-3.5">
                                <code class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                                    {{ $tx->invoice_number }}
                                                </code>
                                @if($tx->discount_amount > 0)
                                    <span class="badge badge-yellow text-[10px] ml-1">
                                        <i class="fas fa-tag text-[8px]"></i> Diskon
                                    </span>
                                @endif
                                @if($tx->notes)
                                    <p class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[120px]" title="{{ $tx->notes }}">
                                        <i class="fas fa-note-sticky"></i> {{ $tx->notes }}
                                    </p>
                                @endif
                            </td>
                            {{-- Tanggal --}}
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-gray-800 dark:text-white text-xs">
                                    {{ $tx->created_at->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-400 font-mono">{{ $tx->created_at->format('H:i:s') }}</p>
                                <p class="text-[10px] text-gray-300 dark:text-gray-600 mt-0.5">
                                    {{ $tx->created_at->diffForHumans() }}
                                </p>
                            </td>
                            {{-- Pelanggan --}}
                            <td class="px-4 py-3.5 hidden md:table-cell">
                                @if($tx->customer)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                            style="background: linear-gradient(135deg,#0d9373,#6366f1);">
                                            {{ strtoupper(substr($tx->customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-800 dark:text-white">{{ $tx->customer->name }}
                                            </p>
                                            <p class="text-[10px] text-gray-400">{{ $tx->customer->phone ?? '' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Umum</span>
                                @endif
                            </td>
                            {{-- Item --}}
                            <td class="px-4 py-3.5 hidden lg:table-cell">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @foreach($tx->details->take(3) as $d)
                                        <span class="badge badge-gray text-[10px]">
                                            {{ $d->product->name ?? '?' }} ×{{ $d->quantity }}
                                            @if($d->size) <span class="opacity-60">({{ $d->size }})</span> @endif
                                        </span>
                                    @endforeach
                                    @if($tx->details->count() > 3)
                                        <span class="badge badge-blue text-[10px]">+{{ $tx->details->count() - 3 }} lagi</span>
                                    @endif
                                </div>
                            </td>
                            {{-- Metode --}}
                            <td class="px-4 py-3.5 text-center">
                                <span class="badge {{ $pmBadge }}">{{ $pmName ?: '-' }}</span>
                            </td>
                            {{-- Total --}}
                            <td class="px-4 py-3.5 text-right">
                                <p class="font-bold text-gray-800 dark:text-white">Rp
                                    {{ number_format($tx->total_amount, 0, ',', '.') }}
                                </p>
                                @if($tx->discount_amount > 0)
                                    <p class="text-[10px] text-amber-600 mt-0.5">
                                        Hemat Rp {{ number_format($tx->discount_amount, 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-[10px] text-gray-400 mt-0.5">Bayar: Rp
                                    {{ number_format($tx->payment_amount, 0, ',', '.') }}
                                </p>
                            </td>
                            {{-- Kasir (Admin only) --}}
                            @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-3.5 hidden xl:table-cell">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0"
                                            style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                                            {{ strtoupper(substr($tx->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $tx->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                            @endif
                            {{-- Aksi --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Lihat Invoice --}}
                                    <a href="{{ route('cashier.invoice', $tx->id) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Lihat Invoice">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    {{-- Cetak Struk --}}
                                    <a href="{{ route('cashier.print', $tx->id) }}" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        title="Cetak Struk">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    {{-- Detail (expand) --}}
                                    <button type="button" onclick="toggleDetail({{ $tx->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors"
                                        title="Lihat Detail Item">
                                        <i class="fas fa-list-ul text-xs"></i>
                                    </button>
                                    {{-- Retur (Admin) --}}
                                    @if(auth()->user()->role === 'admin')
                                        <button type="button" onclick="openReturnModalTx({{ $tx->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            title="Buat Retur">
                                            <i class="fas fa-rotate-left text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        {{-- Expanded Detail Row --}}
                        <tr id="detail-{{ $tx->id }}" class="hidden bg-emerald-50/30 dark:bg-emerald-900/10">
                            <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="px-6 py-4">
                                <div class="flex flex-wrap gap-3">
                                    @foreach($tx->details as $d)
                                        <div
                                            class="flex items-center gap-2 bg-white dark:bg-gray-800 rounded-xl px-3 py-2 shadow-sm border border-gray-100 dark:border-gray-700">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                                style="background: rgba(16,185,129,0.1);">
                                                <i class="fas fa-box text-emerald-500 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-800 dark:text-white">
                                                    {{ $d->product->name ?? 'Unknown' }}
                                                </p>
                                                <p class="text-[10px] text-gray-400">
                                                    {{ $d->quantity }} × Rp {{ number_format($d->price, 0, ',', '.') }}
                                                    @if($d->size) · Ukuran: <span class="font-medium">{{ $d->size }}</span> @endif
                                                </p>
                                            </div>
                                            <div class="ml-2 text-right">
                                                <p class="text-xs font-bold text-gray-800 dark:text-white">Rp
                                                    {{ number_format($d->subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div
                                        class="flex items-center gap-2 bg-emerald-600 text-white rounded-xl px-3 py-2 shadow-sm">
                                        <div>
                                            <p class="text-xs font-semibold">Total {{ $tx->details->sum('quantity') }} item</p>
                                            <p class="text-[10px] opacity-80">Kembalian: Rp
                                                {{ number_format($tx->change_amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}"
                                class="px-5 py-14 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center"
                                        style="background: rgba(16,185,129,0.08);">
                                        <i class="fas fa-receipt text-3xl text-emerald-400 opacity-50"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak ada transaksi
                                        ditemukan</p>
                                    @if($search || $paymentId || $customerId)
                                        <a href="{{ route('transactions.index') }}"
                                            class="text-emerald-500 text-xs hover:underline">
                                            Hapus filter untuk melihat semua
                                        </a>
                                    @else
                                        <p class="text-xs text-gray-400">Belum ada transaksi pada periode ini</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $transactions->links('vendor.tailwind') }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .shortcut-btn {
                font-size: 0.7rem;
                font-weight: 600;
                padding: 0.25rem 0.6rem;
                border-radius: 0.5rem;
                border: 1.5px solid #e2e8f0;
                background: #f9fafb;
                color: #4a5568;
                cursor: pointer;
                transition: all 0.15s;
            }

            .shortcut-btn:hover {
                border-color: #0d9373;
                background: rgba(13, 147, 115, 0.06);
                color: #0d9373;
            }

            .dark .shortcut-btn {
                border-color: #2d3f55;
                background: rgba(255, 255, 255, 0.04);
                color: #9ca3af;
            }

            .dark .shortcut-btn:hover {
                border-color: #14b890;
                color: #14b890;
            }
        </style>
    @endpush

    <script>
        // Toggle detail baris
        function toggleDetail(id) {
            const row = document.getElementById('detail-' + id);
            if (row) row.classList.toggle('hidden');
        }

        // Shortcut tanggal
        function setDateRange(type) {
            const start = document.querySelector('input[name="start_date"]');
            const end = document.querySelector('input[name="end_date"]');
            const now = new Date();
            const fmt = (d) => d.toISOString().split('T')[0];

            if (type === 'today') {
                start.value = fmt(now);
                end.value = fmt(now);
            } else if (type === 'week') {
                const week = new Date(now);
                week.setDate(week.getDate() - 6);
                start.value = fmt(week);
                end.value = fmt(now);
            } else if (type === 'month') {
                const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                start.value = fmt(firstDay);
                end.value = fmt(lastDay);
            }
            document.getElementById('filterForm').submit();
            }

            // ─── Buka modal retur dari Riwayat Transaksi ────────────────
            function openReturnModalTx(transactionId) {
                openReturnModal();
                // Delay sedikit agar modal sudah tampil
                setTimeout(() => fetchTransactionById(transactionId), 100);
            }
        </script>

        {{-- ══════════════════════════════════════════════════════
             MODAL RETUR (embedded)
        ══════════════════════════════════════════════════════ --}}
        <div id="returnModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
             role="dialog" aria-modal="true">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReturnModal()"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden
                        transform transition-all duration-300 scale-95 opacity-0"
                 id="returnModalPanel">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/60"
                     style="background: linear-gradient(135deg, rgba(239,68,68,0.05), rgba(245,158,11,0.03));">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                            <i class="fas fa-rotate-left text-red-500"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Buat Pengajuan Retur</h3>
                            <p class="text-xs text-gray-400" id="returnModalSubtitle">Memuat data transaksi...</p>
                        </div>
                    </div>
                    <button onclick="closeReturnModal()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <div id="returnLoadingState" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-emerald-500 text-2xl mb-3 block"></i>
                        <p class="text-sm text-gray-400">Memuat data transaksi...</p>
                    </div>

                    <form id="returnForm" action="{{ route('returns.store') }}" method="POST" class="space-y-4 hidden">
                        @csrf
                        <input type="hidden" name="transaction_id" id="modal_transaction_id">

                        {{-- Info Transaksi --}}
                        <div id="transactionInfo"
                             class="px-4 py-3 rounded-xl text-xs"
                             style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2);">
                        </div>

                        {{-- Error --}}
                        <div id="returnError" class="hidden px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation"></i>
                            <span></span>
                        </div>

                        {{-- Pilih Produk --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-box text-red-500 mr-1"></i> Produk yang Diretur
                            </label>
                            <select name="product_id" id="modal_product_id" class="form-select w-full text-sm" required
                                    onchange="updateMaxQty()">
                                <option value="">— Pilih Produk —</option>
                            </select>
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-cubes text-red-500 mr-1"></i>
                                Jumlah Retur
                                <span id="maxQtyLabel" class="normal-case text-gray-400 font-normal ml-1"></span>
                            </label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="adjustQty(-1)"
                                    class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-bold text-lg">−</button>
                                <input type="number" name="quantity" id="modal_qty"
                                    class="form-input text-center font-bold text-lg w-20" value="1" min="1" required>
                                <button type="button" onclick="adjustQty(1)"
                                    class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-bold text-lg">+</button>
                                <div class="flex-1 text-right">
                                    <p class="text-xs text-gray-400">Estimasi Refund</p>
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400 text-sm" id="refundAmount">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-comment text-red-500 mr-1"></i> Alasan Retur
                            </label>
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach(['Produk rusak/cacat', 'Salah ukuran', 'Tidak sesuai pesanan', 'Produk tidak berfungsi'] as $reason)
                                    <button type="button" onclick="setReason('{{ $reason }}')"
                                        class="text-[11px] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:border-red-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        {{ $reason }}
                                    </button>
                                @endforeach
                            </div>
                            <textarea name="reason" id="modal_reason" rows="2"
                                class="form-input w-full text-sm resize-none"
                                placeholder="Tuliskan alasan retur secara detail..." required></textarea>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-700/20">
                    <button type="button" onclick="closeReturnModal()" class="btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" id="submitReturnBtn"
                        onclick="document.getElementById('returnForm').submit()"
                        class="hidden"
                        style="display:none; padding: 0.5rem 1rem; background: #ef4444; color: white; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.375rem;">
                        <i class="fas fa-paper-plane"></i> Ajukan Retur
                    </button>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .shortcut-btn {
                    font-size: 0.7rem; font-weight: 600; padding: 0.25rem 0.6rem;
                    border-radius: 0.5rem; border: 1.5px solid #e2e8f0; background: #f9fafb;
                    color: #4a5568; cursor: pointer; transition: all 0.15s;
                }
                .shortcut-btn:hover { border-color: #0d9373; background: rgba(13,147,115,0.06); color: #0d9373; }
                .dark .shortcut-btn { border-color: #2d3f55; background: rgba(255,255,255,0.04); color: #9ca3af; }
                .dark .shortcut-btn:hover { border-color: #14b890; color: #14b890; }
                #returnModal.active { display: flex !important; }
                #returnModal.active #returnModalPanel { opacity: 1 !important; transform: scale(1) !important; }
            </style>
        @endpush

        <script>
            // ── Modal State ──────────────────────────────────────────────
            function openReturnModal() {
                const modal = document.getElementById('returnModal');
                modal.style.display = 'flex';
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    const panel = document.getElementById('returnModalPanel');
                    panel.style.opacity = '1';
                    panel.style.transform = 'scale(1)';
                }));
                document.body.style.overflow = 'hidden';
            }

            function closeReturnModal() {
                const panel = document.getElementById('returnModalPanel');
                panel.style.opacity = '0';
                panel.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    document.getElementById('returnModal').style.display = 'none';
                    resetReturnForm();
                }, 250);
                document.body.style.overflow = '';
            }

            function resetReturnForm() {
                document.getElementById('modal_transaction_id').value = '';
                document.getElementById('modal_product_id').innerHTML = '<option value="">— Pilih Produk —</option>';
                document.getElementById('modal_qty').value = 1;
                document.getElementById('modal_reason').value = '';
                document.getElementById('returnForm').classList.add('hidden');
                document.getElementById('returnLoadingState').classList.remove('hidden');
                document.getElementById('submitReturnBtn').style.display = 'none';
                document.getElementById('refundAmount').textContent = 'Rp 0';
                document.getElementById('maxQtyLabel').textContent = '';
                document.getElementById('returnModalSubtitle').textContent = 'Memuat data transaksi...';
            }

            // ── Load dari ID langsung ────────────────────────────────────
            function openReturnModalTx(transactionId) {
                openReturnModal();
                resetReturnForm();
                setTimeout(() => fetchTransactionById(transactionId), 80);
            }

            function fetchTransactionById(id) {
                fetch(`/api/v1/transactions/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => { if (data.success) loadTransactionData(data.transaction); })
                .catch(() => {
                    document.getElementById('returnModalSubtitle').textContent = 'Gagal memuat data.';
                });
            }

            function loadTransactionData(tx) {
                document.getElementById('modal_transaction_id').value = tx.id;

                const date = new Date(tx.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});
                document.getElementById('transactionInfo').innerHTML = `
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">${tx.invoice_number}</span>
                        <span class="text-gray-400">·</span>
                        <span class="text-gray-500 text-xs">${date}</span>
                    </div>
                    <div class="flex gap-4 text-gray-500 text-xs">
                        <span>${tx.details.length} jenis produk</span>
                        <span>Rp ${parseInt(tx.total_amount).toLocaleString('id-ID')}</span>
                        ${tx.customer ? `<span>${tx.customer.name}</span>` : ''}
                    </div>`;

                const select = document.getElementById('modal_product_id');
                select.innerHTML = '<option value="">— Pilih Produk —</option>';
                tx.details.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.product_id;
                    opt.dataset.qty   = d.quantity;
                    opt.dataset.price = d.price;
                    opt.textContent = `${d.product?.name || 'Produk'} — beli: ${d.quantity} × Rp ${parseInt(d.price).toLocaleString('id-ID')}`;
                    select.appendChild(opt);
                });

                document.getElementById('returnLoadingState').classList.add('hidden');
                document.getElementById('returnForm').classList.remove('hidden');
                document.getElementById('submitReturnBtn').style.display = 'inline-flex';
                document.getElementById('returnModalSubtitle').textContent = 'Invoice ' + tx.invoice_number;

                updateMaxQty();
            }

            function updateMaxQty() {
                const select = document.getElementById('modal_product_id');
                const opt = select.options[select.selectedIndex];
                if (!opt || !opt.dataset.qty) return;
                const maxQty = parseInt(opt.dataset.qty);
                const price  = parseFloat(opt.dataset.price);
                const qtyInput = document.getElementById('modal_qty');
                qtyInput.max = maxQty;
                if (parseInt(qtyInput.value) > maxQty) qtyInput.value = maxQty;
                document.getElementById('maxQtyLabel').textContent = `(maks. ${maxQty})`;
                calcRefund(price);
            }

            function calcRefund(price) {
                if (!price) {
                    const opt = document.getElementById('modal_product_id').options[document.getElementById('modal_product_id').selectedIndex];
                    price = opt ? parseFloat(opt.dataset.price) : 0;
                }
                const qty = parseInt(document.getElementById('modal_qty').value) || 0;
                document.getElementById('refundAmount').textContent = 'Rp ' + (price * qty).toLocaleString('id-ID');
            }

            function adjustQty(delta) {
                const input = document.getElementById('modal_qty');
                const newVal = Math.max(1, Math.min(parseInt(input.max) || 999, parseInt(input.value) + delta));
                input.value = newVal;
                calcRefund();
            }

            document.getElementById('modal_qty').addEventListener('input', () => calcRefund());

            function setReason(text) {
                document.getElementById('modal_reason').value = text;
            }
        </script>
@endsection