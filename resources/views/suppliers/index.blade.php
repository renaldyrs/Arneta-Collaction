@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Supplier</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola data pemasok dan mitra bisnis</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Tambah Supplier
        </button>
    </div>

    {{-- Supplier List --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-truck text-emerald-500"></i> Daftar Supplier
            </h3>
            <span class="badge badge-green">{{ count($suppliers) }} supplier</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Supplier</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kontak</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Alamat</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors"
                            id="sup-row-{{ $supplier->id }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                        style="background: linear-gradient(135deg, #0d9373, #6366f1);">
                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                    </div>
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ $supplier->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-gray-700 dark:text-gray-300">{{ $supplier->phone }}</p>
                                <p class="text-xs text-gray-400">{{ $supplier->email ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
                                {{ $supplier->address }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        onclick="openEditModal({{ $supplier->id }}, {{ json_encode(['name' => $supplier->name, 'email' => $supplier->email, 'phone' => $supplier->phone, 'address' => $supplier->address]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="deleteSupplier({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-truck text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada supplier terdaftar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════ MODAL CREATE ══════════════════════ --}}
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-200 scale-95 opacity-0"
            id="createModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(16,185,129,0.12);">
                        <i class="fas fa-truck text-emerald-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Tambah Supplier</h2>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitCreate(event)" class="p-6 space-y-4">
                <div id="createErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                        Supplier *</label>
                    <div class="relative"><i
                            class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="cName" required placeholder="PT. Supplier ABC" class="form-input pl-9">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Telepon
                            *</label>
                        <div class="relative"><i
                                class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="cPhone" required placeholder="08xxxxxxxxxx" class="form-input pl-9">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                        <div class="relative"><i
                                class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="email" id="cEmail" placeholder="email@supplier.com" class="form-input pl-9">
                        </div>
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat
                        *</label>
                    <textarea id="cAddress" required rows="2" placeholder="Alamat lengkap supplier..."
                        class="form-input resize-none"></textarea>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeCreateModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="cSubmitBtn" class="btn-primary flex-1"><i class="fas fa-save"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════ MODAL EDIT ══════════════════════ --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-200 scale-95 opacity-0"
            id="editModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Supplier</h2>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitEdit(event)" class="p-6 space-y-4">
                <input type="hidden" id="eId">
                <div id="editErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;"></div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                        Supplier *</label>
                    <div class="relative"><i
                            class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="eName" required class="form-input pl-9">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Telepon
                            *</label>
                        <div class="relative"><i
                                class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="ePhone" required class="form-input pl-9">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                        <div class="relative"><i
                                class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="email" id="eEmail" class="form-input pl-9">
                        </div>
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat
                        *</label>
                    <textarea id="eAddress" required rows="2" class="form-input resize-none"></textarea>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="eSubmitBtn" class="btn-primary flex-1"><i class="fas fa-save"></i>
                        Perbarui</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        function showModal(m, b) { m.classList.remove('hidden'); requestAnimationFrame(() => { b.classList.remove('scale-95', 'opacity-0'); b.classList.add('scale-100', 'opacity-100'); }); document.body.style.overflow = 'hidden'; }
        function hideModal(m, b) { b.classList.remove('scale-100', 'opacity-100'); b.classList.add('scale-95', 'opacity-0'); setTimeout(() => { m.classList.add('hidden'); document.body.style.overflow = ''; }, 200); }
        function showToast(msg, type = 'success') { const c = type === 'success' ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5'; const t = document.createElement('div'); t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1.1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);${c}`; t.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${msg}`; document.body.appendChild(t); setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000); }
        async function apiCall(url, method, body) { const res = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined }); const data = await res.json(); return { ok: res.ok, data }; }

        function openCreateModal() { ['cName', 'cPhone', 'cEmail', 'cAddress'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; }); document.getElementById('createErrors').classList.add('hidden'); showModal(document.getElementById('createModal'), document.getElementById('createModalBox')); setTimeout(() => document.getElementById('cName').focus(), 250); }
        function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
        async function submitCreate(e) { e.preventDefault(); const btn = document.getElementById('cSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall('{{ route("suppliers.store") }}', 'POST', { name: document.getElementById('cName').value, phone: document.getElementById('cPhone').value, email: document.getElementById('cEmail').value, address: document.getElementById('cAddress').value }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeCreateModal(); showToast('Supplier berhasil ditambahkan!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan'; }

        function openEditModal(id, d) { document.getElementById('eId').value = id; document.getElementById('eName').value = d.name || ''; document.getElementById('ePhone').value = d.phone || ''; document.getElementById('eEmail').value = d.email || ''; document.getElementById('eAddress').value = d.address || ''; document.getElementById('editErrors').classList.add('hidden'); showModal(document.getElementById('editModal'), document.getElementById('editModalBox')); setTimeout(() => document.getElementById('eName').focus(), 250); }
        function closeEditModal() { hideModal(document.getElementById('editModal'), document.getElementById('editModalBox')); }
        async function submitEdit(e) { e.preventDefault(); const id = document.getElementById('eId').value; const btn = document.getElementById('eSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('editErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall(`/suppliers/${id}`, 'PUT', { name: document.getElementById('eName').value, phone: document.getElementById('ePhone').value, email: document.getElementById('eEmail').value, address: document.getElementById('eAddress').value }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeEditModal(); showToast('Supplier berhasil diperbarui!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Perbarui'; }

        async function deleteSupplier(id, name) { const r = await Swal.fire({ title: `Hapus "${name}"?`, text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' }); if (!r.isConfirmed) return; const { ok, data } = await apiCall(`/suppliers/${id}`, 'DELETE'); if (ok) { const row = document.getElementById(`sup-row-${id}`); if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); } showToast('Supplier berhasil dihapus!'); } else { showToast(data.message || 'Gagal menghapus', 'error'); } }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); } });
    </script>
@endpush