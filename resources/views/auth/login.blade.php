@extends('layouts.login')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    * { font-family: 'Inter', sans-serif; }

    body {
        min-height: 100vh;
        background: #0d1520;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    /* ── Animated background ── */
    .bg-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.18;
        animation: orb-float 8s ease-in-out infinite;
    }
    .bg-orb-1 { width: 420px; height: 420px; background: #14b890; top: -80px; left: -100px; animation-delay: 0s; }
    .bg-orb-2 { width: 350px; height: 350px; background: #6366f1; bottom: -60px; right: -80px; animation-delay: -3s; }
    .bg-orb-3 { width: 250px; height: 250px; background: #3b82f6; top: 40%; left: 30%; animation-delay: -5s; }

    @keyframes orb-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33%       { transform: translate(20px, -20px) scale(1.05); }
        66%       { transform: translate(-15px, 15px) scale(0.97); }
    }

    /* ── Grid pattern ── */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: 
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    /* ── Login card ── */
    .login-card {
        position: relative;
        width: 100%;
        max-width: 420px;
        background: rgba(255,255,255,0.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 1.5rem;
        padding: 2.25rem;
        box-shadow: 0 24px 64px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
        animation: card-in 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes card-in {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ── Brand logo chip ── */
    .brand-chip {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, #0d9373, #14b890);
        border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 24px rgba(13,147,115,0.45), 0 0 0 1px rgba(255,255,255,0.08);
        margin: 0 auto 1.25rem;
        animation: pulse-glow 3s ease-in-out infinite;
    }
    @keyframes pulse-glow {
        0%,100% { box-shadow: 0 8px 24px rgba(13,147,115,0.45), 0 0 0 1px rgba(255,255,255,0.08); }
        50%      { box-shadow: 0 8px 32px rgba(13,147,115,0.7), 0 0 0 4px rgba(13,147,115,0.15); }
    }

    /* ── Inputs ── */
    .login-input {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        padding: 0.75rem 0.875rem 0.75rem 2.75rem;
        color: #fff;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .login-input::placeholder { color: rgba(255,255,255,0.25); }
    .login-input:focus {
        border-color: #14b890;
        background: rgba(20,184,144,0.08);
        box-shadow: 0 0 0 4px rgba(20,184,144,0.12);
    }

    /* ── Button ── */
    .login-btn {
        width: 100%;
        padding: 0.8rem;
        border-radius: 0.75rem;
        border: none;
        background: linear-gradient(135deg, #0d9373 0%, #14b890 50%, #10b981 100%);
        background-size: 200% auto;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-position 0.4s, box-shadow 0.2s, transform 0.15s;
        box-shadow: 0 4px 20px rgba(13,147,115,0.4);
        letter-spacing: 0.01em;
    }
    .login-btn:hover {
        background-position: right center;
        box-shadow: 0 6px 28px rgba(13,147,115,0.55);
        transform: translateY(-1px);
    }
    .login-btn:active { transform: translateY(0); }

    /* ── Divider ── */
    .login-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        margin: 1rem 0;
    }

    /* ── Labels ── */
    .login-label { display: block; font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.5); letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 0.4rem; }

    /* ── Error ── */
    .login-error {
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.25);
        border-radius: 0.65rem;
        padding: 0.65rem 0.875rem;
        font-size: 0.8rem;
        color: #fca5a5;
        margin-bottom: 1rem;
    }
</style>

<body>
    <!-- Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    <div class="login-card">
        @php
            try { $profile = \App\Models\StoreProfile::first(); } catch(\Exception $e) { $profile = null; }
            $storeName = $profile->name ?? 'Arneta Collection';
            $logoUrl = null;
            if (!empty($profile->logo)) {
                $logoUrl = filter_var($profile->logo, FILTER_VALIDATE_URL)
                    ? $profile->logo
                    : asset('storage/'.$profile->logo);
            }
        @endphp

        <!-- Brand -->
        <div class="text-center">
            <div class="brand-chip">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover rounded-[14px]"
                         onerror="this.style.display='none'">
                @else
                    <i class="fas fa-store text-white text-xl"></i>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.4);">Masuk ke <span style="color: #2dd4aa;">{{ $storeName }}</span></p>
        </div>

        <div class="login-divider mt-5"></div>

        <!-- Errors -->
        @if($errors->any())
        <div class="login-error mb-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(session('status'))
        <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);border-radius:0.65rem;padding:0.65rem 0.875rem;font-size:0.8rem;color:#6ee7b7;margin-bottom:1rem;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="login-label">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-xs" style="color: rgba(255,255,255,0.3);"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="email@contoh.com"
                           class="login-input">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="login-label">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-xs" style="color: rgba(255,255,255,0.3);"></i>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="login-input" style="padding-right: 2.75rem;">
                    <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2 transition-colors"
                            style="color: rgba(255,255,255,0.3);" id="eyeBtn">
                        <i class="fas fa-eye text-xs" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded" style="accent-color: #14b890;"
                           {{ old('remember') ? 'checked' : '' }}>
                    <span style="font-size:0.8rem; color: rgba(255,255,255,0.45);">Ingat saya</span>
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:0.8rem; color: #2dd4aa;" class="hover:underline">
                    Lupa password?
                </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="login-btn mt-1">
                <span id="btnText">Masuk</span>
                <i class="fas fa-arrow-right ml-2 text-sm"></i>
            </button>

            @if(Route::has('register'))
            <div class="login-divider"></div>
            <p class="text-center" style="font-size:0.8rem; color: rgba(255,255,255,0.35);">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color: #2dd4aa;" class="font-semibold hover:underline ml-1">Daftar</a>
            </p>
            @endif
        </form>
    </div>

    <!-- Footer -->
    <p class="fixed bottom-4 text-center w-full text-xs" style="color: rgba(255,255,255,0.2);">
        &copy; {{ date('Y') }} {{ $storeName }} · POS System
    </p>
</body>

@push('scripts')
<script>
function togglePwd() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'fas fa-eye-slash text-xs';
    } else {
        inp.type = 'password';
        ico.className = 'fas fa-eye text-xs';
    }
}
// Submit loading state
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.querySelector('.login-btn');
    btn.style.opacity = '0.7';
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
});
</script>
@endpush

@endsection