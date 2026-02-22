@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Diskon & Promo</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola kode promo dan diskon transaksi</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Buat Diskon
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Diskon</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-tags text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDiscounts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktif</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeDiscounts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Digunakan</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-ticket-alt text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsed) }}×</p>
        </div>
    </div>

    {{-- Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i> Daftar Diskon
            </h3>
            <form method="GET" action="{{ route('discounts.index') }}" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode diskon..."
                        class="pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20 w-48">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Diskon</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kode</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tipe & Nilai</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Min. Belanja</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Digunakan</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Berlaku</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($discounts as $d)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors" id="disc-row-{{ $d->id }}">
                            <td class="px-5 py-3.5 font-semibold text-gray-800 dark:text-white">{{ $d->name }}</td>
                            <td class="px-5 py-3.5">
                                @if($d->code)
                                    <code
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-0.5 rounded-lg text-xs font-mono font-bold">{{ $d->code }}</code>
                                @else
                                    <span class="text-gray-400 text-xs italic">Otomatis</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span
                                        class="badge {{ $d->type === 'percentage' ? 'badge-purple' : 'badge-blue' }}">{{ $d->type === 'percentage' ? '%' : 'Rp' }}</span>
                                    <span
                                        class="font-bold text-orange-600 dark:text-orange-400">{{ $d->formatted_value }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-600 dark:text-gray-400">
                                {{ $d->min_purchase > 0 ? 'Rp ' . number_format($d->min_purchase, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-600 dark:text-gray-400">
                                {{ $d->used_count }}{{ $d->max_uses ? '/' . $d->max_uses : '' }}×
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 dark:text-gray-400">
                                {{ $d->start_date ? $d->start_date->format('d/m/y') : '∞' }} –
                                {{ $d->end_date ? $d->end_date->format('d/m/y') : '∞' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($d->is_active && $d->isValid())
                                    <span class="badge badge-green"><i class="fas fa-circle text-[6px]"></i> Aktif</span>
                                @elseif(!$d->is_active)
                                    <span class="badge badge-gray">Nonaktif</span>
                                @else
                                    <span class="badge badge-red">Kadaluarsa</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        onclick="openEditModal({{ $d->id }}, {{ json_encode(['name' => $d->name, 'code' => $d->code, 'type' => $d->type, 'value' => $d->value, 'min_purchase' => $d->min_purchase, 'max_uses' => $d->max_uses, 'start_date' => $d->start_date?->format('Y-m-d'), 'end_date' => $d->end_date?->format('Y-m-d'), 'is_active' => $d->is_active]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="deleteDiscount({{ $d->id }}, '{{ addslashes($d->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-tags text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada diskon</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($discounts->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $discounts->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════ MODAL CREATE ══════════════════════ --}}
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-200 scale-95 opacity-0"
            id="createModalBox">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 sticky top-0 bg-white dark:bg-gray-800 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(245,158,11,0.12);">
                        <i class="fas fa-tags text-amber-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Buat Diskon Baru</h2>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitCreate(event)" class="p-6 space-y-4">
                <div id="createErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                            Diskon *</label>
                        <input type="text" id="cName" required placeholder="Diskon Hari Raya" class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kode
                            Promo <span class="text-gray-400 normal-case">(opsional)</span></label>
                        <input type="text" id="cCode" placeholder="HEMAT20" class="form-input uppercase"
                            oninput="this.value=this.value.toUpperCase()">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika diskon otomatis</p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tipe
                            Diskon *</label>
                        <select id="cType" onchange="updatePrefix('c')" class="form-input">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nilai
                            Diskon *</label>
                        <div class="relative">
                            <span id="cPrefix"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">%</span>
                            <input type="number" id="cValue" required step="0.01" min="0.01" class="form-input pl-8">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Min.
                            Pembelian</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" id="cMinPurchase" value="0" min="0" step="1000" class="form-input pl-10">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Maks.
                            Penggunaan <span class="text-gray-400 normal-case">(kosong = ∞)</span></label>
                        <input type="number" id="cMaxUses" min="1" placeholder="100" class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                            Mulai</label>
                        <input type="date" id="cStartDate" class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                            Berakhir</label>
                        <input type="date" id="cEndDate" class="form-input">
                    </div>
                    <div class="md:col-span-2 flex items-center gap-3 py-1">
                        <label class="relative inline-flex items-center cursor-pointer gap-3">
                            <input type="checkbox" id="cIsActive" checked class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan Diskon</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeCreateModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="cSubmitBtn" class="btn-primary flex-1"><i class="fas fa-save"></i> Buat
                        Diskon</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════ MODAL EDIT ══════════════════════ --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-200 scale-95 opacity-0"
            id="editModalBox">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 sticky top-0 bg-white dark:bg-gray-800 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Diskon</h2>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            <form onsubmit="submitEdit(event)" class="p-6 space-y-4">
                <input type="hidden" id="eId">
                <div id="editErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                            Diskon *</label>
                        <input type="text" id="eName" required class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kode
                            Promo</label>
                        <input type="text" id="eCode" class="form-input uppercase"
                            oninput="this.value=this.value.toUpperCase()">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tipe
                            Diskon *</label>
                        <select id="eType" onchange="updatePrefix('e')" class="form-input">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nilai
                            Diskon *</label>
                        <div class="relative">
                            <span id="ePrefix"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">%</span>
                            <input type="number" id="eValue" required step="0.01" min="0.01" class="form-input pl-8">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Min.
                            Pembelian</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" id="eMinPurchase" min="0" step="1000" class="form-input pl-10">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Maks.
                            Penggunaan</label>
                        <input type="number" id="eMaxUses" min="1" class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                            Mulai</label>
                        <input type="date" id="eStartDate" class="form-input">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                            Berakhir</label>
                        <input type="date" id="eEndDate" class="form-input">
                    </div>
                    <div class="md:col-span-2 flex items-center gap-3 py-1">
                        <label class="relative inline-flex items-center cursor-pointer gap-3">
                            <input type="checkbox" id="eIsActive" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan Diskon</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="eSubmitBtn" class="btn-primary flex-1"><i class="fas fa-save"></i> Simpan
                        Perubahan</button>
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

        function updatePrefix(p) { const t = document.getElementById(`${p}Type`).value; document.getElementById(`${p}Prefix`).textContent = t === 'percentage' ? '%' : 'Rp'; }

        function getCreateData() { return { name: document.getElementById('cName').value, code: document.getElementById('cCode').value || null, type: document.getElementById('cType').value, value: parseFloat(document.getElementById('cValue').value), min_purchase: parseFloat(document.getElementById('cMinPurchase').value) || 0, max_uses: document.getElementById('cMaxUses').value || null, start_date: document.getElementById('cStartDate').value || null, end_date: document.getElementById('cEndDate').value || null, is_active: document.getElementById('cIsActive').checked ? 1 : 0 }; }
        function getEditData() { return { name: document.getElementById('eName').value, code: document.getElementById('eCode').value || null, type: document.getElementById('eType').value, value: parseFloat(document.getElementById('eValue').value), min_purchase: parseFloat(document.getElementById('eMinPurchase').value) || 0, max_uses: document.getElementById('eMaxUses').value || null, start_date: document.getElementById('eStartDate').value || null, end_date: document.getElementById('eEndDate').value || null, is_active: document.getElementById('eIsActive').checked ? 1 : 0 }; }

        function openCreateModal() { ['cName', 'cCode', 'cValue', 'cMinPurchase', 'cMaxUses', 'cStartDate', 'cEndDate'].forEach(id => { const el = document.getElementById(id); if (el) el.value = id === 'cMinPurchase' ? '0' : ''; }); document.getElementById('cIsActive').checked = true; document.getElementById('cType').value = 'percentage'; updatePrefix('c'); document.getElementById('createErrors').classList.add('hidden'); showModal(document.getElementById('createModal'), document.getElementById('createModalBox')); setTimeout(() => document.getElementById('cName').focus(), 250); }
        function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
        async function submitCreate(e) { e.preventDefault(); const btn = document.getElementById('cSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall('{{ route("discounts.store") }}', 'POST', getCreateData()); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeCreateModal(); showToast('Diskon berhasil dibuat!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Buat Diskon'; }

        function openEditModal(id, d) { document.getElementById('eId').value = id; document.getElementById('eName').value = d.name || ''; document.getElementById('eCode').value = d.code || ''; document.getElementById('eType').value = d.type || 'percentage'; document.getElementById('eValue').value = d.value || ''; document.getElementById('eMinPurchase').value = d.min_purchase || 0; document.getElementById('eMaxUses').value = d.max_uses || ''; document.getElementById('eStartDate').value = d.start_date || ''; document.getElementById('eEndDate').value = d.end_date || ''; document.getElementById('eIsActive').checked = !!d.is_active; updatePrefix('e'); document.getElementById('editErrors').classList.add('hidden'); showModal(document.getElementById('editModal'), document.getElementById('editModalBox')); setTimeout(() => document.getElementById('eName').focus(), 250); }
        function closeEditModal() { hideModal(document.getElementById('editModal'), document.getElementById('editModalBox')); }
        async function submitEdit(e) { e.preventDefault(); const id = document.getElementById('eId').value; const btn = document.getElementById('eSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('editErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall(`/discounts/${id}`, 'PUT', getEditData()); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeEditModal(); showToast('Diskon berhasil diperbarui!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan'; }

        async function deleteDiscount(id, name) { const r = await Swal.fire({ title: `Hapus "${name}"?`, text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' }); if (!r.isConfirmed) return; const { ok, data } = await apiCall(`/discounts/${id}`, 'DELETE'); if (ok) { const row = document.getElementById(`disc-row-${id}`); if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); } showToast('Diskon berhasil dihapus!'); } else { showToast(data.message || 'Gagal menghapus', 'error'); } }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); } });
    </script>
@endpush