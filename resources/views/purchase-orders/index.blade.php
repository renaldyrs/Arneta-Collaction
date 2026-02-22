@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Purchase Order</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola pembelian dan restok barang dari supplier</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Buat PO Baru
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total PO</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-file-invoice text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">PO Pending</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nilai</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-coins text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter + Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="flex flex-wrap gap-2 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <form method="GET" action="{{ route('purchase-orders.index') }}" class="flex flex-wrap gap-2 flex-1">
                <div class="relative min-w-40 flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="No. PO..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20">
                </div>
                <select name="status" class="form-select text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ordered" {{ $status === 'ordered' ? 'selected' : '' }}>Dipesan</option>
                    <option value="received" {{ $status === 'received' ? 'selected' : '' }}>Diterima</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <select name="supplier_id" class="form-select text-sm">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplier == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i></button>
                <a href="{{ route('purchase-orders.index') }}" class="btn-secondary"><i class="fas fa-times"></i></a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No. PO</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Supplier</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Dibuat Oleh</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tgl. Harapan</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($purchaseOrders as $po)
                        @php
                            $statusBadge = match ($po->status) {
                                'pending' => 'badge-yellow',
                                'ordered' => 'badge-blue',
                                'received' => 'badge-green',
                                'cancelled' => 'badge-red',
                                default => 'badge-gray'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $po->po_number }}</code>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $po->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-800 dark:text-white">{{ $po->supplier->name }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $po->user->name }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge {{ $statusBadge }}">{{ $po->status_label }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-gray-800 dark:text-white">Rp
                                {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500">
                                {{ $po->expected_date ? $po->expected_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('purchase-orders.show', $po) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    @if($po->status === 'pending')
                                        <a href="{{ route('purchase-orders.edit', $po) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-file-invoice text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada purchase order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchaseOrders->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>
@endsection