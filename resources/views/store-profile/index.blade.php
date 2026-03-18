@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Toko</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Informasi dan identitas toko Anda</p>
        </div>
        <button onclick="openEditStoreProfileModal()" class="btn-primary">
            <i class="fas fa-edit text-sm"></i> Edit Profil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                {{-- Banner --}}
                

                <div class="px-5 pb-5">
                    {{-- Logo --}}
                    <div class="mt-10 mb-4 flex justify-center">
                        @php
                            $logoUrl = null;
                            if (!empty($profile->logo)) {
                                $logoUrl = filter_var($profile->logo, FILTER_VALIDATE_URL) ? $profile->logo : asset('storage/' . $profile->logo);
                            }
                        @endphp
                        <div class="w-20 h-20 rounded-2xl border-4 border-white dark:border-gray-800 overflow-hidden shadow-lg flex items-center justify-center"
                            style="background: linear-gradient(135deg, #0d9373, #14b890);">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo {{ $profile->name }}" class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; this.nextSibling.style.display='flex';">
                                <div style="display:none;" class="w-full h-full items-center justify-center">
                                    <i class="fas fa-store text-white text-2xl"></i>
                                </div>
                            @else
                                <i class="fas fa-store text-white text-2xl"></i>
                            @endif
                        </div>
                    </div>

                    <div class="text-center mb-5">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $profile->name ?? 'Nama Toko' }}</h2>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mt-0.5">
                            {{ $profile->type ?? 'Retail Store' }}</p>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(16,185,129,0.06);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                style="background: rgba(16,185,129,0.12);">
                                <i class="fas fa-map-marker-alt text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Alamat</p>
                                <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->address ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(99,102,241,0.06);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                style="background: rgba(99,102,241,0.12);">
                                <i class="fas fa-phone text-indigo-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Telepon</p>
                                <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->phone ?? '—' }}</p>
                            </div>
                        </div>
                        @if (!empty($profile->email))
                            <div class="flex items-start gap-3 p-3 rounded-xl" style="background: rgba(245,158,11,0.06);">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(245,158,11,0.12);">
                                    <i class="fas fa-envelope text-amber-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Email</p>
                                    <p class="text-sm text-gray-800 dark:text-white mt-0.5">{{ $profile->email }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button onclick="openEditStoreProfileModal()" class="btn-primary w-full justify-center mt-5">
                        <i class="fas fa-edit"></i> Edit Profil Toko
                    </button>
                </div>
            </div>
        </div>

        {{-- Additional Info --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(16,185,129,0.12);">
                        <i class="fas fa-box text-emerald-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Product::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Produk</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(99,102,241,0.12);">
                        <i class="fas fa-users text-indigo-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Customer::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pelanggan</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center"
                        style="background: rgba(245,158,11,0.12);">
                        <i class="fas fa-receipt text-amber-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Transaction::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Transaksi</p>
                </div>
            </div>

            {{-- System Info --}}
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-info-circle text-emerald-500"></i> Informasi Sistem
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            Versi Aplikasi</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Arneta POS v2.0</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Mata
                            Uang</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">IDR (Rupiah)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Zona
                            Waktu</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Asia/Jakarta (WIB)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            Terakhir Diperbarui</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ $profile->updated_at ? $profile->updated_at->format('d M Y') : '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div
                class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-emerald-500"></i> Akses Cepat
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $quickLinks = [
                            ['route' => 'products.index', 'icon' => 'fa-box', 'label' => 'Produk', 'color' => '#0d9373'],
                            ['route' => 'categories.index', 'icon' => 'fa-layer-group', 'label' => 'Kategori', 'color' => '#6366f1'],
                            ['route' => 'payment.index', 'icon' => 'fa-credit-card', 'label' => 'Metode Bayar', 'color' => '#f59e0b'],
                            ['route' => 'users.index', 'icon' => 'fa-users', 'label' => 'Pengguna', 'color' => '#ef4444'],
                        ];
                    @endphp
                    @foreach ($quickLinks as $link)
                        <a href="{{ route($link['route']) }}"
                            class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                                style="background: {{ $link['color'] }}1a;">
                                <i class="fas {{ $link['icon'] }} text-sm" style="color: {{ $link['color'] }};"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 text-center">{{ $link['label'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Store Profile --}}
    <div id="editStoreProfileModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditStoreProfileModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0" id="editStoreProfileModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center">
                        <i class="fas fa-store text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Profil Toko</h2>
                </div>
                <button onclick="closeEditStoreProfileModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <form id="editStoreProfileForm" onsubmit="submitEditStoreProfile(event)" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div id="editStoreErrors" class="hidden p-3 rounded-xl text-xs bg-red-50 text-red-800 border border-red-100"></div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Logo Toko</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-[10px] text-gray-400">Pilih logo batu jika ingin mengubah. Maks 2MB.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama Toko *</label>
                    <div class="relative">
                        <i class="fas fa-store absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" name="name" value="{{ $profile->name ?? '' }}" required class="form-input pl-9" placeholder="Nama Toko Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Telepon *</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" name="phone" value="{{ $profile->phone ?? '' }}" required class="form-input pl-9" placeholder="Nomor Telepon">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat *</label>
                    <textarea name="address" rows="3" required class="form-input w-full p-3 rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Alamat Lengkap">{{ $profile->address ?? '' }}</textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeEditStoreProfileModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="editStoreSubmitBtn" class="btn-primary flex-1">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

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
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function showToast(msg, type = 'success') {
        const colors = type === 'success' ?
            'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' :
            'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5';
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        const t = document.createElement('div');
        t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);animation:fadeIn .3s ease;${colors}`;
        t.innerHTML = `<i class="${icon}"></i>${msg}`;
        document.body.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transition = 'opacity .3s';
            setTimeout(() => t.remove(), 300);
        }, 3000);
    }

    function openEditStoreProfileModal() {
        document.getElementById('editStoreErrors').classList.add('hidden');
        showModal(document.getElementById('editStoreProfileModal'), document.getElementById('editStoreProfileModalBox'));
    }

    function closeEditStoreProfileModal() {
        hideModal(document.getElementById('editStoreProfileModal'), document.getElementById('editStoreProfileModalBox'));
    }

    async function submitEditStoreProfile(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('editStoreSubmitBtn');
        const errEl = document.getElementById('editStoreErrors');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        errEl.classList.add('hidden');

        const formData = new FormData(form);

        try {
            const res = await fetch('{{ route("store-profile.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Gagal memperbarui profil toko'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                errEl.classList.remove('hidden');
                return;
            }

            showToast('Profil toko berhasil diperbarui!');
            
            // Reload to show the new updated profile data 
            setTimeout(() => location.reload(), 600);

        } catch (err) {
            console.error(err);
            errEl.innerHTML = '<p>• Koneksi gagal, coba lagi.</p>';
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan';
        }
    }

    // ESC to close
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeEditStoreProfileModal();
        }
    });
</script>
@endpush