@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi pribadi dan keamanan akun Anda</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openEditProfileModal()" class="btn-primary">
                <i class="fas fa-user-edit mr-2"></i> Edit Profil
            </button>
            <button onclick="openChangePasswordModal()" class="btn-secondary">
                <i class="fas fa-key mr-2"></i> Ubah Password
            </button>
        </div>
    </div>

    @auth
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="p-8">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-indigo-50 dark:border-indigo-900/30 shadow-lg">
                            <img id="profile-avatar-display" 
                                 src="{{ $user->avatar ? Storage::url($user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="Avatar" 
                                 class="w-full h-full object-cover">
                        </div>
                        <button onclick="openEditProfileModal()" class="absolute bottom-2 right-2 w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-700 transition-transform hover:scale-110">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <div class="mb-6">
                            <h2 id="profile-name-display" class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h2>
                            <p id="profile-email-display" class="text-gray-500 dark:text-gray-400 flex items-center justify-center md:justify-start gap-2">
                                <i class="fas fa-envelope text-indigo-500"></i>
                                {{ $user->email }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700/50">
                                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Status Akun</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Aktif
                                </span>
                            </div>
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700/50">
                                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Bergabung Sejak</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $user->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-700">
            <i class="fas fa-lock text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Silakan login untuk melihat profil.</p>
            <a href="{{ route('login') }}" class="mt-4 btn-primary inline-flex">Login Sekarang</a>
        </div>
    @endauth

    {{-- Modal Edit Profile --}}
    <div id="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditProfileModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0" id="editProfileModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center">
                        <i class="fas fa-user-edit text-indigo-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Profil</h2>
                </div>
                <button onclick="closeEditProfileModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <form id="editProfileForm" onsubmit="submitEditProfile(event)" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div id="editProfileErrors" class="hidden p-3 rounded-xl text-xs bg-red-50 text-red-800 border border-red-100"></div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="text" name="name" value="{{ $user->name }}" required class="form-input pl-9" placeholder="Nama Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="email" name="email" value="{{ $user->email }}" required class="form-input pl-9" placeholder="email@contoh.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-[10px] text-gray-400">Format: JPG, PNG, GIF. Max: 2MB</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeEditProfileModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="editProfileSubmitBtn" class="btn-primary flex-1">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Change Password --}}
    <div id="changePasswordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeChangePasswordModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-200 scale-95 opacity-0" id="changePasswordModalBox">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center">
                        <i class="fas fa-key text-amber-500"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Ubah Password</h2>
                </div>
                <button onclick="closeChangePasswordModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <form id="changePasswordForm" onsubmit="submitChangePassword(event)" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div id="passwordErrors" class="hidden p-3 rounded-xl text-xs bg-red-50 text-red-800 border border-red-100"></div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password Saat Ini</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="password" name="current_password" required class="form-input pl-9" placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password Baru</label>
                    <div class="relative">
                        <i class="fas fa-shield-alt absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="password" name="password" required class="form-input pl-9" placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <i class="fas fa-check-double absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                        <input type="password" name="password_confirmation" required class="form-input pl-9" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeChangePasswordModal()" class="btn-secondary flex-1">Batal</button>
                    <button type="submit" id="changePasswordSubmitBtn" class="btn-primary flex-1">
                        <i class="fas fa-shield-check mr-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // Helpers
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

    // Edit Profile Modal
    function openEditProfileModal() {
        document.getElementById('editProfileErrors').classList.add('hidden');
        showModal(document.getElementById('editProfileModal'), document.getElementById('editProfileModalBox'));
    }

    function closeEditProfileModal() {
        hideModal(document.getElementById('editProfileModal'), document.getElementById('editProfileModalBox'));
    }

    async function submitEditProfile(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('editProfileSubmitBtn');
        const errEl = document.getElementById('editProfileErrors');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        errEl.classList.add('hidden');

        const formData = new FormData(form);

        try {
            const res = await fetch('{{ route("profile.update") }}', {
                method: 'POST', // Use POST with _method PUT for file upload support
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Gagal memperbarui profil'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                errEl.classList.remove('hidden');
                return;
            }

            showToast('Profil berhasil diperbarui!');
            
            // Update UI
            document.getElementById('profile-name-display').textContent = data.user.name;
            document.getElementById('profile-email-display').innerHTML = `<i class="fas fa-envelope text-indigo-500"></i> ${data.user.email}`;
            
            // If avatar changed, we might need to reload or update the src
            if (formData.get('avatar').size > 0) {
                setTimeout(() => location.reload(), 500);
            } else {
                closeEditProfileModal();
            }

        } catch (err) {
            console.error(err);
            errEl.innerHTML = '<p>• Koneksi gagal, coba lagi.</p>';
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan';
        }
    }

    // Change Password Modal
    function openChangePasswordModal() {
        document.getElementById('changePasswordForm').reset();
        document.getElementById('passwordErrors').classList.add('hidden');
        showModal(document.getElementById('changePasswordModal'), document.getElementById('changePasswordModalBox'));
    }

    function closeChangePasswordModal() {
        hideModal(document.getElementById('changePasswordModal'), document.getElementById('changePasswordModalBox'));
    }

    async function submitChangePassword(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('changePasswordSubmitBtn');
        const errEl = document.getElementById('passwordErrors');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        errEl.classList.add('hidden');

        const formData = new FormData(form);

        try {
            const res = await fetch('{{ route("profile.updatePassword") }}', {
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
                const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Gagal mengubah password'];
                errEl.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                errEl.classList.remove('hidden');
                return;
            }

            showToast('Password berhasil diperbarui!');
            closeChangePasswordModal();
            form.reset();

        } catch (err) {
            console.error(err);
            errEl.innerHTML = '<p>• Koneksi gagal, coba lagi.</p>';
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shield-check mr-1"></i> Perbarui';
        }
    }

    // ESC to close
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeEditProfileModal();
            closeChangePasswordModal();
        }
    });
</script>
@endpush