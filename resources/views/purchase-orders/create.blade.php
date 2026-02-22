@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('purchase-orders.index') }}" class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Purchase Order</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm" class="space-y-6">
            @csrf
            <!-- Info PO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" required
                        class="w-full px-3 py-2 border @error('supplier_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Diharapkan Tiba</label>
                    <input type="date" name="expected_date" value="{{ old('expected_date') }}" min="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-700">

            <!-- Daftar Item -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">Item Pesanan</h3>
                    <button type="button" onclick="addItem()"
                        class="inline-flex items-center gap-1.5 text-sm text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-plus-circle"></i> Tambah Item
                    </button>
                </div>

                <div id="itemsContainer" class="space-y-3">
                    <!-- Item pertama otomatis -->
                </div>

                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Total Estimasi</span>
                        <span id="grandTotal" class="text-xl font-bold text-primary-600">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-paper-plane mr-2"></i>Buat Purchase Order
                </button>
                <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const products = @json($products);
let itemCount = 0;

function addItem() {
    itemCount++;
    const container = document.getElementById('itemsContainer');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-12 gap-2 items-start';
    div.id = `item-${itemCount}`;
    div.innerHTML = `
        <div class="col-span-5">
            <select name="items[${itemCount}][product_id]" required onchange="updatePrice(${itemCount}, this.value)"
                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                <option value="">-- Pilih Produk --</option>
                ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (Stok: ${p.stock})</option>`).join('')}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemCount}][quantity]" placeholder="Jumlah" min="1" required
                onchange="updateTotal()"
                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
        </div>
        <div class="col-span-3">
            <div class="relative">
                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                <input type="number" name="items[${itemCount}][unit_price]" id="price-${itemCount}" placeholder="Harga Beli" min="0" required
                    onchange="updateTotal()"
                    class="w-full pl-7 pr-2 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
            </div>
        </div>
        <div class="col-span-2 flex justify-end">
            <button type="button" onclick="removeItem(${itemCount})"
                class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
}

function updatePrice(itemId, productId) {
    const product = products.find(p => p.id == productId);
    if (product) {
        document.getElementById(`price-${itemId}`).value = product.price;
        updateTotal();
    }
}

function removeItem(itemId) {
    const el = document.getElementById(`item-${itemId}`);
    if (el) el.remove();
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('#itemsContainer > div').forEach(row => {
        const qty = parseFloat(row.querySelector('input[type=number]')?.value || 0);
        const price = parseFloat(row.querySelectorAll('input[type=number]')[1]?.value || 0);
        total += qty * price;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Tambah item pertama
addItem();

// Header label
const container = document.getElementById('itemsContainer');
const header = document.createElement('div');
header.className = 'grid grid-cols-12 gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1';
header.innerHTML = `<div class="col-span-5">Produk</div><div class="col-span-2">Qty</div><div class="col-span-3">Harga Beli</div><div class="col-span-2"></div>`;
container.before(header);
</script>
@endpush
