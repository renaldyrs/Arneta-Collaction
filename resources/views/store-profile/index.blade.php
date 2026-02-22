@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Toko</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Informasi dan identitas toko Anda</p>
        </div>
        <a href="{{ route('store-profile.edit') }}" class="btn-primary">
            <i class="fas fa-edit text-sm"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                {{-- Banner --}}
                

                <div class="px-5 pb-5">
                    {{-- Logo --}}
                    <div class="mt-10 mb-4 flex justify-center">
                        @php
                            $logoUrl = null;
                            if (!empty($profile->logo)) {
                                $logoUrl = filter_var($profile->logo, FILTER_VALIDATE_URL) ? $profile->logo : asset('storage/' . $profile->logo);
                            }
                        @endphp
                        <div class="w-20 h-20 rounded-2xl border-4 border-white dark:border-gray-800 overflow-hidden shadow-lg flex items-center justify-center"
                            style="background: linear-gradient(135deg, #0d9373, #14b890);">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo {{ $profile->name }}" class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; this.nextSibling.style.display='flex';">
                                <div style="display:none;" class="w-full h-full items-center justify-center">
                                    <i class="fas fa-store text-white text-2xl"></i>
                                </div>
                            @else
                                <i class="fas fa-store text-white text-2xl"></i>
                            @endif
                        </div>
                    </div>

                    <div class="text-center mb-5">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $profile->name ?? 'Nama Toko' }}</h2>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mt-0.5">
                            {{ $profile->type ?? 'Retail Store' }}</p>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(16,185,129,0.06);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                style="background: rgba(16,185,129,0.12);">
                                <i class="fas fa-map-marker-alt text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Alamat</p>
                                <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->address ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(99,102,241,0.06);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                style="background: rgba(99,102,241,0.12);">
                                <i class="fas fa-phone text-indigo-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Telepon</p>
                                <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->phone ?? '—' }}</p>
                            </div>
                        </div>
                        @if(!empty($profile->email))
                            <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(245,158,11,0.06);">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(245,158,11,0.12);">
                                    <i class="fas fa-envelope text-amber-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Email</p>
                                    <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->email }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('store-profile.edit') }}" class="btn-primary w-full justify-center mt-5">
                        <i class="fas fa-edit"></i> Edit Profil Toko
                    </a>
                </div>
            </div>
        </div>

        {{-- Additional Info --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(16,185,129,0.12);">
                        <i class="fas fa-box text-emerald-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Product::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Produk</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-users text-indigo-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Customer::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pelanggan</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(245,158,11,0.12);">
                        <i class="fas fa-receipt text-amber-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Transaction::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Transaksi</p>
                </div>
            </div>

            {{-- System Info --}}
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-info-circle text-emerald-500"></i> Informasi Sistem
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            Versi Aplikasi</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Arneta POS v2.0</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Mata
                            Uang</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">IDR (Rupiah)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Zona
                            Waktu</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Asia/Jakarta (WIB)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            Terakhir Diperbarui</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ $profile->updated_at ? $profile->updated_at->format('d M Y') : '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-emerald-500"></i> Akses Cepat
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $quickLinks = [
                            ['route' => 'products.index', 'icon' => 'fa-box', 'label' => 'Produk', 'color' => '#0d9373'],
                            ['route' => 'categories.index', 'icon' => 'fa-layer-group', 'label' => 'Kategori', 'color' => '#6366f1'],
                            ['route' => 'payment.index', 'icon' => 'fa-credit-card', 'label' => 'Metode Bayar', 'color' => '#f59e0b'],
                            ['route' => 'users.index', 'icon' => 'fa-users', 'label' => 'Pengguna', 'color' => '#ef4444'],
                        ];
                    @endphp
                    @foreach($quickLinks as $link)
                        <a href="{{ route($link['route']) }}"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                                style="background: {{ $link['color'] }}1a;">
                                <i class="fas {{ $link['icon'] }} text-sm" style="color: {{ $link['color'] }};"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 text-center">{{ $link['label'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection