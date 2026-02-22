@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pelanggan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola data pelanggan dan riwayat transaksi</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Tambah Pelanggan
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pelanggan</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-users text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="stat-total">{{ number_format($totalCustomers) }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Belanja</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-shopping-bag text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Poin</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-star text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPoints) }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i> Daftar Pelanggan
            </h3>
            <form method="GET" action="{{ route('customers.index') }}" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, telepon..."
                        class="pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20 w-52">
                </div>
                @if($search)
                    <a href="{{ route('customers.index') }}"
                        class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pelanggan</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kontak</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Transaksi</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Total Belanja</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Poin</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($customers as $c)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors" id="cust-row-{{ $c->id }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                        style="background: linear-gradient(135deg, #0d9373, #6366f1);">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $c->name }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[160px]">{{ $c->address ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-gray-700 dark:text-gray-300 text-xs">{{ $c->phone ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $c->email ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge badge-blue">{{ $c->transactions_count ?? 0 }}×</span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-gray-800 dark:text-white">
                                Rp {{ number_format($c->total_spent, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge badge-yellow">⭐ {{ number_format($c->points) }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('customers.show', $c) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <button
                                        onclick="openEditModal({{ $c->id }}, {{ json_encode(['name' => $c->name, 'phone' => $c->phone, 'email' => $c->email, 'address' => $c->address]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="deleteCustomer({{ $c->id }}, '{{ addslashes($c->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-users text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada pelanggan terdaftar</p>
                                <button onclick="openCreateModal()" class="btn-primary mt-3 inline-flex"><i
                                        class="fas fa-plus"></i> Tambah Pelanggan</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $customers->links() }}
            </div>
        @endif
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
                        <i class="fas fa-user-plus text-emerald-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Tambah Pelanggan</h2>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitCreate(event)" class="p-6 space-y-4">
                <div id="createErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                            Lengkap *</label>
                        <div class="relative"><i
                                class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="cName" required placeholder="Budi Santoso" class="form-input pl-9">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">No.
                            Telepon</label>
                        <div class="relative"><i
                                class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="cPhone" placeholder="08xx-xxxx-xxxx" class="form-input pl-9">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                        <div class="relative"><i
                                class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="email" id="cEmail" placeholder="email@contoh.com" class="form-input pl-9">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat</label>
                        <textarea id="cAddress" rows="2" placeholder="Alamat lengkap..."
                            class="form-input resize-none"></textarea>
                    </div>
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
                        <i class="fas fa-user-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Pelanggan</h2>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitEdit(event)" class="p-6 space-y-4">
                <input type="hidden" id="eId">
                <div id="editErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                            Lengkap *</label>
                        <div class="relative"><i
                                class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="eName" required class="form-input pl-9">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">No.
                            Telepon</label>
                        <div class="relative"><i
                                class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="text" id="ePhone" class="form-input pl-9">
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
                    <div class="sm:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat</label>
                        <textarea id="eAddress" rows="2" class="form-input resize-none"></textarea>
                    </div>
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

        function showModal(m, b) {
            m.classList.remove('hidden');
            requestAnimationFrame(() => { b.classList.remove('scale-95', 'opacity-0'); b.classList.add('scale-100', 'opacity-100'); });
            document.body.style.overflow = 'hidden';
        }
        function hideModal(m, b) {
            b.classList.remove('scale-100', 'opacity-100'); b.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { m.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }
        function showToast(msg, type = 'success') {
            const c = type === 'success' ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5';
            const i = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1.1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);${c}`;
            t.innerHTML = `<i class="fas ${i}"></i>${msg}`;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000);
        }
        async function apiCall(url, method, body) {
            const res = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined });
            const data = await res.json();
            return { ok: res.ok, data };
        }

        // ── Create ──
        function openCreateModal() {
            ['cName', 'cPhone', 'cEmail', 'cAddress'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('createErrors').classList.add('hidden');
            showModal(document.getElementById('createModal'), document.getElementById('createModalBox'));
            setTimeout(() => document.getElementById('cName').focus(), 250);
        }
        function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
        async function submitCreate(e) {
            e.preventDefault();
            const btn = document.getElementById('cSubmitBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden');
            const { ok, data } = await apiCall('{{ route("customers.store") }}', 'POST', {
                name: document.getElementById('cName').value,
                phone: document.getElementById('cPhone').value,
                email: document.getElementById('cEmail').value,
                address: document.getElementById('cAddress').value,
            });
            if (!ok) {
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden');
            } else {
                closeCreateModal(); showToast('Pelanggan berhasil ditambahkan!');
                setTimeout(() => location.reload(), 600);
            }
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
        }

        // ── Edit ──
        function openEditModal(id, data) {
            document.getElementById('eId').value = id;
            document.getElementById('eName').value = data.name || '';
            document.getElementById('ePhone').value = data.phone || '';
            document.getElementById('eEmail').value = data.email || '';
            document.getElementById('eAddress').value = data.address || '';
            document.getElementById('editErrors').classList.add('hidden');
            showModal(document.getElementById('editModal'), document.getElementById('editModalBox'));
            setTimeout(() => document.getElementById('eName').focus(), 250);
        }
        function closeEditModal() { hideModal(document.getElementById('editModal'), document.getElementById('editModalBox')); }
        async function submitEdit(e) {
            e.preventDefault();
            const id = document.getElementById('eId').value;
            const btn = document.getElementById('eSubmitBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            const errEl = document.getElementById('editErrors'); errEl.classList.add('hidden');
            const { ok, data } = await apiCall(`/customers/${id}`, 'PUT', {
                name: document.getElementById('eName').value,
                phone: document.getElementById('ePhone').value,
                email: document.getElementById('eEmail').value,
                address: document.getElementById('eAddress').value,
            });
            if (!ok) {
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden');
            } else {
                closeEditModal(); showToast('Data pelanggan berhasil diperbarui!');
                setTimeout(() => location.reload(), 600);
            }
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Perbarui';
        }

        // ── Delete ──
        async function deleteCustomer(id, name) {
            const r = await Swal.fire({
                title: `Hapus "${name}"?`, text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
            });
            if (!r.isConfirmed) return;
            const { ok, data } = await apiCall(`/customers/${id}`, 'DELETE');
            if (ok) {
                const row = document.getElementById(`cust-row-${id}`);
                if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); }
                showToast('Pelanggan berhasil dihapus!');
            } else { showToast(data.message || 'Gagal menghapus', 'error'); }
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); } });
    </script>
@endpush