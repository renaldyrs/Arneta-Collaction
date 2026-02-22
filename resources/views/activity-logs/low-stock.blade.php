@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-amber-500"></i> Peringatan Stok
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Produk dengan stok menipis atau sudah habis</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="btn-primary">
            <i class="fas fa-cart-plus"></i> Buat Purchase Order
        </a>
    </div>

    @php
        $outCount = $outOfStockProducts->count();
        $lowCount = $lowStockProducts->count();
    @endphp

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Habis</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                    <i class="fas fa-times-circle text-red-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $outCount }}</p>
            <p class="text-xs text-red-500 font-medium mt-1">produk perlu restock segera</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Menipis</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowCount }}</p>
            <p class="text-xs text-amber-500 font-medium mt-1">produk di bawah batas minimum</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Perhatian</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-bell text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $outCount + $lowCount }}</p>
            <p class="text-xs text-indigo-500 font-medium mt-1">total produk butuh tindakan</p>
        </div>
    </div>

    @if($outOfStockProducts->isEmpty() && $lowStockProducts->isEmpty())
        {{-- All Good --}}
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-16 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4"
                style="background: rgba(16,185,129,0.12);">
                <i class="fas fa-check-circle text-emerald-500 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Semua Stok Aman! 🎉</h3>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada produk yang membutuhkan perhatian saat ini.</p>
            <a href="{{ route('products.index') }}" class="btn-primary mt-4 inline-flex">
                <i class="fas fa-box"></i> Lihat Semua Produk
            </a>
        </div>
    @endif

    {{-- Stok Habis --}}
    @if($outOfStockProducts->isNotEmpty())
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-red-200 dark:border-red-800/50 overflow-hidden mb-5">
            <div class="px-5 py-4 border-b border-red-100 dark:border-red-800/50 flex items-center gap-3"
                style="background: rgba(239,68,68,0.04);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                    <i class="fas fa-times-circle text-red-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-700 dark:text-red-400">Stok Habis</h3>
                    <p class="text-xs text-red-500">{{ $outOfStockProducts->count() }} produk · Perlu restock segera</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-red-50/60 dark:bg-red-900/10">
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Produk</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Supplier</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Stok</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($outOfStockProducts as $product)
                            <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $product->code }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="badge badge-gray text-xs">{{ $product->category->name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $product->supplier->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="badge badge-red font-bold">HABIS</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 transition-colors">
                                            <i class="fas fa-edit"></i> Edit Stok
                                        </a>
                                        <a href="{{ route('purchase-orders.create') }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 transition-colors">
                                            <i class="fas fa-cart-plus"></i> PO
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Stok Menipis --}}
    @if($lowStockProducts->isNotEmpty())
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-amber-200 dark:border-amber-800/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-amber-100 dark:border-amber-800/50 flex items-center gap-3"
                style="background: rgba(245,158,11,0.04);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-700 dark:text-amber-400">Stok Menipis</h3>
                    <p class="text-xs text-amber-500">{{ $lowStockProducts->count() }} produk · Di bawah batas minimum</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-amber-50/60 dark:bg-amber-900/10">
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Produk</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Supplier</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Stok Saat Ini</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Min. Stok</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($lowStockProducts as $product)
                            @php
                                $pct = $product->low_stock_threshold > 0 ? round(($product->stock / $product->low_stock_threshold) * 100) : 0;
                                $pct = min($pct, 100);
                            @endphp
                            <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $product->code }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="badge badge-gray text-xs">{{ $product->category->name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $product->supplier->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div>
                                        <span class="badge badge-yellow font-bold text-sm">{{ $product->stock }}</span>
                                        <div
                                            class="w-16 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden mt-1.5 mx-auto">
                                            <div class="h-full rounded-full transition-all"
                                                style="width: {{ $pct }}%; background: #f59e0b;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-400">
                                    {{ $product->low_stock_threshold }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 transition-colors">
                                            <i class="fas fa-edit"></i> Edit Stok
                                        </a>
                                        <a href="{{ route('purchase-orders.create') }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 transition-colors">
                                            <i class="fas fa-cart-plus"></i> PO
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection