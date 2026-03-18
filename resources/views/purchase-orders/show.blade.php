@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase-orders.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Purchase Order</h1>
        </div>

        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-100 dark:bg-emerald-900/20 border border-emerald-300 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Info PO -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            {{-- Header Info --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-xl font-bold text-gray-800 dark:text-white">{{ $purchaseOrder->po_number }}</span>
                        <a href="{{ route('purchase-orders.print', $purchaseOrder) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="Cetak Surat Pesanan">
                            <i class="fas fa-print text-xs"></i>
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dibuat {{ $purchaseOrder->created_at->format('d M Y, H:i') }} oleh {{ $purchaseOrder->user->name }}</p>
                </div>
                <div class="flex flex-col md:flex-row md:items-center gap-2">
                    @php
                        $colors = ['pending' => 'yellow', 'ordered' => 'blue', 'received' => 'green', 'cancelled' => 'red'];
                        $c = $colors[$purchaseOrder->status] ?? 'gray';
                        
                        $pColors = ['unpaid' => 'red', 'partial' => 'amber', 'paid' => 'emerald'];
                        $pc = $pColors[$purchaseOrder->payment_status] ?? 'gray';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-{{ $c }}-50 text-{{ $c }}-600 border border-{{ $c }}-100 uppercase">
                        {{ $purchaseOrder->status }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-{{ $pc }}-50 text-{{ $pc }}-600 border border-{{ $pc }}-100 uppercase">
                        {{ $purchaseOrder->payment_status ?: 'unpaid' }}
                    </span>
                </div>
            </div>

            {{-- Debt Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-6 text-sm border border-gray-100 dark:border-gray-700/30">
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Supplier</p>
                    <p class="font-semibold text-gray-800 dark:text-white mt-1">{{ $purchaseOrder->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Total Tagihan</p>
                    <p class="font-bold text-gray-800 dark:text-white mt-1">Rp {{ number_format($purchaseOrder->total_amount) }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Sudah Dibayar</p>
                    <p class="font-bold text-emerald-600 mt-1">Rp {{ number_format($purchaseOrder->paid_amount) }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Sisa Hutang</p>
                    <p class="font-bold text-red-500 mt-1">Rp {{ number_format($purchaseOrder->total_amount - $purchaseOrder->paid_amount) }}</p>
                </div>
            </div>

            {{-- Dates and Notes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700/30 text-xs text-gray-600 dark:text-gray-400">
                    <span class="font-bold mr-2 uppercase tracking-tighter opacity-50">Estimasi Tiba:</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('d M Y') : '-' }}</span>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700/30 text-xs text-gray-600 dark:text-gray-400">
                    <span class="font-bold mr-2 uppercase tracking-tighter opacity-50">Diterima Pada:</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchaseOrder->received_date ? $purchaseOrder->received_date->format('d M Y') : '-' }}</span>
                </div>
            </div>

            @if ($purchaseOrder->notes)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-3 mb-6 text-sm text-blue-700 dark:text-blue-400">
                    <i class="fas fa-info-circle mr-2 opacity-50"></i>{{ $purchaseOrder->notes }}
                </div>
            @endif

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-center">Dipesan</th>
                            <th class="px-4 py-3 text-center">Diterima</th>
                            <th class="px-4 py-3 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($purchaseOrder->details as $detail)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ $detail->product->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $detail->product->code }}</p>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">{{ $detail->quantity_ordered }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="{{ $detail->quantity_received > 0 ? 'text-emerald-600 font-bold' : 'text-gray-400' }}">
                                        {{ $detail->quantity_received }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($detail->unit_price) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-white">Rp {{ number_format($detail->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payments section -->
            <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Riwayat Pembayaran</h3>
                    @if ($purchaseOrder->payment_status !== 'paid' && $purchaseOrder->status !== 'cancelled')
                        <button onclick="document.getElementById('paymentModal').classList.remove('hidden')" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-[10px] font-bold hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-plus"></i> Catat Pembayaran
                        </button>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-[10px] uppercase font-bold tracking-tight">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Metode</th>
                                <th class="px-4 py-2 text-left">Catatan</th>
                                <th class="px-4 py-2 text-right">Jumlah</th>
                                <th class="px-4 py-2 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($purchaseOrder->payments as $payment)
                                <tr>
                                    <td class="px-4 py-3 text-xs font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ $payment->paymentMethod->name }}</td>
                                    <td class="px-4 py-3 text-gray-400 italic text-[10px]">{{ $payment->notes ?: '-' }}</td>
                                    <td class="px-4 py-3 text-right text-xs font-bold text-emerald-600">Rp {{ number_format($payment->amount) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('purchase-payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pembayaran ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic text-xs">Belum ada pembayaran dicatat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                @if ($purchaseOrder->status === 'pending')
                    <form action="{{ route('purchase-orders.mark-ordered', $purchaseOrder) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary py-2 text-sm">
                            <i class="fas fa-paper-plane mr-1.5"></i> Tandai Dipesan
                        </button>
                    </form>
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="px-4 py-2 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg text-sm font-bold hover:bg-amber-100 transition">
                        <i class="fas fa-edit mr-1.5"></i> Edit PO
                    </a>
                @elseif ($purchaseOrder->status === 'ordered')
                    <button onclick="document.getElementById('receiveModal').classList.remove('hidden')" class="btn-primary py-2 text-sm" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-double mr-1.5"></i> Terima Barang
                    </button>
                @endif

                @if ($purchaseOrder->status !== 'received' && $purchaseOrder->status !== 'cancelled')
                    <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Batalkan PO ini?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-sm font-bold hover:bg-red-100 transition">
                            <i class="fas fa-times mr-1.5"></i> Batalkan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @include('purchase-orders.show_payment_modal')

    <!-- Modal Terima Barang -->
    @if (in_array($purchaseOrder->status, ['pending', 'ordered']))
        <div id="receiveModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Terima Barang</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Konfirmasi jumlah barang fisik yang masuk ke gudang.</p>
                    </div>
                    <button onclick="document.getElementById('receiveModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST">
                    @csrf
                    <div class="p-5 max-h-96 overflow-y-auto space-y-3">
                        @foreach ($purchaseOrder->details as $detail)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700/30">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 dark:text-white text-sm">{{ $detail->product->name }}</p>
                                    <p class="text-[10px] text-gray-400">Pesanan: {{ $detail->quantity_ordered }} unit</p>
                                </div>
                                <div class="w-32">
                                    <div class="relative">
                                        <input type="number" name="received[{{ $detail->id }}][quantity_received]"
                                            value="{{ $detail->quantity_ordered }}" min="0" max="{{ $detail->quantity_ordered }}"
                                            required
                                            class="w-full pl-3 pr-10 py-2 border border-blue-200 dark:border-blue-900/50 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-bold text-center">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400 pointer-events-none">PCS</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-5 border-t border-gray-100 dark:border-gray-700 flex gap-3 bg-gray-50/50 dark:bg-gray-700/30">
                        <button type="submit" class="btn-primary flex-1 py-2.5">
                            <i class="fas fa-check mr-2"></i>Konfirmasi & Update Stok
                        </button>
                        <button type="button" onclick="document.getElementById('receiveModal').classList.add('hidden')" class="btn-secondary px-6">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection