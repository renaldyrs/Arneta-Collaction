@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('shifts.index') }}" class="text-gray-400 hover:text-gray-600 transition"><i
                    class="fas fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Shift</h1>
        </div>

        <!-- Info Shift -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <span
                        class="font-mono text-xl font-bold text-gray-800 dark:text-white">{{ $shift->shift_number }}</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kasir: {{ $shift->user->name }}</p>
                </div>
                @if($shift->status === 'open')
                    <span
                        class="px-4 py-2 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-sm font-semibold animate-pulse">●
                        Shift Aktif</span>
                @else
                    <span
                        class="px-4 py-2 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 text-sm font-semibold">Shift
                        Selesai</span>
                @endif
            </div>

            <!-- Statistik Shift -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Modal Awal</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">Rp
                        {{ number_format($shift->opening_cash, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Total Transaksi</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $shift->transactions->count() }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Total Revenue</p>
                    <p class="text-lg font-bold text-green-600 mt-1">Rp
                        {{ number_format($shift->transactions->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
                @if($shift->status === 'closed')
                    <div
                        class="{{ $shift->cash_difference >= 0 ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-red-50 dark:bg-red-900/20' }} rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Selisih Kas</p>
                        <p class="text-lg font-bold {{ $shift->cash_difference >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                            {{ $shift->cash_difference >= 0 ? '+' : '' }}Rp
                            {{ number_format($shift->cash_difference, 0, ',', '.') }}
                        </p>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Dibuka Pukul</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">
                            {{ $shift->opened_at ? $shift->opened_at->format('H:i') : '-' }}</p>
                    </div>
                @endif
            </div>

            <!-- Revenue by Payment Method -->
            @if($revenueByPayment->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Revenue per Metode Pembayaran</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($revenueByPayment as $payment)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment['name'] }}</p>
                                <p class="font-bold text-gray-800 dark:text-white text-sm mt-0.5">Rp
                                    {{ number_format($payment['total'], 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">{{ $payment['count'] }} transaksi</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tutup Shift -->
            @if($shift->status === 'open' && ($shift->user_id === auth()->id() || auth()->user()->role === 'admin'))
                <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Tutup Shift</h3>
                    <form action="{{ route('shifts.close', $shift) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uang Kas Akhir
                                    (Aktual) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="closing_cash" required min="0" step="1000"
                                        class="w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                                    Penutupan</label>
                                <textarea name="closing_notes" rows="1"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm resize-none"></textarea>
                            </div>
                        </div>
                        <button type="submit" onclick="return confirm('Tutup shift sekarang?')"
                            class="w-full md:w-auto inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                            <i class="fas fa-stop-circle"></i> Tutup Shift & Rekonsiliasi Kas
                        </button>
                    </form>
                </div>
            @endif

            @if($shift->status === 'closed')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mt-2">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Kas yang Diharapkan</p>
                        <p class="font-bold text-gray-800 dark:text-white">Rp
                            {{ number_format($shift->expected_cash, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Kas Aktual</p>
                        <p class="font-bold text-gray-800 dark:text-white">Rp
                            {{ number_format($shift->closing_cash, 0, ',', '.') }}</p>
                    </div>
                    @if($shift->closing_notes)
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Catatan Penutupan</p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mt-1">{{ $shift->closing_notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Transaksi dalam Shift ini -->
        @if($shift->transactions->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Transaksi dalam Shift ini
                        ({{ $shift->transactions->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
                    @foreach($shift->transactions as $trx)
                        <div class="px-4 py-3 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white text-sm">{{ $trx->invoice_number }}</p>
                                <p class="text-xs text-gray-400">{{ $trx->created_at->format('H:i') }} •
                                    {{ $trx->paymentMethod->name ?? '-' }}</p>
                            </div>
                            <p class="font-bold text-green-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection