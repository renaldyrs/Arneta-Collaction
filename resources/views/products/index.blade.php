@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola inventaris produk toko Anda</p>
        </div>
        <button onclick="openModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Tambah Produk
        </button>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Produk</p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-box text-indigo-500"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua produk inventaris</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Aman</p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inStockProducts }}</p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $totalProducts > 0 ? number_format(($inStockProducts / $totalProducts) * 100, 0) : 0 }}% dari inventaris
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Menipis</p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-triangle-exclamation text-amber-500"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowStockProducts }}</p>
            <p class="text-xs text-amber-500 mt-1 font-medium">Perlu perhatian</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Habis</p>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $outOfStockProducts }}</p>
            <p class="text-xs text-red-500 mt-1 font-medium">Perlu restok</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        {{-- Table Header --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i> Daftar Produk
            </h3>
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                        class="pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20 w-52">
                </div>
                @if(request('search'))
                    <a href="{{ route('products.index') }}"
                        class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Produk</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Barcode</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kategori</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Harga</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Stok</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(16,185,129,0.1);">
                                            <i class="fas fa-box text-emerald-500 text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $product->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($product->barcode)
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, 'C128') }}"
                                        alt="barcode" class="h-8 w-28">
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge badge-blue">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-semibold text-gray-800 dark:text-white">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span
                                    class="font-bold {{ $product->stock <= 0 ? 'text-red-500' : ($product->stock <= 10 ? 'text-amber-500' : 'text-gray-800 dark:text-white') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($product->stock > 10)
                                    <span class="badge badge-green"><i class="fas fa-circle text-[6px]"></i> Aman</span>
                                @elseif($product->stock > 0)
                                    <span class="badge badge-yellow"><i class="fas fa-circle text-[6px]"></i> Menipis</span>
                                @else
                                    <span class="badge badge-red"><i class="fas fa-circle text-[6px]"></i> Habis</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode(['name' => $product->name, 'price' => $product->price, 'stock' => $product->stock, 'category_id' => $product->category_id, 'description' => $product->description, 'image' => $product->image]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <a href="{{ route('products.print-barcodes', $product->id) }}" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Print Barcode">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <button onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
            {{ $products->appends(['search' => request('search')])->links('vendor.tailwind') }}
        </div>
    </div>

    {{-- Add Product Modal --}}
    <div id="addProductModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4 z-50"
        style="display:none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all duration-200 scale-95 opacity-0"
            id="modalContent">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h2>
                <button onclick="closeModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                    id="addProductForm">
                    @csrf
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded-xl text-sm"
                            style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                                Produk *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Harga
                                    *</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-semibold">Rp</span>
                                    <input type="number" name="price" value="{{ old('price') }}" required
                                        class="form-input pl-9">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Stok
                                    *</label>
                                <input type="number" name="stock" value="{{ old('stock') }}" required class="form-input">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kategori
                                *</label>
                            <select name="category_id" required class="form-input">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Supplier
                                *</label>
                            <select name="supplier_id" required class="form-input">
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                            <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Foto
                                Produk</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="h-20 w-20 rounded-xl object-cover border border-gray-200">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Ukuran
                                & Stok (Opsional)</label>
                            <div id="size-container" class="space-y-2">
                                <!-- Ukuran dapat ditambahkan secara dinamis -->
                            </div>
                            <button type="button" id="add-size"
                                class="mt-2 text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                                <i class="fas fa-plus-circle"></i> Tambah Ukuran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <button onclick="closeModal()" class="btn-secondary">Batal</button>
                <button form="addProductForm" type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan
                    Produk</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════ MODAL EDIT PRODUK ══════════════════════ --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all duration-200 scale-95 opacity-0"
            id="editModalBox">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Produk</h2>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <form id="editProductForm" class="space-y-4">
                    <input type="hidden" id="eProdId">
                    <div id="editErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                            Produk *</label>
                        <input type="text" id="eProdName" required class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Harga
                                *</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-semibold">Rp</span>
                                <input type="number" id="eProdPrice" required class="form-input pl-9">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Stok
                                *</label>
                            <input type="number" id="eProdStock" required class="form-input">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kategori
                            *</label>
                        <select id="eProdCategory" required class="form-input">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea id="eProdDesc" rows="2" class="form-input resize-none"></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Ganti
                            Foto Produk</label>
                        <input type="file" id="eProdImage" accept="image/*"
                            class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                            onchange="previewEditImage(this)">
                        <div id="editImagePreview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="h-20 w-20 rounded-xl object-cover border border-gray-200">
                        </div>
                    </div>
                </form>
            </div>
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 sticky bottom-0 bg-white dark:bg-gray-800">
                <button onclick="closeEditModal()" class="btn-secondary">Batal</button>
                <button onclick="submitEdit()" id="eSubmitBtn" class="btn-primary"><i class="fas fa-save"></i> Simpan
                    Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        // ── Create Modal ──
        function openModal() {
            const m = document.getElementById('addProductModal'), c = document.getElementById('modalContent');
            m.style.display = 'flex';
            setTimeout(() => { c.classList.remove('scale-95', 'opacity-0'); c.classList.add('scale-100', 'opacity-100'); }, 10);
        }
        function closeModal() {
            const m = document.getElementById('addProductModal'), c = document.getElementById('modalContent');
            c.classList.remove('scale-100', 'opacity-100'); c.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { m.style.display = 'none'; }, 200);
        }
        document.getElementById('addProductModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });
        document.getElementById('image').addEventListener('change', function () {
            const f = this.files[0], p = document.getElementById('image-preview');
            if (f) { const r = new FileReader(); r.onload = e => { p.querySelector('img').src = e.target.result; p.classList.remove('hidden'); }; r.readAsDataURL(f); }
        });
        let si = 0;
        document.getElementById('add-size').addEventListener('click', () => {
            const c = document.getElementById('size-container'), d = document.createElement('div');
            d.className = 'flex gap-2 items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl';
            d.innerHTML = `<input type="text" name="sizes[${si}][name]" placeholder="Ukuran" class="form-input flex-1"><input type="number" name="sizes[${si}][stock]" placeholder="Stok" class="form-input flex-1"><button type="button" class="remove-size w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 flex-shrink-0"><i class="fas fa-trash text-xs"></i></button>`;
            c.appendChild(d); si++;
        });
        document.getElementById('size-container').addEventListener('click', e => {
            const btn = e.target.closest('.remove-size');
            if (btn) btn.closest('div').remove();
        });
        // Before submitting the form, remove empty size rows and reindex sizes to start from 0.
        document.getElementById('addProductForm').addEventListener('submit', function (ev) {
            const container = document.getElementById('size-container');
            // Remove rows where both name and stock are empty
            Array.from(container.children).forEach(row => {
                const nameInp = row.querySelector('input[type="text"]');
                const stockInp = row.querySelector('input[type="number"]');
                const name = nameInp?.value?.trim();
                const stock = stockInp?.value?.trim();
                if (!name && !stock) row.remove();
            });
            // Reindex remaining rows so names are sizes[0].. sizes[n]
            Array.from(container.children).forEach((row, idx) => {
                const nameInp = row.querySelector('input[type="text"]');
                const stockInp = row.querySelector('input[type="number"]');
                if (nameInp) nameInp.name = `sizes[${idx}][name]`;
                if (stockInp) {
                    // default empty stock to 0 if name provided
                    if (!stockInp.value || stockInp.value.trim() === '') stockInp.value = '0';
                    stockInp.name = `sizes[${idx}][stock]`;
                }
            });
        });
            @if($errors->any()) document.addEventListener('DOMContentLoaded', openModal); @endif

            // ── Edit Modal ──
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        function showToast(msg, type = 'success') { const c = type === 'success' ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5'; const t = document.createElement('div'); t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1.1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);${c}`; t.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${msg}`; document.body.appendChild(t); setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000); }

        function openEditModal(id, d) {
            document.getElementById('eProdId').value = id;
            document.getElementById('eProdName').value = d.name || '';
            document.getElementById('eProdPrice').value = d.price || '';
            document.getElementById('eProdStock').value = d.stock || '';
            document.getElementById('eProdCategory').value = d.category_id || '';
            document.getElementById('eProdDesc').value = d.description || '';
            document.getElementById('editErrors').classList.add('hidden');
            document.getElementById('editImagePreview').classList.add('hidden');
            document.getElementById('eProdImage').value = '';
            const em = document.getElementById('editModal'), eb = document.getElementById('editModalBox');
            em.classList.remove('hidden');
            requestAnimationFrame(() => { eb.classList.remove('scale-95', 'opacity-0'); eb.classList.add('scale-100', 'opacity-100'); });
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('eProdName').focus(), 250);
        }
        function closeEditModal() {
            const em = document.getElementById('editModal'), eb = document.getElementById('editModalBox');
            eb.classList.remove('scale-100', 'opacity-100'); eb.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { em.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }
        function previewEditImage(input) {
            const f = input.files[0], p = document.getElementById('editImagePreview');
            if (f) { const r = new FileReader(); r.onload = e => { p.querySelector('img').src = e.target.result; p.classList.remove('hidden'); }; r.readAsDataURL(f); }
        }
        async function submitEdit() {
            const id = document.getElementById('eProdId').value;
            const btn = document.getElementById('eSubmitBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            const errEl = document.getElementById('editErrors');
            errEl.classList.add('hidden');
            // Use FormData for file upload support
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('name', document.getElementById('eProdName').value);
            fd.append('price', document.getElementById('eProdPrice').value);
            fd.append('stock', document.getElementById('eProdStock').value);
            fd.append('category_id', document.getElementById('eProdCategory').value);
            fd.append('description', document.getElementById('eProdDesc').value || '');
            const imgFile = document.getElementById('eProdImage').files[0];
            if (imgFile) fd.append('image', imgFile);
            const res = await fetch(`/products/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: fd });
            const data = await res.json();
            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                errEl.classList.remove('hidden');
            } else {
                closeEditModal();
                showToast('Produk berhasil diperbarui!');
                setTimeout(() => location.reload(), 600);
            }
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        }
        async function deleteProduct(id, name) {
            const r = await Swal.fire({ title: `Hapus "${name}"?`, text: 'Produk akan dihapus secara permanen.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' });
            if (!r.isConfirmed) return;
            const res = await fetch(`/products/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
            if (res.ok) { showToast('Produk berhasil dihapus!'); setTimeout(() => location.reload(), 600); }
            else { showToast('Gagal menghapus produk', 'error'); }
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditModal(); });
    </script>
@endsection