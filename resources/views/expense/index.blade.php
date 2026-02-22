@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengeluaran</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Catat dan kelola pengeluaran bisnis</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-plus text-sm"></i> Catat Pengeluaran
        </button>
    </div>

    {{-- Stats + Chart Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        {{-- Summary Sidebar --}}
        <div class="space-y-4">
            {{-- Total --}}
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-5">
                <div class="p-4 rounded-xl mb-4"
                    style="background: linear-gradient(135deg, rgba(239,68,68,0.08), rgba(244,63,94,0.04)); border: 1px solid rgba(239,68,68,0.12);">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-700 dark:text-red-400 mt-0.5">Rp
                        {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                </div>
                <div class="space-y-3">
                    @foreach($categories as $cat)
                        @php
                            $total = \Illuminate\Support\Facades\DB::table('expenses')->where('category', $cat)->sum('amount');
                            $pct = $totalExpenses > 0 ? round(($total / $totalExpenses) * 100) : 0;
                            $clr = match ($cat) { 'Bahan Baku' => '#14b890', 'Operasional' => '#6366f1', 'Gaji' => '#f59e0b', default => '#9ca3af'};
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $cat }}</span>
                                <span class="text-gray-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                    style="width:{{ $pct }}%; background: {{ $clr }};"></div>
                            </div>
                            <p class="text-right text-xs text-gray-400 mt-0.5">{{ $pct }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Expense List --}}
        <div class="lg:col-span-2">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list text-emerald-500"></i> Riwayat Pengeluaran
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Keterangan</th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors"
                                    id="exp-row-{{ $expense->id }}">
                                    <td class="px-5 py-3.5">
                                        <p class="font-semibold text-gray-800 dark:text-white text-xs">
                                            {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @php $badgeMap = ['Bahan Baku' => 'badge-green', 'Operasional' => 'badge-purple', 'Gaji' => 'badge-yellow', 'Lainnya' => 'badge-gray']; @endphp
                                        <span
                                            class="badge {{ $badgeMap[$expense->category] ?? 'badge-gray' }}">{{ $expense->category }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                        {{ $expense->description ?? '—' }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="font-bold text-red-600 dark:text-red-400">- Rp
                                            {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <button onclick="deleteExpense({{ $expense->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors mx-auto">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                        <i class="fas fa-money-bill-wave text-3xl mb-2 block opacity-20"></i>
                                        <p class="text-sm">Belum ada pengeluaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                    {{ $expenses->links('vendor.tailwind') }}
                </div>
            </div>
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
                        style="background: rgba(239,68,68,0.12);">
                        <i class="fas fa-receipt text-red-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Catat Pengeluaran Baru</h2>
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
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal
                        *</label>
                    <div class="relative"><i
                            class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="date" id="cDate" required class="form-input pl-9" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Jumlah
                        (Rp) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-semibold">Rp</span>
                        <input type="number" id="cAmount" step="0.01" required class="form-input pl-9" placeholder="50000">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kategori
                        *</label>
                    <select id="cCategory" required class="form-input">
                        <option value="">Pilih Kategori</option>
                        <option value="Bahan Baku">Bahan Baku</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Keterangan</label>
                    <textarea id="cDesc" rows="2" placeholder="Deskripsi pengeluaran..."
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

@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        function showModal(m, b) { m.classList.remove('hidden'); requestAnimationFrame(() => { b.classList.remove('scale-95', 'opacity-0'); b.classList.add('scale-100', 'opacity-100'); }); document.body.style.overflow = 'hidden'; }
        function hideModal(m, b) { b.classList.remove('scale-100', 'opacity-100'); b.classList.add('scale-95', 'opacity-0'); setTimeout(() => { m.classList.add('hidden'); document.body.style.overflow = ''; }, 200); }
        function showToast(msg, type = 'success') { const c = type === 'success' ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5'; const t = document.createElement('div'); t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1.1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);${c}`; t.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${msg}`; document.body.appendChild(t); setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000); }
        async function apiCall(url, method, body) { const res = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined }); const data = await res.json(); return { ok: res.ok, data }; }

        function openCreateModal() { document.getElementById('cDate').value = '{{ date("Y-m-d") }}'; document.getElementById('cAmount').value = ''; document.getElementById('cCategory').value = ''; document.getElementById('cDesc').value = ''; document.getElementById('createErrors').classList.add('hidden'); showModal(document.getElementById('createModal'), document.getElementById('createModalBox')); setTimeout(() => document.getElementById('cAmount').focus(), 250); }
        function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
        async function submitCreate(e) { e.preventDefault(); const btn = document.getElementById('cSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden'); const { ok, data } = await apiCall('{{ route("expenses.store") }}', 'POST', { date: document.getElementById('cDate').value, amount: document.getElementById('cAmount').value, category: document.getElementById('cCategory').value, description: document.getElementById('cDesc').value || null }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeCreateModal(); showToast('Pengeluaran berhasil dicatat!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan'; }

        async function deleteExpense(id) { const r = await Swal.fire({ title: 'Hapus pengeluaran ini?', text: 'Tindakan ini tidak bisa dibatalkan.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' }); if (!r.isConfirmed) return; const { ok, data } = await apiCall(`/expenses/${id}`, 'DELETE'); if (ok) { const row = document.getElementById(`exp-row-${id}`); if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); } showToast('Pengeluaran berhasil dihapus!'); } else { showToast(data.message || 'Gagal menghapus', 'error'); } }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCreateModal(); });
    </script>
@endpush