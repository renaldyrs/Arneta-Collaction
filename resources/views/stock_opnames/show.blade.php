@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-opnames.index') }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Stock Opname</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $stockOpname->reference_number }} • {{ \Carbon\Carbon::parse($stockOpname->opname_date)->format('d M Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if ($stockOpname->status === 'pending')
                <form action="{{ route('stock-opnames.complete', $stockOpname->id) }}" method="POST" onsubmit="return confirm('Selesaikan Stock Opname? Stok sistem akan diperbarui sesuai stok fisik.')">
                    @csrf
                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i> Selesaikan & Update Stok
                    </button>
                </form>
            @else
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">SESI SELESAI</span>
            @endif
        </div>
    </div>

    @if ($stockOpname->notes)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-4 rounded-2xl mb-6 flex gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <p class="text-sm text-blue-700 dark:text-blue-300"><strong>Catatan:</strong> {{ $stockOpname->notes }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <form action="{{ route('stock-opnames.update', $stockOpname->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Produk / Ukuran</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Sistem</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Stok Fisik</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($stockOpname->details as $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $detail->product->name }}</span>
                                        @if ($detail->size)
                                            <span class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mt-0.5">UKURAN: {{ $detail->size->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                                    {{ $detail->system_stock }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($stockOpname->status === 'pending')
                                        <input type="number" name="physical_stocks[{{ $detail->id }}]" 
                                               value="{{ $detail->physical_stock }}" min="0" 
                                               class="w-full px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:border-emerald-500 outline-none"
                                               oninput="calcDiff(this, {{ $detail->system_stock }}, 'diff-{{ $detail->id }}')">
                                    @else
                                        <span class="font-bold text-gray-800 dark:text-white">{{ $detail->physical_stock }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span id="diff-{{ $detail->id }}" class="text-sm font-bold {{ $detail->adjustment_quantity < 0 ? 'text-red-500' : ($detail->adjustment_quantity > 0 ? 'text-blue-500' : 'text-gray-400') }}">
                                        {{ $detail->adjustment_quantity > 0 ? '+' : '' }}{{ $detail->adjustment_quantity }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($stockOpname->status === 'pending')
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Draft Fisik
                    </button>
                </div>
            @endif
        </form>
    </div>

    <script>
        function calcDiff(input, system, targetId) {
            const physical = parseInt(input.value) || 0;
            const diff = physical - system;
            const target = document.getElementById(targetId);
            target.textContent = (diff > 0 ? '+' : '') + diff;
            target.className = 'text-sm font-bold ' + (diff < 0 ? 'text-red-500' : (diff > 0 ? 'text-blue-500' : 'text-gray-400'));
        }
    </script>
@endsection
