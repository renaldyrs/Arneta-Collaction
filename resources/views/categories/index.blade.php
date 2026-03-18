@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kategori</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola kategori produk secara efisien</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Tambah Kategori
        </button>
    </div>

    {{-- Category List --}}
    <div id="categoryContainer" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-10 gap-3">
        @forelse ($categories as $item)
            <div class="group relative bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/50 hover:shadow-md transition-all hover:-translate-y-1"
                 id="cat-row-{{ $item->id }}">
                
                {{-- Actions (Hover) --}}
                <div class="absolute top-1 right-1 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->code) }}')"
                            class="w-6 h-6 flex items-center justify-center rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-edit text-[9px]"></i>
                    </button>
                    <button onclick="deleteCategory({{ $item->id }})"
                            class="w-6 h-6 flex items-center justify-center rounded-md bg-red-50 dark:bg-red-900/40 text-red-500 hover:bg-red-100 transition-colors">
                        <i class="fas fa-trash text-[9px]"></i>
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-2"
                         style="background: rgba(16,185,129,0.12);">
                        {{ strtoupper(substr($item->name, 0, 1)) }}
                    </div>
                    <h3 class="text-[11px] font-bold text-gray-800 dark:text-white leading-tight truncate w-full px-0.5" title="{{ $item->name }}">
                        {{ $item->name }}
                    </h3>
                    <code class="mt-0.5 px-1.5 py-0 text-[9px] bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 font-mono rounded-sm uppercase tracking-tighter">
                        {{ $item->code }}
                    </code>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-gray-400">
                <i class="fas fa-layer-group text-4xl mb-3 opacity-20"></i>
                <p class="text-sm">Belum ada kategori</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $categories->links('vendor.tailwind') }}
    </div>

    {{-- ═══════════════════════ MODAL CREATE ═══════════════════════ --}}
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog"
        aria-modal="true" aria-labelledby="createModalTitle">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0"
            id="createModalBox">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(16,185,129,0.12);">
                        <i class="fas fa-plus text-emerald-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white" id="createModalTitle">Tambah Kategori</h2>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            {{-- Form --}}
            <form id="createForm" onsubmit="submitCreate(event)" class="p-6 space-y-4">
                @csrf
                <div id="createErrors" class="hidden p-3 rounded-xl text-xs" style="background: #fee2e2; color: #991b1b;">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kode
                        Kategori *</label>
                    <div class="relative">
                        <i class="fas fa-hashtag absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="createCode" name="code" required placeholder="CT-001" class="form-input pl-9"
                            autocomplete="off">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                        Kategori *</label>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="createName" name="name" required placeholder="Contoh: Aksesoris"
                            class="form-input pl-9" autocomplete="off">
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeCreateModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="createSubmitBtn" class="btn-primary flex-1">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════ MODAL EDIT ═══════════════════════ --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog"
        aria-modal="true" aria-labelledby="editModalTitle">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0"
            id="editModalBox">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white" id="editModalTitle">Edit Kategori</h2>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            {{-- Form --}}
            <form id="editForm" onsubmit="submitEdit(event)" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId">
                <div id="editErrors" class="hidden p-3 rounded-xl text-xs" style="background: #fee2e2; color: #991b1b;">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kode
                        Kategori *</label>
                    <div class="relative">
                        <i class="fas fa-hashtag absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="editCode" name="code" required class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                        Kategori *</label>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="editName" name="name" required class="form-input pl-9">
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="editSubmitBtn" class="btn-primary flex-1">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ── Helpers ──
        function showModal(modal, box) {
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                box.classList.remove('scale-95', 'opacity-0');
                box.classList.add('scale-100', 'opacity-100');
            });
            document.body.style.overflow = 'hidden';
        }
        function hideModal(modal, box) {
            box.classList.remove('scale-100', 'opacity-100');
            box.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }
        function showToast(msg, type = 'success') {
            const colors = type === 'success'
                ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0'
                : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);animation:fadeIn .3s ease;${colors}`;
            t.innerHTML = `<i class="${icon}"></i>${msg}`;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
        }

        // ── Create Modal ──
        function openCreateModal() {
            document.getElementById('createCode').value = '';
            document.getElementById('createName').value = '';
            document.getElementById('createErrors').classList.add('hidden');
            showModal(document.getElementById('createModal'), document.getElementById('createModalBox'));
            setTimeout(() => document.getElementById('createCode').focus(), 250);
        }
        function closeCreateModal() {
            hideModal(document.getElementById('createModal'), document.getElementById('createModalBox'));
        }

        async function submitCreate(e) {
            e.preventDefault();
            const btn = document.getElementById('createSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            const errEl = document.getElementById('createErrors');
            errEl.classList.add('hidden');

            try {
                const res = await fetch('{{ route("categories.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        code: document.getElementById('createCode').value,
                        name: document.getElementById('createName').value,
                    })
                });
                const data = await res.json();
                if (!res.ok) {
                    const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan'];
                    errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                    errEl.classList.remove('hidden');
                    return;
                }
                closeCreateModal();
                showToast('Kategori berhasil ditambahkan!');
                setTimeout(() => location.reload(), 600);
            } catch (err) {
                errEl.innerHTML = '<p>• Koneksi gagal, coba lagi.</p>';
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            }
        }

        // ── Edit Modal ──
        function openEditModal(id, name, code) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editCode').value = code;
            document.getElementById('editErrors').classList.add('hidden');
            showModal(document.getElementById('editModal'), document.getElementById('editModalBox'));
            setTimeout(() => document.getElementById('editName').focus(), 250);
        }
        function closeEditModal() {
            hideModal(document.getElementById('editModal'), document.getElementById('editModalBox'));
        }

        async function submitEdit(e) {
            e.preventDefault();
            const id = document.getElementById('editId').value;
            const btn = document.getElementById('editSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            const errEl = document.getElementById('editErrors');
            errEl.classList.add('hidden');

            try {
                const res = await fetch(`/categories/${id}`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        code: document.getElementById('editCode').value,
                        name: document.getElementById('editName').value,
                    })
                });
                const data = await res.json();
                if (!res.ok) {
                    const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan'];
                    errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                    errEl.classList.remove('hidden');
                    return;
                }
                closeEditModal();
                showToast('Kategori berhasil diperbarui!');
                setTimeout(() => location.reload(), 600);
            } catch (err) {
                errEl.innerHTML = '<p>• Koneksi gagal, coba lagi.</p>';
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Perbarui';
            }
        }

        // ── Delete ──
        async function deleteCategory(id) {
            const result = await Swal.fire({
                title: 'Hapus Kategori?',
                text: 'Tindakan ini tidak bisa dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                borderRadius: '1rem',
            });
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/categories/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const row = document.getElementById(`cat-row-${id}`);
                    if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); }
                    showToast('Kategori berhasil dihapus!');
                } else {
                    const data = await res.json();
                    showToast(data.message || 'Gagal menghapus kategori', 'error');
                }
            } catch (err) {
                showToast('Koneksi gagal, coba lagi.', 'error');
            }
        }

        // ESC key to close modals
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
            }
        });
    </script>
@endpush