@extends('layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Purchase Order</h1>
            <span class="font-mono text-sm text-gray-500 dark:text-gray-400">{{ $purchaseOrder->po_number }}</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" id="poForm"
                class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier <span
                                class="text-red-500">*</span></label>
                        <select name="supplier_id" required
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ $purchaseOrder->supplier_id == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Diharapkan
                            Tiba</label>
                        <input type="date" name="expected_date"
                            value="{{ $purchaseOrder->expected_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm resize-none">{{ $purchaseOrder->notes }}</textarea>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <div>
                    <div
                        class="grid grid-cols-12 gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">
                        <div class="col-span-5">Produk</div>
                        <div class="col-span-2">Qty</div>
                        <div class="col-span-3">Harga Beli</div>
                        <div class="col-span-2"></div>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">Item Pesanan</h3>
                        <button type="button" onclick="addItem()"
                            class="inline-flex items-center gap-1.5 text-sm text-primary-600 hover:text-primary-700 font-medium">
                            <i class="fas fa-plus-circle"></i> Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-3">
                        @foreach($purchaseOrder->details as $i => $detail)
                            <div class="grid grid-cols-12 gap-2 items-start" id="item-existing-{{ $i }}">
                                <div class="col-span-5">
                                    <select name="items[{{ $i }}][product_id]" required
                                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $detail->product_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="items[{{ $i }}][quantity]"
                                        value="{{ $detail->quantity_ordered }}" min="1" required onchange="updateTotal()"
                                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                                </div>
                                <div class="col-span-3">
                                    <div class="relative">
                                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                        <input type="number" name="items[{{ $i }}][unit_price]"
                                            value="{{ $detail->unit_price }}" min="0" required onchange="updateTotal()"
                                            class="w-full pl-7 pr-2 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                                    </div>
                                </div>
                                <div class="col-span-2 flex justify-end">
                                    <button type="button" onclick="this.closest('[id^=item]').remove(); updateTotal()"
                                        class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Total Estimasi</span>
                            <span id="grandTotal" class="text-xl font-bold text-primary-600">Rp
                                {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                        class="px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const products = @json($products);
        let itemCount = 1000; // avoid conflict with existing items

        function addItem() {
            itemCount++;
            const container = document.getElementById('itemsContainer');
            const div = document.createElement('div');
            div.className = 'grid grid-cols-12 gap-2 items-start';
            div.id = `item-${itemCount}`;
            div.innerHTML = `
            <div class="col-span-5">
                <select name="items[${itemCount}][product_id]" required
                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemCount}][quantity]" placeholder="Qty" min="1" required onchange="updateTotal()"
                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
            </div>
            <div class="col-span-3">
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                    <input type="number" name="items[${itemCount}][unit_price]" placeholder="Harga" min="0" required onchange="updateTotal()"
                        class="w-full pl-7 pr-2 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                </div>
            </div>
            <div class="col-span-2 flex justify-end">
                <button type="button" onclick="this.closest('[id^=item]').remove(); updateTotal()"
                    class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
            container.appendChild(div);
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#itemsContainer > div').forEach(row => {
                const inputs = row.querySelectorAll('input[type=number]');
                const qty = parseFloat(inputs[0]?.value || 0);
                const price = parseFloat(inputs[1]?.value || 0);
                total += qty * price;
            });
            document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        updateTotal();
    </script>
@endpush