@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Metode Pembayaran</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola opsi pembayaran yang tersedia di kasir</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Tambah Metode
        </button>
    </div>

    {{-- Payment Method Cards --}}
    @php
        $pmIcons = [
            'Tunai' => ['icon' => 'fa-money-bill-wave', 'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.12)'],
            'Transfer Bank' => ['icon' => 'fa-university', 'color' => '#6366f1', 'bg' => 'rgba(99,102,241,0.12)'],
            'Kartu Kredit' => ['icon' => 'fa-credit-card', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.12)'],
            'QRIS' => ['icon' => 'fa-qrcode', 'color' => '#0d9373', 'bg' => 'rgba(13,147,115,0.12)'],
            'E-Wallet' => ['icon' => 'fa-wallet', 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.12)'],
        ];
    @endphp
    @if($paymentMethods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
            @foreach($paymentMethods as $pm)
                @php $style = $pmIcons[$pm->name] ?? ['icon' => 'fa-credit-card', 'color' => '#0d9373', 'bg' => 'rgba(13,147,115,0.12)']; @endphp
                <div id="pm-card-{{ $pm->id }}"
                    class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-5 group hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: {{ $style['bg'] }};">
                            <i class="fas {{ $style['icon'] }} text-lg" style="color: {{ $style['color'] }};"></i>
                        </div>
                        <div class="flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                onclick="openEditModal({{ $pm->id }}, {{ json_encode(['name' => $pm->name, 'description' => $pm->description]) }})"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button onclick="deletePM({{ $pm->id }}, '{{ addslashes($pm->name) }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $pm->name }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $pm->description ?? 'Metode pembayaran aktif' }}</p>
                    <div class="mt-3 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" style="background: #10b981;"></span>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Aktif</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i> Semua Metode Pembayaran
            </h3>
            <span class="badge badge-blue">{{ $paymentMethods->count() }} metode</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="pmTable">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Metode</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Deskripsi</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($paymentMethods as $pm)
                        @php $style = $pmIcons[$pm->name] ?? ['icon' => 'fa-credit-card', 'color' => '#0d9373', 'bg' => 'rgba(13,147,115,0.12)']; @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors" id="pm-row-{{ $pm->id }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                        style="background: {{ $style['bg'] }};">
                                        <i class="fas {{ $style['icon'] }} text-sm" style="color: {{ $style['color'] }};"></i>
                                    </div>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $pm->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-xs">{{ $pm->description ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge badge-green"><span
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Aktif</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        onclick="openEditModal({{ $pm->id }}, {{ json_encode(['name' => $pm->name, 'description' => $pm->description]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="deletePM({{ $pm->id }}, '{{ addslashes($pm->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-credit-card text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada metode pembayaran</p>
                                <button onclick="openCreateModal()" class="btn-primary mt-3 inline-flex"><i
                                        class="fas fa-plus"></i> Tambah Sekarang</button>
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
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0"
            id="createModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(16,185,129,0.12);">
                        <i class="fas fa-credit-card text-emerald-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Tambah Metode Pembayaran</h2>
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
                        Metode *</label>
                    <div class="relative"><i
                            class="fas fa-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="cName" required placeholder="Contoh: Tunai, QRIS, Transfer Bank"
                            class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="cDesc" rows="2" placeholder="Deskripsi singkat (opsional)..."
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
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0"
            id="editModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Metode Pembayaran</h2>
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
                        Metode *</label>
                    <div class="relative"><i
                            class="fas fa-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="eName" required class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="eDesc" rows="2" class="form-input resize-none"></textarea>
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

        function openCreateModal() { document.getElementById('cName').value = ''; document.getElementById('cDesc').value = ''; document.getElementById('createErrors').classList.add('hidden'); showModal(document.getElementById('createModal'), document.getElementById('createModalBox')); setTimeout(() => document.getElementById('cName').focus(), 250); }
        function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
        async function submitCreate(e) { e.preventDefault(); const btn = document.getElementById('cSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall('{{ route("payment.store") }}', 'POST', { name: document.getElementById('cName').value, description: document.getElementById('cDesc').value || null }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeCreateModal(); showToast('Metode pembayaran berhasil ditambahkan!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan'; }

        function openEditModal(id, d) { document.getElementById('eId').value = id; document.getElementById('eName').value = d.name || ''; document.getElementById('eDesc').value = d.description || ''; document.getElementById('editErrors').classList.add('hidden'); showModal(document.getElementById('editModal'), document.getElementById('editModalBox')); setTimeout(() => document.getElementById('eName').focus(), 250); }
        function closeEditModal() { hideModal(document.getElementById('editModal'), document.getElementById('editModalBox')); }
        async function submitEdit(e) { e.preventDefault(); const id = document.getElementById('eId').value; const btn = document.getElementById('eSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('editErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall(`/payment/${id}`, 'PUT', { name: document.getElementById('eName').value, description: document.getElementById('eDesc').value || null }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeEditModal(); showToast('Metode pembayaran berhasil diperbarui!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Perbarui'; }

        async function deletePM(id, name) { const r = await Swal.fire({ title: `Hapus "${name}"?`, text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' }); if (!r.isConfirmed) return; const { ok, data } = await apiCall(`/payment/${id}`, 'DELETE'); if (ok) { ['pm-card-' + id, 'pm-row-' + id].forEach(rid => { const el = document.getElementById(rid); if (el) { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; setTimeout(() => el.remove(), 300); } }); showToast('Metode pembayaran berhasil dihapus!'); } else { showToast(data.message || 'Gagal menghapus', 'error'); } }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); } });
    </script>
@endpush