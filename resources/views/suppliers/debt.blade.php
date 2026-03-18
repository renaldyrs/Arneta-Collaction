@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ringkasan Hutang Supplier</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola kewajiban pembayaran kepada pemasok</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 px-6 py-3 rounded-2xl flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-800 flex items-center justify-center text-red-600 dark:text-red-400">
                <i class="fas fa-hand-holding-usd text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] text-red-600 dark:text-red-400 font-bold uppercase tracking-wider">Total Hutang Global</p>
                <p class="text-xl font-black text-red-700 dark:text-red-300">Rp {{ number_format($totalGlobalDebt) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($suppliers as $supplier)
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-md transition-all overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-50 dark:border-gray-700/30 flex justify-between items-start bg-gray-50/30 dark:bg-gray-700/10">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">{{ $supplier->name }}</h3>
                        <p class="text-xs text-gray-400 flex items-center gap-2 mt-1">
                            <i class="fas fa-phone-alt"></i> {{ $supplier->phone }}
                        </p>
                    </div>
                    <div class="px-3 py-1 bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg text-[10px] font-black tracking-tighter border border-red-100 dark:border-red-900/20">
                        {{ $supplier->active_pos->count() }} PO BERHUTANG
                    </div>
                </div>

                {{-- Debt Details --}}
                <div class="p-6 flex-1">
                    <div class="space-y-4">
                        @foreach ($supplier->active_pos as $po)
                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter group-hover:text-indigo-500 transition-colors">{{ $po->po_number }}</p>
                                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-0.5">Sisa: Rp {{ number_format($po->total_amount - $po->paid_amount) }}</p>
                                </div>
                                <div class="w-7 h-7 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center text-gray-300 group-hover:text-indigo-500 transition-colors">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Summary --}}
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-50 dark:border-gray-700/30 flex justify-between items-center text-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Total Sisa Tagihan :</span>
                    <span class="text-lg font-black text-red-600 dark:text-red-400">Rp {{ number_format($supplier->total_debt) }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-gray-400">
                <div class="w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center mb-4">
                    <i class="fas fa-check-circle text-4xl text-emerald-500/30"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-600 dark:text-gray-300">Semua Hutang Lunas</h3>
                <p class="text-sm">Tidak ada tagihan yang tertunggak saat ini.</p>
            </div>
        @endforelse
    </div>
@endsection
