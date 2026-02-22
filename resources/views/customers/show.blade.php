@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pelanggan</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Pelanggan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $customer->name }}</h2>
                        <span
                            class="text-xs bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-0.5 rounded-full">⭐
                            {{ number_format($customer->points) }} poin</span>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $customer->phone ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $customer->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm">
                        <i class="fas fa-map-marker-alt w-5 text-gray-400 mt-0.5"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $customer->address ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i class="fas fa-calendar w-5 text-gray-400"></i>
                        <span class="text-gray-700 dark:text-gray-300">Bergabung
                            {{ $customer->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Transaksi</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalTransactions }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Belanja</p>
                        <p class="text-sm font-bold text-green-600">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <a href="{{ route('customers.edit', $customer) }}"
                        class="flex-1 text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                        onsubmit="return confirm('Hapus pelanggan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Riwayat Transaksi Terakhir</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentTransactions as $trx)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $trx->invoice_number }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $trx->paymentMethod->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 dark:text-gray-500">
                            <i class="fas fa-receipt text-3xl mb-2 block opacity-30"></i>
                            <p class="text-sm">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection