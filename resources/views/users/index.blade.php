@extends('layouts.app')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola akun dan hak akses pengguna sistem</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary">
            <i class="fas fa-user-plus text-sm"></i> Tambah Pengguna
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @php
            $totalUsers = $users->total() ?? count($users);
            $adminCount = \App\Models\User::where('role', 'admin')->count();
            $kasirCount = \App\Models\User::where('role', 'kasir')->count();
        @endphp
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: rgba(16,185,129,0.12);">
                <i class="fas fa-users text-emerald-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pengguna
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
            </div>
        </div>
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: rgba(99,102,241,0.12);">
                <i class="fas fa-user-shield text-indigo-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admin</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $adminCount }}</p>
            </div>
        </div>
        <div
            class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.12);">
                <i class="fas fa-cash-register text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kasir</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kasirCount }}</p>
            </div>
        </div>
    </div>

    {{-- User Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div
            class="flex flex-col sm:flex-row sm:items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-users text-emerald-500"></i>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Daftar Pengguna</h3>
                <span class="badge badge-green">{{ $totalUsers }} pengguna</span>
            </div>
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..."
                        class="form-input pl-8 py-2 text-xs w-44">
                </div>
                <button type="submit" class="btn-secondary py-2 px-3 text-xs"><i class="fas fa-search"></i></button>
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn-secondary py-2 px-3 text-xs"><i
                            class="fas fa-times"></i></a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pengguna</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">
                            Email</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Role</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Bergabung</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse ($users as $user)
                        @php
                            $colors = ['#0d9373', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6', '#f97316'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors"
                            id="user-row-{{ $user->id }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                        style="background: {{ $color }};">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 sm:hidden">{{ $user->email }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">
                                                <i class="fas fa-circle text-[6px]"></i> Anda
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                                {{ $user->email }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background: rgba(99,102,241,0.1); color: #6366f1;">
                                        <i class="fas fa-user-shield text-[10px]"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background: rgba(245,158,11,0.1); color: #d97706;">
                                        <i class="fas fa-cash-register text-[10px]"></i> Kasir
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button
                                        onclick="openEditModal({{ $user->id }}, {{ json_encode(['name' => $user->name, 'email' => $user->email, 'role' => $user->role]) }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    @else
                                        <div class="w-8 h-8 flex items-center justify-center text-gray-300"
                                            title="Tidak bisa hapus akun sendiri">
                                            <i class="fas fa-ban text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-users text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Tidak ada pengguna ditemukan</p>
                                @if(request('search'))
                                    <a href="{{ route('users.index') }}"
                                        class="text-emerald-500 text-xs mt-1 inline-block hover:underline">Hapus filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $users->appends(request()->query())->links('vendor.tailwind') }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════ MODAL CREATE ══════════════════════ --}}
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-200 scale-95 opacity-0"
            id="createModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50"
                style="background: linear-gradient(135deg, #0d9373 0%, #065f46 100%); border-radius: 1rem 1rem 0 0;">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <h2 class="text-base font-bold text-white">Tambah Pengguna Baru</h2>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                    <i class="fas fa-times text-white/80"></i>
                </button>
            </div>
            <form onsubmit="submitCreate(event)" class="p-6 space-y-4">
                <div id="createErrors" class="hidden p-3 rounded-xl text-xs" style="background:#fee2e2;color:#991b1b;">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama
                        *</label>
                    <div class="relative"><i
                            class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="cName" required placeholder="Nama lengkap" class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email
                        *</label>
                    <div class="relative"><i
                            class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="email" id="cEmail" required placeholder="user@email.com" class="form-input pl-9">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password
                            *</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="password" id="cPassword" required placeholder="Min. 8 karakter"
                                class="form-input pl-9 pr-9">
                            <button type="button" onclick="togglePwd('cPassword','cPwdIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-xs" id="cPwdIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi
                            *</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="password" id="cPasswordConfirm" required placeholder="Ulangi password"
                                class="form-input pl-9">
                        </div>
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Role
                        *</label>
                    <div class="grid grid-cols-2 gap-2" id="cRoleSelector">
                        <label class="role-card cursor-pointer">
                            <input type="radio" name="cRole" value="admin" class="sr-only">
                            <div
                                class="role-opt flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-indigo-400 transition-all text-center">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background: rgba(99,102,241,0.12);">
                                    <i class="fas fa-user-shield text-indigo-500 text-sm"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Admin</span>
                            </div>
                        </label>
                        <label class="role-card cursor-pointer">
                            <input type="radio" name="cRole" value="kasir" class="sr-only" checked>
                            <div
                                class="role-opt flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-amber-400 transition-all text-center">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background: rgba(245,158,11,0.12);">
                                    <i class="fas fa-cash-register text-amber-500 text-sm"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kasir</span>
                            </div>
                        </label>
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
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Pengguna</h2>
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
                        *</label>
                    <div class="relative"><i
                            class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" id="eName" required class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email
                        *</label>
                    <div class="relative"><i
                            class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="email" id="eEmail" required class="form-input pl-9">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password
                            Baru <span class="text-gray-400 normal-case">(opsional)</span></label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="password" id="ePassword" placeholder="Kosongkan jika tidak diubah"
                                class="form-input pl-9 pr-9">
                            <button type="button" onclick="togglePwd('ePassword','ePwdIcon')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-xs" id="ePwdIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input type="password" id="ePasswordConfirm" class="form-input pl-9">
                        </div>
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Role
                        *</label>
                    <div class="grid grid-cols-2 gap-2" id="eRoleSelector">
                        <label class="role-card cursor-pointer">
                            <input type="radio" name="eRole" value="admin" class="sr-only">
                            <div
                                class="role-opt flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-indigo-400 transition-all text-center">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background: rgba(99,102,241,0.12);">
                                    <i class="fas fa-user-shield text-indigo-500 text-sm"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Admin</span>
                            </div>
                        </label>
                        <label class="role-card cursor-pointer">
                            <input type="radio" name="eRole" value="kasir" class="sr-only">
                            <div
                                class="role-opt flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-amber-400 transition-all text-center">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background: rgba(245,158,11,0.12);">
                                    <i class="fas fa-cash-register text-amber-500 text-sm"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kasir</span>
                            </div>
                        </label>
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

    @push('styles')
        <style>
            .role-card input:checked+.role-opt {
                border-color: #0d9373;
                background: rgba(13, 147, 115, 0.05);
                box-shadow: 0 0 0 3px rgba(13, 147, 115, 0.15);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            function showModal(m, b) { m.classList.remove('hidden'); requestAnimationFrame(() => { b.classList.remove('scale-95', 'opacity-0'); b.classList.add('scale-100', 'opacity-100'); }); document.body.style.overflow = 'hidden'; }
            function hideModal(m, b) { b.classList.remove('scale-100', 'opacity-100'); b.classList.add('scale-95', 'opacity-0'); setTimeout(() => { m.classList.add('hidden'); document.body.style.overflow = ''; }, 200); }
            function showToast(msg, type = 'success') { const c = type === 'success' ? 'background:#d1fae5;color:#065f46;border:1px solid #a7f3d0' : 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5'; const t = document.createElement('div'); t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;padding:.75rem 1.1rem;border-radius:.75rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.5rem;box-shadow:0 8px 24px rgba(0,0,0,.12);${c}`; t.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${msg}`; document.body.appendChild(t); setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3000); }
            async function apiCall(url, method, body) { const res = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined }); const data = await res.json(); return { ok: res.ok, data }; }

            function togglePwd(fieldId, iconId) { const f = document.getElementById(fieldId); const i = document.getElementById(iconId); const isText = f.type === 'text'; f.type = isText ? 'password' : 'text'; i.classList.toggle('fa-eye', isText); i.classList.toggle('fa-eye-slash', !isText); }

            function initRoleCards(prefix) { document.querySelectorAll(`[name="${prefix}Role"]`).forEach(radio => { radio.addEventListener('change', function () { document.querySelectorAll(`[name="${prefix}Role"] + .role-opt`).forEach(opt => { opt.style.borderColor = ''; opt.style.background = ''; opt.style.boxShadow = ''; }); if (this.checked) { const opt = this.nextElementSibling; opt.style.borderColor = '#0d9373'; opt.style.background = 'rgba(13,147,115,0.05)'; opt.style.boxShadow = '0 0 0 3px rgba(13,147,115,0.15)'; } }); if (radio.checked) radio.dispatchEvent(new Event('change')); }); }

            function openCreateModal() { ['cName', 'cEmail', 'cPassword', 'cPasswordConfirm'].forEach(id => document.getElementById(id).value = ''); document.querySelector('[name="cRole"][value="kasir"]').checked = true; document.getElementById('createErrors').classList.add('hidden'); showModal(document.getElementById('createModal'), document.getElementById('createModalBox')); initRoleCards('c'); setTimeout(() => document.getElementById('cName').focus(), 250); }
            function closeCreateModal() { hideModal(document.getElementById('createModal'), document.getElementById('createModalBox')); }
            async function submitCreate(e) { e.preventDefault(); const btn = document.getElementById('cSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('createErrors'); errEl.classList.add('hidden'); const role = document.querySelector('[name="cRole"]:checked')?.value || 'kasir'; const { ok, data } = await apiCall('{{ route("users.store") }}', 'POST', { name: document.getElementById('cName').value, email: document.getElementById('cEmail').value, password: document.getElementById('cPassword').value, password_confirmation: document.getElementById('cPasswordConfirm').value, role }); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeCreateModal(); showToast('Pengguna berhasil ditambahkan!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan'; }

            function openEditModal(id, d) { document.getElementById('eId').value = id; document.getElementById('eName').value = d.name || ''; document.getElementById('eEmail').value = d.email || ''; document.getElementById('ePassword').value = ''; document.getElementById('ePasswordConfirm').value = ''; const roleRadio = document.querySelector(`[name="eRole"][value="${d.role}"]`); if (roleRadio) { roleRadio.checked = true; } document.getElementById('editErrors').classList.add('hidden'); showModal(document.getElementById('editModal'), document.getElementById('editModalBox')); initRoleCards('e'); setTimeout(() => document.getElementById('eName').focus(), 250); }
            function closeEditModal() { hideModal(document.getElementById('editModal'), document.getElementById('editModalBox')); }
            async function submitEdit(e) { e.preventDefault(); const id = document.getElementById('eId').value; const btn = document.getElementById('eSubmitBtn'); btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; const errEl = document.getElementById('editErrors'); errEl.classList.add('hidden'); const role = document.querySelector('[name="eRole"]:checked')?.value || 'kasir'; const pwd = document.getElementById('ePassword').value; const body = { name: document.getElementById('eName').value, email: document.getElementById('eEmail').value, role }; if (pwd) { body.password = pwd; body.password_confirmation = document.getElementById('ePasswordConfirm').value; } const { ok, data } = await apiCall(`/users/${id}`, 'PUT', body); if (!ok) { const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Terjadi kesalahan']; errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join(''); errEl.classList.remove('hidden'); } else { closeEditModal(); showToast('Pengguna berhasil diperbarui!'); setTimeout(() => location.reload(), 600); } btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Perbarui'; }

            async function deleteUser(id, name) { const r = await Swal.fire({ title: `Hapus "${name}"?`, html: `Pengguna <strong>${name}</strong> akan dihapus secara permanen.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!', cancelButtonText: 'Batal', customClass: { confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' } }); if (!r.isConfirmed) return; const { ok, data } = await apiCall(`/users/${id}`, 'DELETE'); if (ok) { const row = document.getElementById(`user-row-${id}`); if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); } showToast('Pengguna berhasil dihapus!'); } else { showToast(data.message || 'Gagal menghapus', 'error'); } }

            document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCreateModal(); closeEditModal(); } });
        </script>
    @endpush

@endsection