@extends('layouts.app')

@section('content')
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            font-size: 0.875rem;
            animation: notifSlideIn 0.3s ease;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 320px;
        }

        .notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .notification.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .notification.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        @keyframes notifSlideIn {
            from {
                transform: translateX(110%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            color: #374151;
            background: #f9fafb;
            transition: all 0.15s;
            flex-shrink: 0;
            line-height: 1;
        }

        .qty-btn:hover {
            background: #0d9373;
            border-color: #0d9373;
            color: white;
        }

        .dark .qty-btn {
            background: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }

        .dark .qty-btn:hover {
            background: #0d9373;
            border-color: #0d9373;
            color: white;
        }

        .quick-cash-btn {
            padding: 0.35rem 0.4rem;
            border-radius: 0.5rem;
            border: 1.5px solid #e5e7eb;
            font-size: 0.68rem;
            font-weight: 700;
            cursor: pointer;
            color: #374151;
            background: #f9fafb;
            transition: all 0.15s;
            text-align: center;
        }

        .quick-cash-btn:hover {
            border-color: #0d9373;
            background: rgba(13, 147, 115, 0.08);
            color: #0d9373;
        }

        .dark .quick-cash-btn {
            background: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }

        .dark .quick-cash-btn:hover {
            border-color: #0d9373;
            color: #10b981;
        }

        .category-btn.active {
            background: #0d9373 !important;
            color: white !important;
            border-color: transparent !important;
        }
    </style>

    <div class="min-h-screen" style="background: #f0f4f8;">
        <div class="container mx-auto px-4 py-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kasir</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Proses transaksi penjualan</p>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-user-circle text-emerald-500"></i>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <span class="text-gray-300">|</span>
                    <i class="fas fa-clock text-emerald-500"></i>
                    <span id="liveClock">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── Katalog Produk ── --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-emerald-600/20"
                            style="background: linear-gradient(135deg, #0d9373 0%, #065f46 100%);">
                            <h2 class="text-base font-bold text-white flex items-center gap-2">
                                <i class="fas fa-shopping-bag text-white/80"></i> Katalog Produk
                            </h2>
                            <span class="text-white/60 text-xs">{{ count($products) }} produk</span>
                        </div>
                        <div class="p-5">
                            {{-- Search --}}
                            <div class="relative mb-3">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="productSearch" placeholder="Cari nama produk atau barcode..."
                                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20">
                            </div>
                            {{-- Kategori --}}
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <button
                                    class="category-btn active px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all"
                                    data-category="all">Semua</button>
                                @foreach($categories as $cat)
                                    <button
                                        class="category-btn px-3 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 border border-transparent hover:bg-gray-200 transition-all"
                                        data-category="{{ $cat->id }}">{{ $cat->name }}</button>
                                @endforeach
                            </div>
                            {{-- Grid Produk --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto max-h-[33rem] pr-1"
                                id="productGrid">
                                @foreach($products as $product)
                                    <div class="product-card bg-white dark:bg-gray-700/80 p-3 rounded-xl border border-gray-100 dark:border-gray-600/50 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all cursor-pointer flex flex-col group select-none"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                        data-category="{{ $product->category_id }}" data-barcode="{{ $product->barcode ?? '' }}"
                                        data-sizes="{{ $product->sizes->map(fn($s) => ['name' => $s->name, 'stock' => $s->pivot->stock])->toJson() }}">
                                        <div
                                            class="h-28 bg-gray-50 dark:bg-gray-600/50 rounded-lg mb-2.5 overflow-hidden flex items-center justify-center relative">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="flex flex-col items-center gap-1 opacity-25">
                                                    <i class="fas fa-box text-2xl text-gray-400"></i>
                                                </div>
                                            @endif
                                            @if($product->stock <= 0)
                                                <div
                                                    class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-lg">
                                                    <span
                                                        class="text-white text-[10px] font-bold bg-red-500 px-2 py-0.5 rounded-full">HABIS</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <p class="font-semibold text-gray-800 dark:text-white text-xs truncate">
                                                {{ $product->name }}</p>
                                            <div class="flex items-center justify-between mt-1.5">
                                                <span class="font-bold text-emerald-600 dark:text-emerald-400 text-xs">Rp
                                                    {{ number_format($product->price) }}</span>
                                                <span
                                                    class="text-[10px] {{ $product->stock > 5 ? 'text-gray-400' : ($product->stock > 0 ? 'text-amber-500 font-semibold' : 'text-red-500 font-bold') }}">
                                                    Stok: {{ $product->stock }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Keranjang ── --}}
                <div class="col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden sticky top-4">
                        {{-- Header Keranjang --}}
                        <div class="flex items-center justify-between px-5 py-4 border-b border-emerald-600/20"
                            style="background: linear-gradient(135deg, #0d9373 0%, #0f766e 100%);">
                            <h2 class="text-base font-bold text-white flex items-center gap-2">
                                <i class="fas fa-shopping-cart text-white/80"></i>
                                Keranjang
                                <span id="cartCount"
                                    class="bg-white/20 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1 hidden">0</span>
                            </h2>
                            <button id="startScannerBtn" title="Scan Barcode"
                                class="w-9 h-9 flex items-center justify-center rounded-xl text-white/70 hover:text-white transition-colors"
                                style="background: rgba(255,255,255,0.12);">
                                <i class="fas fa-barcode text-sm"></i>
                            </button>
                        </div>

                        {{-- Scanner Modal --}}
                        <div id="scannerModal"
                            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-bold dark:text-white">Scan Barcode</h2>
                                    <button id="stopScannerBtn"
                                        class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="scanner"
                                    class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden"></div>
                                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-3">Arahkan kamera ke
                                    barcode produk</p>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            {{-- Cart Items --}}
                            <div class="min-h-[80px] max-h-52 overflow-y-auto" id="cartItems">
                                <div id="emptyCartMessage" class="text-center py-7">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2"
                                        style="background: rgba(16,185,129,0.08);">
                                        <i class="fas fa-shopping-cart text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm text-gray-400">Keranjang masih kosong</p>
                                    <p class="text-xs text-gray-300 mt-0.5">Klik produk untuk menambahkan</p>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 dark:border-gray-700"></div>

                            {{-- Kode Diskon --}}
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kode
                                    Diskon (Opsional)</label>
                                <div class="flex gap-2">
                                    <input type="text" id="discountCode" placeholder="Masukkan kode..."
                                        class="form-input flex-1 text-sm uppercase" autocomplete="off"
                                        style="text-transform:uppercase">
                                    <button id="applyDiscountBtn"
                                        class="px-3 py-2 rounded-xl text-xs font-bold transition-colors border"
                                        style="background: rgba(13,147,115,0.08); border-color: rgba(13,147,115,0.3); color: #0d9373;">
                                        Pakai
                                    </button>
                                </div>
                                <p id="discountMsg" class="text-xs mt-1.5 hidden font-medium"></p>
                            </div>

                            {{-- Ringkasan Harga --}}
                            <div class="rounded-xl p-3.5 space-y-2"
                                style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15);">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                    <span class="font-semibold text-gray-800 dark:text-white" id="subtotal">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Diskon</span>
                                    <span class="font-semibold text-red-500" id="discountDisplay">Rp 0</span>
                                </div>
                                <div
                                    class="flex justify-between pt-2 border-t border-emerald-100 dark:border-emerald-900/30">
                                    <span class="font-bold text-gray-900 dark:text-white">Total</span>
                                    <span class="font-bold text-xl text-emerald-600 dark:text-emerald-400" id="total">Rp
                                        0</span>
                                </div>
                            </div>

                            {{-- Pelanggan --}}
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Pelanggan
                                    (Opsional)</label>
                                <select id="customerSelect" class="form-select w-full text-sm">
                                    <option value="">— Tanpa Pelanggan —</option>
                                    @foreach(\App\Models\Customer::orderBy('name')->get() as $cust)
                                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Metode
                                    Pembayaran</label>
                                <select id="paymentMethod" class="form-select w-full text-sm">
                                    <option value="">— Pilih Metode —</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}"
                                            data-cash="{{ (str_contains(strtolower($method->name), 'tunai') || str_contains(strtolower($method->name), 'cash')) ? '1' : '0' }}">
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah Bayar (tunai) --}}
                            <div id="cashSection">
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Jumlah
                                    Bayar</label>
                                <div class="relative mb-2">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-semibold">Rp</span>
                                    <input type="number" id="paymentAmount" class="form-input pl-9 w-full text-sm"
                                        placeholder="0" min="0">
                                </div>
                                {{-- Quick Cash --}}
                                <div class="grid grid-cols-4 gap-1">
                                    <button type="button" class="quick-cash-btn" data-amount="5000">5rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="10000">10rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="20000">20rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="50000">50rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="100000">100rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="200000">200rb</button>
                                    <button type="button" class="quick-cash-btn" data-amount="500000">500rb</button>
                                    <button type="button" class="quick-cash-btn" data-exact="1"
                                        style="color:#0d9373; border-color:#0d9373;">Pas</button>
                                </div>
                            </div>

                            {{-- Kembalian --}}
                            <div id="changeRow">
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kembalian</label>
                                <div class="px-3 py-2.5 rounded-xl font-bold text-emerald-600 dark:text-emerald-400 text-sm"
                                    style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);">
                                    <span id="changeAmount">Rp 0</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <button id="cancelBtn" class="btn-secondary py-3 justify-center text-sm">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button id="checkoutBtn"
                                    class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                                    style="background: linear-gradient(135deg, #0d9373, #059669); box-shadow: 0 4px 14px rgba(13,147,115,0.3);"
                                    disabled>
                                    <i class="fas fa-check-circle"></i> Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Struk --}}
    <div id="receiptModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-receipt text-emerald-500"></i> Struk Transaksi
                </h3>
                <button onclick="closeReceiptModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div id="receiptBody" class="p-4 overflow-y-auto flex-1 bg-gray-50 dark:bg-gray-900">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-emerald-500 text-2xl"></i>
                    <p class="text-sm text-gray-400 mt-2">Memuat struk...</p>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex gap-2 justify-end">
                <button id="printReceiptBtn" class="btn-primary"><i class="fas fa-print"></i> Cetak</button>
                <button onclick="closeReceiptModal()" class="btn-secondary">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── STATE ─────────────────────────────────────────────
        let cart           = [];
        let activeDiscount = null; // { id, type, value, code }
        let scannerActive  = false;

        // ── DOM ───────────────────────────────────────────────
        const cartItemsEl     = document.getElementById('cartItems');
        const emptyCartMsg    = document.getElementById('emptyCartMessage');
        const subtotalEl      = document.getElementById('subtotal');
        const totalEl         = document.getElementById('total');
        const discountDisplayEl = document.getElementById('discountDisplay');
        const paymentMethodEl = document.getElementById('paymentMethod');
        const paymentAmountEl = document.getElementById('paymentAmount');
        const changeAmountEl  = document.getElementById('changeAmount');
        const checkoutBtn     = document.getElementById('checkoutBtn');
        const cartCount       = document.getElementById('cartCount');
        const cashSection     = document.getElementById('cashSection');
        const changeRow       = document.getElementById('changeRow');

        // ── LIVE CLOCK ────────────────────────────────────────
        setInterval(() => {
            const n = new Date(), p = s => String(s).padStart(2,'0');
            document.getElementById('liveClock').textContent =
                `${p(n.getDate())}/${p(n.getMonth()+1)}/${n.getFullYear()} ${p(n.getHours())}:${p(n.getMinutes())}`;
        }, 1000);

        // ── HELPERS ───────────────────────────────────────────
        const fmtRp  = n  => 'Rp ' + Math.round(n).toLocaleString('id-ID');
        const parseRp = s => parseInt((s||'').replace(/[^\d]/g,'')) || 0;
        const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

        // ── RENDER CART ───────────────────────────────────────
        function renderCart() {
            if (cart.length === 0) {
                cartItemsEl.innerHTML = '';
                cartItemsEl.appendChild(emptyCartMsg);
                emptyCartMsg.classList.remove('hidden');
                subtotalEl.textContent      = fmtRp(0);
                totalEl.textContent         = fmtRp(0);
                discountDisplayEl.textContent = fmtRp(0);
                checkoutBtn.disabled        = true;
                cartCount.classList.add('hidden');
                return;
            }
            emptyCartMsg.classList.add('hidden');
            cartItemsEl.innerHTML = '';

            const subtotal = cart.reduce((s, i) => s + i.price * i.quantity, 0);
            let discountAmt = 0;
            if (activeDiscount) {
                if (activeDiscount.type === 'percentage') {
                    discountAmt = subtotal * (activeDiscount.value / 100);
                } else {
                    discountAmt = activeDiscount.value;
                }
                discountAmt = Math.min(discountAmt, subtotal);
            }
            const total = subtotal - discountAmt;
            subtotalEl.textContent        = fmtRp(subtotal);
            discountDisplayEl.textContent = fmtRp(discountAmt);
            totalEl.textContent           = fmtRp(total);

            cart.forEach((item, idx) => {
                const el = document.createElement('div');
                el.className = 'flex items-start gap-2 py-2.5 border-b border-gray-50 dark:border-gray-700/50 last:border-0';
                el.innerHTML = `
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-800 dark:text-white truncate">
                            ${item.name}${item.size ? ` <span class="text-emerald-600 font-normal">(${item.size})</span>` : ''}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${fmtRp(item.price)} / pcs</p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <button class="qty-btn" data-idx="${idx}" data-act="dec">−</button>
                            <input type="number" class="qty-input w-10 text-center text-xs font-bold border border-gray-200 dark:border-gray-600 rounded-lg py-0.5 dark:bg-gray-700 dark:text-white"
                                   value="${item.quantity}" min="1" max="${item.stock}" data-idx="${idx}">
                            <button class="qty-btn" data-idx="${idx}" data-act="inc">+</button>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 ml-1">${fmtRp(item.price * item.quantity)}</span>
                        </div>
                    </div>
                    <button class="remove-btn w-7 h-7 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex-shrink-0 mt-1" data-idx="${idx}">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>`;
                cartItemsEl.appendChild(el);
            });

            const totalQty = cart.reduce((s,i) => s + i.quantity, 0);
            cartCount.textContent = totalQty;
            cartCount.classList.remove('hidden');
            updateCheckoutBtn();

            cartItemsEl.querySelectorAll('.qty-input').forEach(inp => {
                inp.addEventListener('change', function() {
                    const idx = parseInt(this.dataset.idx);
                    let v = parseInt(this.value);
                    if (isNaN(v) || v < 1) v = 1;
                    if (v > cart[idx].stock) { v = cart[idx].stock; notify('Stok tidak mencukupi', 'warning'); }
                    cart[idx].quantity = v;
                    renderCart();
                });
            });
        }

        // ── CART EVENTS (qty +/- & hapus) ────────────────────
        cartItemsEl.addEventListener('click', function(e) {
            const qb = e.target.closest('.qty-btn');
            if (qb) {
                const idx = parseInt(qb.dataset.idx), act = qb.dataset.act;
                if (act === 'inc') {
                    if (cart[idx].quantity < cart[idx].stock) { cart[idx].quantity++; renderCart(); }
                    else notify('Stok tidak mencukupi', 'warning');
                } else {
                    if (cart[idx].quantity > 1) { cart[idx].quantity--; renderCart(); }
                }
            }
            const rb = e.target.closest('.remove-btn');
            if (rb) {
                const idx = parseInt(rb.dataset.idx);
                cart.splice(idx, 1);
                renderCart();
                notify('Produk dihapus dari keranjang', 'info');
            }
        });

        // ── TAMBAH PRODUK KE KERANJANG ───────────────────────
        function addToCart(card) {
            const stock = parseInt(card.dataset.stock);
            let sizes = [];
            try { sizes = JSON.parse(card.dataset.sizes || '[]'); } catch(e) {}

            if (stock <= 0 && sizes.length === 0) { notify('Stok produk habis', 'error'); return; }

            const id    = card.dataset.id;
            const name  = card.dataset.name;
            const price = parseFloat(card.dataset.price);

            if (sizes.length > 0) {
                showSizeModal(id, name, price, sizes);
                return;
            }

            const ex = cart.find(i => i.id === id && !i.size);
            if (ex) {
                if (ex.quantity < stock) { ex.quantity++; renderCart(); }
                else { notify('Stok tidak mencukupi', 'error'); return; }
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
                renderCart();
            }
            notify(`"${name}" ditambahkan ke keranjang`, 'success');
        }

        document.querySelectorAll('.product-card').forEach(c => c.addEventListener('click', () => addToCart(c)));

        // ── SIZE MODAL ────────────────────────────────────────
        function showSizeModal(id, name, price, sizes) {
            document.getElementById('sizeModal')?.remove();
            const m = document.createElement('div');
            m.id = 'sizeModal';
            m.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4';
            m.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xs p-5">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-1">Pilih Ukuran</h3>
                    <p class="text-xs text-gray-400 mb-4">${name}</p>
                    <div class="grid grid-cols-3 gap-2">
                        ${sizes.map(s => `
                            <button class="size-opt py-2.5 px-1 rounded-xl border-2 text-xs font-bold transition-all
                                ${s.stock <= 0 ? 'border-gray-100 text-gray-300 cursor-not-allowed' : 'border-gray-200 hover:border-emerald-500 hover:text-emerald-600'}"
                                data-size="${s.name}" data-stock="${s.stock}" ${s.stock <= 0 ? 'disabled' : ''}>
                                ${s.name}<br><span class="font-normal text-[10px]">Stok: ${s.stock}</span>
                            </button>`).join('')}
                    </div>
                    <button onclick="document.getElementById('sizeModal').remove()" class="w-full mt-4 py-2 text-sm text-gray-400 hover:text-gray-600">Batal</button>
                </div>`;

            m.querySelectorAll('.size-opt:not([disabled])').forEach(btn => {
                btn.addEventListener('click', function() {
                    const szName  = this.dataset.size;
                    const szStock = parseInt(this.dataset.stock);
                    const ex = cart.find(i => i.id === id && i.size === szName);
                    if (ex) {
                        if (ex.quantity < szStock) { ex.quantity++; renderCart(); }
                        else notify('Stok tidak mencukupi', 'error');
                    } else {
                        cart.push({ id, name, price, size: szName, quantity: 1, stock: szStock });
                        renderCart();
                    }
                    notify(`"${name} (${szName})" ditambahkan`, 'success');
                    m.remove();
                });
            });
            document.body.appendChild(m);
            m.addEventListener('click', e => { if (e.target === m) m.remove(); });
        }

        // ── KATEGORI ──────────────────────────────────────────
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const cat = this.dataset.category;
                document.querySelectorAll('.product-card').forEach(c => {
                    c.style.display = (cat === 'all' || c.dataset.category === cat) ? '' : 'none';
                });
            });
        });

        // ── SEARCH ────────────────────────────────────────────
        document.getElementById('productSearch').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(c => {
                c.style.display = (c.dataset.name.toLowerCase().includes(q) || (c.dataset.barcode||'').toLowerCase().includes(q)) ? '' : 'none';
            });
        });

        // ── VALIDASI DISKON ───────────────────────────────────
        document.getElementById('applyDiscountBtn').addEventListener('click', async function() {
            const code     = document.getElementById('discountCode').value.trim().toUpperCase();
            const msgEl    = document.getElementById('discountMsg');

            if (!code) { showDiscountMsg('Masukkan kode diskon terlebih dahulu', 'error'); return; }
            if (cart.length === 0) { showDiscountMsg('Tambahkan produk ke keranjang terlebih dahulu', 'error'); return; }

            const subtotal = parseRp(subtotalEl.textContent);

            const origText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled  = true;

            try {
                const res = await fetch('/api/v1/discounts/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrf(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ code: code, amount: subtotal })
                });

                // Cek apakah response adalah JSON
                const contentType = res.headers.get('Content-Type') || '';
                if (!contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Non-JSON response:', text);
                    throw new Error(`Server error ${res.status} — bukan JSON`);
                }

                const data = await res.json();
                if (data.valid) {
                    activeDiscount = {
                        id:    data.discount.id,
                        type:  data.discount.type,
                        value: data.discount.value,
                        code:  code
                    };
                    const label = data.discount.type === 'percentage'
                        ? data.discount.value + '% off'
                        : fmtRp(data.discount.value) + ' off';
                    showDiscountMsg(`✓ Kode "${code}" aktif — ${label}`, 'success');
                    renderCart();
                } else {
                    activeDiscount = null;
                    showDiscountMsg(data.message || 'Kode diskon tidak valid', 'error');
                    renderCart();
                }
            } catch(e) {
                console.error('Discount error:', e);
                showDiscountMsg('Gagal memvalidasi kode: ' + e.message, 'error');
            } finally {
                this.innerHTML = origText;
                this.disabled  = false;
            }
        });

        function showDiscountMsg(text, type) {
            const el = document.getElementById('discountMsg');
            el.textContent = text;
            el.className = `text-xs mt-1.5 font-medium ${type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500'}`;
            el.classList.remove('hidden');
        }

        // ── METODE PEMBAYARAN ─────────────────────────────────
        paymentMethodEl.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const isCash = opt?.dataset.cash === '1';
            cashSection.style.display = isCash ? '' : 'none';
            changeRow.style.display   = isCash ? '' : 'none';
            if (!isCash) {
                paymentAmountEl.value = parseRp(totalEl.textContent);
            } else {
                paymentAmountEl.value = '';
                changeAmountEl.textContent = fmtRp(0);
            }
            updateCheckoutBtn();
        });

        // ── JUMLAH BAYAR & KEMBALIAN ──────────────────────────
        paymentAmountEl.addEventListener('input', calcChange);
        function calcChange() {
            const total   = parseRp(totalEl.textContent);
            const payment = parseFloat(paymentAmountEl.value) || 0;
            const change  = payment - total;
            changeAmountEl.textContent = change >= 0 ? fmtRp(change) : fmtRp(0);
            changeAmountEl.style.color = change < 0 ? '#ef4444' : '';
            updateCheckoutBtn();
        }

        // ── QUICK CASH ────────────────────────────────────────
        document.querySelectorAll('.quick-cash-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                paymentAmountEl.value = this.dataset.exact
                    ? parseRp(totalEl.textContent)
                    : parseInt(this.dataset.amount);
                calcChange();
            });
        });

        // ── CHECKOUT STATE ────────────────────────────────────
        function updateCheckoutBtn() {
            const opt     = paymentMethodEl.options[paymentMethodEl.selectedIndex];
            const isCash  = opt?.dataset.cash === '1';
            const total   = parseRp(totalEl.textContent);
            const payment = parseFloat(paymentAmountEl.value) || 0;
            const payOk   = !isCash || payment >= total;
            checkoutBtn.disabled = !(cart.length > 0 && paymentMethodEl.value && payOk);
        }

        // ── BATAL / RESET ─────────────────────────────────────
        document.getElementById('cancelBtn').addEventListener('click', function() {
            if (cart.length === 0) return;
            if (!confirm('Kosongkan semua item di keranjang?')) return;
            cart = []; activeDiscount = null;
            document.getElementById('discountCode').value = '';
            document.getElementById('discountMsg').classList.add('hidden');
            paymentMethodEl.value = ''; paymentAmountEl.value = '';
            changeAmountEl.textContent = fmtRp(0);
            renderCart();
            notify('Keranjang dikosongkan', 'info');
        });

        // ── CHECKOUT / BAYAR ──────────────────────────────────
        checkoutBtn.addEventListener('click', async function() {
            const opt    = paymentMethodEl.options[paymentMethodEl.selectedIndex];
            const isCash = opt?.dataset.cash === '1';
            const total  = parseRp(totalEl.textContent);
            const payment = isCash ? (parseFloat(paymentAmountEl.value) || 0) : total;

            if (cart.length === 0) { notify('Keranjang masih kosong', 'error'); return; }
            if (!paymentMethodEl.value) { notify('Pilih metode pembayaran dahulu', 'error'); return; }
            if (isCash && payment < total) { notify('Jumlah bayar kurang dari total', 'error'); return; }
            if (!confirm(`Proses pembayaran ${fmtRp(total)}?`)) return;

            const origHTML = this.innerHTML;
            this.disabled  = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const body = {
                    items: cart.map(i => ({ id: i.id, name: i.name, price: i.price, quantity: i.quantity, size: i.size || null })),
                    payment_method_id: paymentMethodEl.value,
                    payment_amount:    payment,
                    change_amount:     Math.max(0, payment - total),
                    customer_id:       document.getElementById('customerSelect').value || null,
                    discount_id:       activeDiscount?.id || null,
                };

                const res = await fetch('/cashier', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':getCsrf(), 'X-Requested-With':'XMLHttpRequest' },
                    body: JSON.stringify(body),
                    credentials: 'same-origin'
                });

                const contentType = res.headers.get('Content-Type') || '';
                if (!contentType.includes('application/json')) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();
                if (!data.success) throw new Error(data.message || 'Transaksi gagal');

                notify('Pembayaran berhasil! 🎉', 'success');

                // Reset
                cart = []; activeDiscount = null;
                document.getElementById('discountCode').value = '';
                document.getElementById('discountMsg').classList.add('hidden');
                paymentMethodEl.value = ''; paymentAmountEl.value = '';
                changeAmountEl.textContent = fmtRp(0);
                renderCart();

                if (data.transaction?.id) {
                    showReceiptModal(data.transaction.id);
                } else {
                    setTimeout(() => location.reload(), 2500);
                }
            } catch(err) {
                notify('Error: ' + err.message, 'error');
            } finally {
                this.innerHTML = origHTML;
                updateCheckoutBtn();
            }
        });

        // ── MODAL STRUK ───────────────────────────────────────
        function showReceiptModal(txId) {
            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptBody').innerHTML = `<div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-emerald-500 text-2xl"></i>
                <p class="text-sm text-gray-400 mt-2">Memuat struk...</p></div>`;

            fetch(`/cashier/receipt/${txId}`, { headers: { 'X-Requested-With':'XMLHttpRequest','Accept':'text/html' } })
                .then(r => r.text())
                .then(html => { document.getElementById('receiptBody').innerHTML = html; })
                .catch(() => { document.getElementById('receiptBody').innerHTML = '<p class="text-center text-red-500 p-8">Gagal memuat struk</p>'; });

            document.getElementById('printReceiptBtn').onclick = () => {
                const win = window.open(`/cashier/print/${txId}`, '_blank', 'width=420,height=620');
                if (win) { win.onload = () => { win.focus(); win.print(); }; }
                else notify('Popup diblokir. Izinkan popup untuk mencetak.', 'warning');
            };
        }

        window.closeReceiptModal = function() {
            document.getElementById('receiptModal').classList.add('hidden');
            setTimeout(() => location.reload(), 200);
        };

        // ── NOTIFY ────────────────────────────────────────────
        function notify(msg, type = 'success') {
            document.querySelectorAll('.notification').forEach(n => n.remove());
            const icons = { success:'fa-check-circle', error:'fa-times-circle', info:'fa-info-circle', warning:'fa-exclamation-circle' };
            const n = document.createElement('div');
            n.className = `notification ${type}`;
            n.innerHTML = `<i class="fas ${icons[type]||'fa-info-circle'}"></i> ${msg}`;
            document.body.appendChild(n);
            setTimeout(() => {
                n.style.opacity = '0'; n.style.transform = 'translateX(110%)'; n.style.transition = 'all 0.3s';
                setTimeout(() => n.remove(), 300);
            }, 3500);
        }

        // ── SCANNER ───────────────────────────────────────────
        const scannerModal = document.getElementById('scannerModal');
        const scannerEl    = document.getElementById('scanner');

        document.getElementById('startScannerBtn').addEventListener('click', async function() {
            scannerModal.classList.remove('hidden');
            scannerEl.innerHTML = `<div class="flex items-center justify-center h-full">
                <div class="text-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500 mx-auto mb-2"></div>
                <p class="text-sm text-gray-500">Mengakses kamera...</p></div></div>`;
            setTimeout(startScanner, 400);
        });
        document.getElementById('stopScannerBtn').addEventListener('click', stopScanner);
        scannerModal.addEventListener('click', e => { if (e.target === scannerModal) stopScanner(); });

        async function startScanner() {
            if (!navigator.mediaDevices?.getUserMedia) {
                notify('Browser tidak mendukung akses kamera', 'error');
                scannerModal.classList.add('hidden'); return;
            }
            scannerEl.innerHTML = '';
            try {
                const video = document.createElement('video');
                video.setAttribute('autoplay',''); video.setAttribute('playsinline','');
                video.className = 'w-full h-full object-cover';
                const offscreen = document.createElement('canvas');
                offscreen.width = 320; offscreen.height = 240;
                const octx = offscreen.getContext('2d', { willReadFrequently: true });
                const overlay = document.createElement('canvas');
                overlay.className = 'absolute top-0 left-0 w-full h-full pointer-events-none';
                const wrap = document.createElement('div');
                wrap.className = 'relative w-full h-full';
                wrap.append(video, overlay); scannerEl.append(wrap);
                scannerEl.classList.add('relative');

                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode:'environment', width:{ideal:640}, height:{ideal:480}, frameRate:{max:15} }
                });
                video.srcObject = stream;
                await new Promise(res => { video.onloadedmetadata = () => { video.play(); res(); }; });

                const ola = overlay.getContext('2d');
                let lastScan = 0;
                scannerActive = { stream };

                function loop(ts) {
                    if (!scannerActive) return;
                    overlay.width = video.videoWidth; overlay.height = video.videoHeight;
                    octx.drawImage(video, 0, 0, 320, 240);
                    ola.drawImage(video, 0, 0, overlay.width, overlay.height);
                    ola.strokeStyle = '#10b981'; ola.lineWidth = 3;
                    const bx = overlay.width*0.15, by = overlay.height*0.25, bw = overlay.width*0.7, bh = overlay.height*0.5;
                    ola.strokeRect(bx, by, bw, bh);
                    if (ts - lastScan > 800) {
                        lastScan = ts;
                        const imgData = octx.getImageData(0,0,320,240);
                        const tmpC = document.createElement('canvas'); tmpC.width=320; tmpC.height=240;
                        tmpC.getContext('2d').putImageData(imgData, 0, 0);
                        Quagga.decodeSingle({ src:tmpC.toDataURL('image/jpeg',0.5), numOfWorkers:1, inputStream:{size:640}, decoder:{readers:['code_128_reader','ean_reader','upc_reader']}, locate:true }, result => {
                            if (result?.codeResult && scannerActive) {
                                const barcode = result.codeResult.code;
                                if (navigator.vibrate) navigator.vibrate(100);
                                const found = [...document.querySelectorAll('.product-card')].find(c => c.dataset.barcode === barcode);
                                if (found) { addToCart(found); stopScanner(); notify(`Barcode ${barcode} ditemukan`, 'success'); }
                                else notify(`Barcode ${barcode} tidak ditemukan`, 'error');
                            }
                        });
                    }
                    scannerActive.frame = requestAnimationFrame(loop);
                }
                scannerActive.frame = requestAnimationFrame(loop);
            } catch(err) {
                notify('Gagal mengakses kamera: ' + err.message, 'error');
                scannerModal.classList.add('hidden');
            }
        }

        function stopScanner() {
            if (scannerActive) {
                if (scannerActive.frame)  cancelAnimationFrame(scannerActive.frame);
                if (scannerActive.stream) scannerActive.stream.getTracks().forEach(t => t.stop());
                scannerActive = false;
            }
            try { Quagga.stop(); } catch(e) {}
            scannerModal.classList.add('hidden');
        }

        // ── INIT ──────────────────────────────────────────────
        renderCart();
    });
    </script>
@endsection
