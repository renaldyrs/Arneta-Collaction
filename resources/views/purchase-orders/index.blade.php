@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Purchase Order</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola pembelian dan restok barang dari supplier</p>
        </div>
        <button onclick="openPoModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Buat PO Baru
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total PO</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-file-invoice text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">PO Pending</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nilai</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-coins text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter + Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="flex flex-wrap gap-2 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <form method="GET" action="{{ route('purchase-orders.index') }}" class="flex flex-wrap gap-2 flex-1">
                <div class="relative min-w-40 flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="No. PO..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20">
                </div>
                <select name="status" class="form-select text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ordered" {{ $status === 'ordered' ? 'selected' : '' }}>Dipesan</option>
                    <option value="received" {{ $status === 'received' ? 'selected' : '' }}>Diterima</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <select name="supplier_id" class="form-select text-sm">
                    <option value="">Semua Supplier</option>
                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplier == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i></button>
                <a href="{{ route('purchase-orders.index') }}" class="btn-secondary"><i class="fas fa-times"></i></a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No. PO</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Supplier</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Dibuat Oleh</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tgl. Harapan</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse ($purchaseOrders as $po)
                        @php
                            switch ($po->status) {
                                case 'pending': $statusBadge = 'badge-yellow'; break;
                                case 'ordered': $statusBadge = 'badge-blue'; break;
                                case 'received': $statusBadge = 'badge-green'; break;
                                case 'cancelled': $statusBadge = 'badge-red'; break;
                                default: $statusBadge = 'badge-gray'; break;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $po->po_number }}</code>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $po->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-800 dark:text-white">{{ $po->supplier->name }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $po->user->name }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge {{ $statusBadge }}">{{ $po->status_label }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-gray-800 dark:text-white">Rp
                                {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500">
                                {{ $po->expected_date ? $po->expected_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('purchase-orders.show', $po) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    @if ($po->status === 'pending')
                                        <a href="{{ route('purchase-orders.edit', $po) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-file-invoice text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada purchase order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($purchaseOrders->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>

    {{-- ═══════════════════════ MODAL CREATE PO ═══════════════════════ --}}
    <div id="poModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePoModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-200 scale-95 opacity-0" id="poModalBox">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30">
                        <i class="fas fa-file-invoice text-emerald-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Buat Purchase Order</h2>
                        <p class="text-xs text-gray-500">Input pesanan barang ke supplier</p>
                    </div>
                </div>
                <button onclick="closePoModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 overflow-y-auto flex-1">
                <form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" required class="form-select w-full">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Estimasi Tiba</label>
                            <input type="date" name="expected_date" min="{{ date('Y-m-d') }}" class="form-input w-full">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan</label>
                            <textarea name="notes" rows="2" class="form-input w-full resize-none" placeholder="Tambahkan catatan jika ada..."></textarea>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-3 border-b border-gray-100 dark:border-gray-700 pb-2">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Item Pesanan</h3>
                            <button type="button" onclick="addItem()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                                <i class="fas fa-plus-circle"></i> Tambah Item
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-3">
                            <div class="grid grid-cols-12 gap-3 text-[10px] font-bold text-gray-400 uppercase px-2">
                                <div class="col-span-6">Produk</div>
                                <div class="col-span-2">Jumlah</div>
                                <div class="col-span-3">Harga Beli</div>
                                <div class="col-span-1"></div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                            <div class="flex items-center justify-between">
                                <div class="text-gray-500">
                                    <p class="text-xs font-bold uppercase tracking-wider">Total Estimasi</p>
                                    <p class="text-[10px]">Harga dapat disesuaikan saat barang datang</p>
                                </div>
                                <span id="grandTotal" class="text-2xl font-black text-emerald-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/50 flex gap-3">
                <button type="button" onclick="closePoModal()" class="btn-secondary flex-1">Batal</button>
                <button type="submit" form="poForm" class="btn-primary flex-[2]">
                    <i class="fas fa-save mr-2"></i> Simpan Purchase Order
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const products = @json($products);
    let itemCount = 0;

    function openPoModal() {
        const modal = document.getElementById('poModal');
        const box = document.getElementById('poModalBox');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        if (itemCount === 0) addItem(); 
        document.body.style.overflow = 'hidden';
    }

    function closePoModal() {
        const modal = document.getElementById('poModal');
        const box = document.getElementById('poModalBox');
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function addItem() {
        itemCount++;
        const container = document.getElementById('itemsContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-3 items-center bg-white dark:bg-gray-800/50 p-2 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-sm transition-all animate-fadeIn';
        div.id = `item-${itemCount}`;
        div.innerHTML = `
            <div class="col-span-6">
                <select name="items[${itemCount}][product_id]" required onchange="updatePrice(${itemCount}, this.value)"
                    class="form-select w-full text-xs">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.cost || p.price}">${p.name} (Stok: ${p.stock})</option>`).join('')}
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemCount}][quantity]" placeholder="Qty" min="1" required
                    onchange="updateTotal()"
                    class="form-input w-full text-xs text-center">
            </div>
            <div class="col-span-3">
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]">Rp</span>
                    <input type="number" name="items[${itemCount}][unit_price]" id="price-${itemCount}" placeholder="Harga" min="0" required
                        onchange="updateTotal()"
                        class="form-input w-full pl-6 pr-2 text-xs">
                </div>
            </div>
            <div class="col-span-1 flex justify-center">
                <button type="button" onclick="removeItem(${itemCount})"
                    class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    function updatePrice(itemId, productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            document.getElementById(`price-${itemId}`).value = product.cost || product.price;
            updateTotal();
        }
    }

    function removeItem(itemId) {
        const container = document.getElementById('itemsContainer');
        if (container.querySelectorAll('.animate-fadeIn').length <= 1) {
            showNotification('Minimal harus ada 1 item', 'warning');
            return;
        }
        const el = document.getElementById(`item-${itemId}`);
        if (el) {
            el.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                el.remove();
                updateTotal();
            }, 200);
        }
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#itemsContainer > .animate-fadeIn').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value || 0);
            const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value || 0);
            total += qty * price;
        });
        document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function showNotification(msg, type) {
        if (typeof notify === 'function') {
            notify(msg, type);
        } else {
            alert(msg);
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease forwards;
    }
</style>
@endpush