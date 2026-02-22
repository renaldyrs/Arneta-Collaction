@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase-orders.index') }}" class="text-gray-400 hover:text-gray-600 transition"><i
                    class="fas fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Purchase Order</h1>
        </div>

        @if(session('error'))
            <div
                class="bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info PO -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <span
                        class="font-mono text-xl font-bold text-gray-800 dark:text-white">{{ $purchaseOrder->po_number }}</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dibuat
                        {{ $purchaseOrder->created_at->format('d M Y, H:i') }} oleh {{ $purchaseOrder->user->name }}</p>
                </div>
                @php
                    $colors = ['pending' => 'yellow', 'ordered' => 'blue', 'received' => 'green', 'cancelled' => 'red'];
                    $c = $colors[$purchaseOrder->status] ?? 'gray';
                @endphp
                <span
                    class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/30 dark:text-{{ $c }}-400">
                    {{ $purchaseOrder->status_label }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-6 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase font-medium">Supplier</p>
                    <p class="font-semibold text-gray-800 dark:text-white mt-1">{{ $purchaseOrder->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase font-medium">Tanggal Diharapkan</p>
                    <p class="font-semibold text-gray-800 dark:text-white mt-1">
                        {{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase font-medium">Tanggal Diterima</p>
                    <p class="font-semibold text-gray-800 dark:text-white mt-1">
                        {{ $purchaseOrder->received_date ? $purchaseOrder->received_date->format('d M Y') : '-' }}</p>
                </div>
            </div>

            @if($purchaseOrder->notes)
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-3 mb-4 text-sm text-blue-700 dark:text-blue-400">
                    <i class="fas fa-info-circle mr-2"></i>{{ $purchaseOrder->notes }}
                </div>
            @endif

            <!-- Items -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-center">Dipesan</th>
                            <th class="px-4 py-3 text-center">Diterima</th>
                            <th class="px-4 py-3 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($purchaseOrder->details as $detail)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ $detail->product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $detail->product->code }} | Stok saat ini:
                                        {{ $detail->product->stock }}</p>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold">{{ $detail->quantity_ordered }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="{{ $detail->quantity_received > 0 ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                                        {{ $detail->quantity_received }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold">Rp
                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right font-bold text-gray-700 dark:text-gray-300">TOTAL
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-primary-600 text-lg">Rp
                                {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                @if($purchaseOrder->status === 'pending')
                    <form action="{{ route('purchase-orders.mark-ordered', $purchaseOrder) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-paper-plane"></i> Tandai Dipesan
                        </button>
                    </form>
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                        class="inline-flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition">
                        <i class="fas fa-edit"></i> Edit PO
                    </a>
                    <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST"
                        onsubmit="return confirm('Batalkan PO ini?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                            <i class="fas fa-times"></i> Batalkan PO
                        </button>
                    </form>
                @elseif($purchaseOrder->status === 'ordered')
                    <button onclick="document.getElementById('receiveModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-check-double"></i> Terima Barang
                    </button>
                    <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST"
                        onsubmit="return confirm('Batalkan PO ini?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                            <i class="fas fa-times"></i> Batalkan PO
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Terima Barang -->
    @if(in_array($purchaseOrder->status, ['pending', 'ordered']))
        <div id="receiveModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Terima Barang</h3>
                    <p class="text-sm text-gray-500 mt-1">Masukkan jumlah barang yang diterima untuk setiap item.</p>
                </div>
                <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST">
                    @csrf
                    <div class="p-5 max-h-96 overflow-y-auto space-y-3">
                        @foreach($purchaseOrder->details as $detail)
                            <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm">{{ $detail->product->name }}</p>
                                    <p class="text-xs text-gray-400">Dipesan: {{ $detail->quantity_ordered }} unit</p>
                                </div>
                                <div class="w-28">
                                    <input type="number" name="received[{{ $detail->id }}][quantity_received]"
                                        value="{{ $detail->quantity_ordered }}" min="0" max="{{ $detail->quantity_ordered }}"
                                        required
                                        class="w-full px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm text-center">
                                    <p class="text-xs text-center text-gray-400 mt-1">Diterima</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-5 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-sm font-medium transition">
                            <i class="fas fa-check mr-2"></i>Konfirmasi Penerimaan & Update Stok
                        </button>
                        <button type="button" onclick="document.getElementById('receiveModal').classList.add('hidden')"
                            class="px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection