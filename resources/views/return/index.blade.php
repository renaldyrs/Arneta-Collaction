@extends('layouts.app')
@section('content')

    {{-- ─── Header ─── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Retur Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola pengajuan retur dan pengembalian barang
                pelanggan</p>
        </div>
        <button onclick="openReturnModal()" class="btn-primary">
            <i class="fas fa-rotate-left"></i> Buat Retur Baru
        </button>
    </div>

    {{-- ─── Stats ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @php
            $pending = $returns->where('status', 'pending')->count();
            $approved = $returns->where('status', 'approved')->count();
            $rejected = $returns->where('status', 'rejected')->count();
        @endphp
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Review</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pending }}</p>
            <p class="text-xs text-amber-500 font-medium mt-1">pengajuan pending</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disetujui</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $approved }}</p>
            <p class="text-xs text-emerald-500 font-medium mt-1">retur diproses</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditolak</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                    <i class="fas fa-times-circle text-red-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rejected }}</p>
            <p class="text-xs text-red-500 font-medium mt-1">retur ditolak</p>
        </div>
    </div>

    {{-- ─── Tabel Retur ─── --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-rotate-left text-emerald-500"></i> Daftar Retur
                <span class="badge badge-blue">{{ $returns->total() }}</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No. Retur</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Transaksi</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Produk</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Qty</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Refund</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Alasan</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $return->return_number }}</code>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $return->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono text-gray-600 dark:text-gray-400">{{ $return->transaction->invoice_number }}</code>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background: rgba(16,185,129,0.1);">
                                        <i class="fas fa-box text-emerald-500 text-xs"></i>
                                    </div>
                                    <span
                                        class="font-medium text-gray-800 dark:text-white text-xs">{{ $return->product->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge badge-blue">{{ $return->quantity }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-xs font-bold text-gray-800 dark:text-white">
                                    Rp {{ number_format($return->total_refund ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400 max-w-[180px]">
                                <p class="truncate" title="{{ $return->reason }}">{{ $return->reason }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($return->status == 'approved')
                                    <span class="badge badge-green"><i class="fas fa-circle text-[6px]"></i> Disetujui</span>
                                @elseif($return->status == 'rejected')
                                    <span class="badge badge-red"><i class="fas fa-circle text-[6px]"></i> Ditolak</span>
                                @else
                                    <span class="badge badge-yellow"><i class="fas fa-circle text-[6px] animate-pulse"></i>
                                        Pending</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if($return->status == 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 transition-colors"
                                                onclick="return confirm('Setujui retur ini? Stok produk akan dikembalikan.')">
                                                <i class="fas fa-check"></i> Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 transition-colors"
                                                onclick="return confirm('Tolak retur ini?')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <p class="text-center text-xs text-gray-400">—</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center"
                                        style="background: rgba(16,185,129,0.08);">
                                        <i class="fas fa-rotate-left text-3xl text-emerald-400 opacity-50"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada pengajuan retur
                                    </p>
                                    <button onclick="openReturnModal()" class="text-emerald-500 text-xs hover:underline">
                                        Buat retur pertama →
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $returns->links('vendor.tailwind') }}
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════
    MODAL FORM RETUR
    ═══════════════════════════════════════════ --}}
    <div id="returnModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog"
        aria-modal="true" aria-labelledby="returnModalTitle">

        {{-- Backdrop --}}
        <div id="returnModalBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReturnModal()">
        </div>

        {{-- Panel --}}
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden
                        transform transition-all duration-300 scale-95 opacity-0" id="returnModalPanel">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/60"
                style="background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(99,102,241,0.05));">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(16,185,129,0.15);">
                        <i class="fas fa-rotate-left text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 id="returnModalTitle" class="text-sm font-bold text-gray-800 dark:text-white">Buat Pengajuan
                            Retur</h3>
                        <p class="text-xs text-gray-400" id="returnModalSubtitle">Isi data berikut untuk mengajukan retur
                        </p>
                    </div>
                </div>
                <button onclick="closeReturnModal()"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                {{-- Error --}}
                @if(session('error'))
                    <div
                        class="mb-4 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form id="returnForm" action="{{ route('returns.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="transaction_id" id="modal_transaction_id">

                    {{-- Step 1: Cari No. Invoice --}}
                    <div id="step1Section">
                        <label
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            <i class="fas fa-search text-emerald-500 mr-1"></i> Cari No. Invoice Transaksi
                        </label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" id="invoiceSearch" placeholder="Contoh: INV-20260222-0001"
                                    class="form-input w-full pl-9 text-sm font-mono" autocomplete="off">
                                <i
                                    class="fas fa-hashtag absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            </div>
                            <button type="button" onclick="searchInvoice()" class="btn-primary px-4 whitespace-nowrap">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div id="invoiceError" class="hidden mt-2 text-xs text-red-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i>
                            <span></span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-dashed border-gray-200 dark:border-gray-700"></div>

                    {{-- Step 2: Detail Form (muncul setelah invoice ditemukan) --}}
                    <div id="returnFormFields" class="space-y-4 hidden">
                        {{-- Info Transaksi --}}
                        <div id="transactionInfo" class="px-4 py-3 rounded-xl text-xs"
                            style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2);">
                        </div>

                        {{-- Pilih Produk --}}
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-box text-emerald-500 mr-1"></i> Produk yang Diretur
                            </label>
                            <select name="product_id" id="modal_product_id" class="form-select w-full text-sm" required
                                onchange="updateMaxQty()">
                                <option value="">— Pilih Produk —</option>
                            </select>
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-cubes text-emerald-500 mr-1"></i>
                                Jumlah Retur
                                <span id="maxQtyLabel" class="normal-case text-gray-400 font-normal ml-1"></span>
                            </label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="adjustQty(-1)"
                                    class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-bold">−</button>
                                <input type="number" name="quantity" id="modal_qty"
                                    class="form-input text-center font-bold text-lg w-20" value="1" min="1" required>
                                <button type="button" onclick="adjustQty(1)"
                                    class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-bold">+</button>
                                <div id="refundPreview" class="flex-1 text-right">
                                    <p class="text-xs text-gray-400">Estimasi Refund</p>
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400 text-sm" id="refundAmount">Rp
                                        0</p>
                                </div>
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-comment text-emerald-500 mr-1"></i> Alasan Retur
                            </label>
                            {{-- Quick reason buttons --}}
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach(['Produk rusak/cacat', 'Salah ukuran', 'Tidak sesuai pesanan', 'Produk tidak berfungsi'] as $reason)
                                    <button type="button" onclick="setReason('{{ $reason }}')"
                                        class="text-[11px] px-2.5 py-1 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:border-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                        {{ $reason }}
                                    </button>
                                @endforeach
                            </div>
                            <textarea name="reason" id="modal_reason" rows="2" class="form-input w-full text-sm resize-none"
                                placeholder="Tuliskan alasan retur secara detail..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div
                class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-700/20">
                <button type="button" onclick="closeReturnModal()" class="btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" id="submitReturnBtn" onclick="document.getElementById('returnForm').submit()"
                    class="btn-primary hidden">
                    <i class="fas fa-paper-plane"></i> Ajukan Retur
                </button>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            #returnModal.active {
                display: flex;
            }

            #returnModal.active #returnModalPanel {
                opacity: 1;
                transform: scale(1);
            }
        </style>
    @endpush

    <script>
        // Data semua transaksi untuk pencarian (di-load via AJAX)
        let currentTransactionData = null;

        // ─── Buka / Tutup Modal ──────────────────────────────────────
        function openReturnModal(transactionId = null) {
            const modal = document.getElementById('returnModal');
            modal.classList.remove('hidden');
            // Trigger animasi
            requestAnimationFrame(() => {
                requestAnimationFrame(() => modal.classList.add('active'));
            });
            document.body.style.overflow = 'hidden';

            // Jika ada transactionId langsung (dari Riwayat Transaksi)
            if (transactionId) {
                document.getElementById('invoiceSearch').value = '';
                fetchTransactionById(transactionId);
            } else {
                resetForm();
            }
        }

        function closeReturnModal() {
            const modal = document.getElementById('returnModal');
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
                resetForm();
            }, 300);
            document.body.style.overflow = '';
        }

        function resetForm() {
            document.getElementById('invoiceSearch').value = '';
            document.getElementById('modal_transaction_id').value = '';
            document.getElementById('modal_product_id').innerHTML = '<option value="">— Pilih Produk —</option>';
            document.getElementById('modal_qty').value = 1;
            document.getElementById('modal_reason').value = '';
            document.getElementById('returnFormFields').classList.add('hidden');
            document.getElementById('submitReturnBtn').classList.add('hidden');
            document.getElementById('refundAmount').textContent = 'Rp 0';
            document.getElementById('maxQtyLabel').textContent = '';
            document.getElementById('invoiceError').classList.add('hidden');
            currentTransactionData = null;
        }

        // ─── Cari Invoice ───────────────────────────────────────────
        function searchInvoice() {
            const keyword = document.getElementById('invoiceSearch').value.trim();
            if (!keyword) {
                showInvoiceError('Masukkan nomor invoice terlebih dahulu.');
                return;
            }

            const searchBtn = document.querySelector('#step1Section button');
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            searchBtn.disabled = true;

            fetch(`/api/v1/transactions/search?q=${encodeURIComponent(keyword)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        loadTransactionData(data.transaction);
                    } else {
                        showInvoiceError(data.message || 'Invoice tidak ditemukan.');
                    }
                })
                .catch(() => showInvoiceError('Gagal menghubungi server. Coba lagi.'))
                .finally(() => {
                    searchBtn.innerHTML = '<i class="fas fa-search"></i> Cari';
                    searchBtn.disabled = false;
                });
        }

        function fetchTransactionById(id) {
            fetch(`/api/v1/transactions/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) loadTransactionData(data.transaction);
                })
                .catch(() => showInvoiceError('Gagal memuat data transaksi.'));
        }

        function loadTransactionData(tx) {
            currentTransactionData = tx;
            document.getElementById('modal_transaction_id').value = tx.id;

            // Info transaksi
            const date = new Date(tx.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            document.getElementById('transactionInfo').innerHTML = `
                    <div class="flex items-center gap-2 mb-1.5">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400">${tx.invoice_number}</span>
                        <span class="text-gray-400">·</span>
                        <span class="text-gray-500">${date}</span>
                    </div>
                    <div class="flex gap-4 text-gray-600 dark:text-gray-400">
                        <span><i class="fas fa-receipt text-[10px] mr-1"></i>${tx.details.length} produk</span>
                        <span><i class="fas fa-coins text-[10px] mr-1"></i>Rp ${parseInt(tx.total_amount).toLocaleString('id-ID')}</span>
                        ${tx.customer ? `<span><i class="fas fa-user text-[10px] mr-1"></i>${tx.customer.name}</span>` : ''}
                    </div>`;

            // Isi dropdown produk
            const select = document.getElementById('modal_product_id');
            select.innerHTML = '<option value="">— Pilih Produk —</option>';
            tx.details.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.product_id;
                opt.dataset.qty = d.quantity;
                opt.dataset.price = d.price;
                opt.textContent = `${d.product?.name || 'Produk'} — beli: ${d.quantity} × Rp ${parseInt(d.price).toLocaleString('id-ID')}`;
                select.appendChild(opt);
            });

            document.getElementById('returnFormFields').classList.remove('hidden');
            document.getElementById('submitReturnBtn').classList.remove('hidden');
            document.getElementById('invoiceError').classList.add('hidden');

            // Update subtitle
            document.getElementById('returnModalSubtitle').textContent = 'Invoice ' + tx.invoice_number + ' ditemukan';

            updateMaxQty();
        }

        // ─── Update maks qty & preview refund ──────────────────────
        function updateMaxQty() {
            const select = document.getElementById('modal_product_id');
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.dataset.qty) return;

            const maxQty = parseInt(opt.dataset.qty);
            const price = parseFloat(opt.dataset.price);
            const qtyInput = document.getElementById('modal_qty');

            qtyInput.max = maxQty;
            if (parseInt(qtyInput.value) > maxQty) qtyInput.value = maxQty;

            document.getElementById('maxQtyLabel').textContent = `(maks. ${maxQty})`;
            updateRefundPreview(price);
        }

        function updateRefundPreview(price) {
            const qty = parseInt(document.getElementById('modal_qty').value) || 0;
            if (!price) {
                const opt = document.getElementById('modal_product_id').options[document.getElementById('modal_product_id').selectedIndex];
                price = opt ? parseFloat(opt.dataset.price) : 0;
            }
            document.getElementById('refundAmount').textContent = 'Rp ' + (price * qty).toLocaleString('id-ID');
        }

        function adjustQty(delta) {
            const input = document.getElementById('modal_qty');
            const newVal = Math.max(1, Math.min(parseInt(input.max) || 999, parseInt(input.value) + delta));
            input.value = newVal;
            updateRefundPreview();
        }

        document.getElementById('modal_qty').addEventListener('input', () => updateRefundPreview());

        // ─── Quick reason ───────────────────────────────────────────
        function setReason(text) {
            document.getElementById('modal_reason').value = text;
        }

        // ─── Invoice error ──────────────────────────────────────────
        function showInvoiceError(msg) {
            const el = document.getElementById('invoiceError');
            el.querySelector('span').textContent = msg;
            el.classList.remove('hidden');
        }

        // ─── Enter to search ────────────────────────────────────────
        document.getElementById('invoiceSearch').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); searchInvoice(); }
        });

        // ─── Auto buka modal jika ada flash transaction_id ─────────
        @if(session('open_return_for'))
            document.addEventListener('DOMContentLoaded', () => {
                openReturnModal({{ session('open_return_for') }});
            });
        @endif
    </script>
@endsection