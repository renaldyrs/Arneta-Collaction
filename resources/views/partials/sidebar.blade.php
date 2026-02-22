<div class="flex flex-col h-full">
    @php
        // Ambil data profil toko dengan handling yang lebih baik
        try {
            $profile = \App\Models\StoreProfile::first();
        } catch (\Exception $e) {
            $profile = null;
        }

        // Jika profile tidak ada, buat object default
        if (!$profile) {
            $profile = (object) [
                'name' => 'Toko',
                'logo' => null,
                'logo_url' => asset('images/default-logo.png')
            ];
        } else {
            // Tentukan logo URL jika tidak ada accessor di model
            if (!isset($profile->logo_url)) {
                if (empty($profile->logo)) {
                    $profile->logo_url = asset('images/default-logo.png');
                } elseif (filter_var($profile->logo, FILTER_VALIDATE_URL)) {
                    $profile->logo_url = $profile->logo;
                } else {
                    $profile->logo_url = asset('storage/' . $profile->logo);
                }
            }
        }

        // Cek role user yang login (asumsi ada field role atau menggunakan gate)
        $user = Auth::user();
        $isAdmin = $user && ($user->role === 'admin' || $user->is_admin ?? false);
    @endphp

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <img src="{{ $profile->logo_url ?? asset('images/default-logo.png') }}"
                alt="Logo {{ $profile->name ?? 'Toko' }}"
                class="w-12 h-12 rounded-full object-cover border-1 border-gray-300 dark:border-gray-700"
                onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';">
            <span class="text-lg font-bold text-gray-800 dark:text-white ml-2">{{ $profile->name ?? 'Toko' }}</span>
        </div>
        <button
            class="md:hidden text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 transition-colors"
            onclick="toggleSidebar()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto py-4 scrollbar-thin">
        <nav class="px-2 space-y-1">


            <!-- Master Data - Hanya untuk admin -->
            @if($isAdmin)

                <!-- Dashboard - Selalu tampil untuk semua user -->
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mx-1 
                              {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>
                    Dashboard
                </a>

                <div class="mt-4">
                    <button onclick="toggleDropdown('master-data-dropdown', 'master-data-arrow')"
                        class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-database mr-3 w-5 text-center"></i>
                            Master Data
                        </div>
                        <svg id="master-data-arrow" class="w-3 h-3 transition-transform duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="master-data-dropdown" class="hidden mt-1 ml-8 space-y-1">
                        <a href="{{ route('store-profile.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('store-profile.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-store mr-3 w-5 text-center"></i>
                            <span>Toko</span>
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('users.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-users mr-3 w-5 text-center"></i>
                            <span>Pengguna</span>
                        </a>
                        <a href="{{ route('categories.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('categories.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-tags mr-3 w-5 text-center"></i>
                            <span>Kategori</span>
                        </a>
                        <a href="{{ route('suppliers.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('suppliers.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-truck mr-3 w-5 text-center"></i>
                            <span>Supplier</span>
                        </a>
                        <a href="{{ route('products.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('products.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-boxes mr-3 w-5 text-center"></i>
                            <span>Produk</span>
                        </a>
                        <a href="{{ route('payment.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('payment.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-credit-card mr-3 w-5 text-center"></i>
                            <span>Payment</span>
                        </a>
                        <a href="{{ route('discounts.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('discounts.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-tags mr-3 w-5 text-center"></i>
                            <span>Diskon & Promo</span>
                        </a>
                        <a href="{{ route('purchase-orders.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('purchase-orders.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-truck-loading mr-3 w-5 text-center"></i>
                            <span>Purchase Order</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Transaksi - Tampil untuk semua user -->
            <div class="mt-1">
                <button onclick="toggleDropdown('transaksi-dropdown', 'transaksi-arrow')"
                    class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-exchange-alt mr-3 w-5 text-center"></i>
                        Transaksi
                    </div>
                    <svg id="transaksi-arrow" class="w-3 h-3 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="transaksi-dropdown" class="hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('cashier.index') }}"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('cashier.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-cash-register mr-3 w-5 text-center"></i>
                        <span>Kasir</span>
                    </a>
                    <a href="{{ route('customers.index') }}"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('customers.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-user-tag mr-3 w-5 text-center"></i>
                        <span>Pelanggan</span>
                    </a>
                    <a href="{{ route('shifts.index') }}"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('shifts.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-business-time mr-3 w-5 text-center"></i>
                        <span>Shift Kasir</span>
                    </a>
                    <a href="{{ route('expenses.index') }}"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('expenses.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i>
                        <span>Pengeluaran</span>
                    </a>
                    <a href="{{ route('returns.index') }}"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('returns.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-undo mr-3 w-5 text-center"></i>
                        <span>Return</span>
                    </a>
                </div>
            </div>

            <!-- Laporan - Tampil untuk semua user -->
            @if ($isAdmin)
                <div class="mt-1">
                    <button onclick="toggleDropdown('laporan-dropdown', 'laporan-arrow')"
                        class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                            Laporan
                        </div>
                        <svg id="laporan-arrow" class="w-3 h-3 transition-transform duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="laporan-dropdown" class="hidden mt-1 ml-8 space-y-1">
                        <a href="{{ route('reports.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('reports.index') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i>
                            <span>Laporan Pesanan</span>
                        </a>
                        <a href="{{ route('reports.financial') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('reports.financial') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('activity-logs.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-history mr-3 w-5 text-center"></i>
                            <span>Log Aktivitas</span>
                        </a>
                        <a href="{{ route('low-stock.index') }}"
                            class="group flex items-center px-3 py-2 text-sm rounded-lg {{ request()->routeIs('low-stock.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-exclamation-triangle mr-3 w-5 text-center text-yellow-500"></i>
                            <span>Peringatan Stok</span>
                        </a>
                    </div>
                </div>
            @endif

        </nav>

        @if (@$isAdmin)
            <!-- Settings Section -->
            <div class="mt-auto px-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Pengaturan
                </h3>
                <nav class="space-y-1">
                    <a href="#"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-user-cog mr-3 w-5 text-center"></i>
                        <span>Profil</span>
                    </a>
                    <a href="#"
                        class="group flex items-center px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog mr-3 w-5 text-center"></i>
                        <span>Pengaturan</span>
                    </a>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-3 py-2 text-sm rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>

        @elseif (Auth::check())
            <!-- Logout Button for non-admin users -->
            <div class="mt-auto px-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-3 py-2 text-sm rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        @endif

    </div>
</div>

@push('scripts')
    <script>
        function toggleDropdown(dropdownId, arrowId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(arrowId);

            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Buka dropdown berdasarkan route aktif
        document.addEventListener('DOMContentLoaded', function () {
            const currentRoute = '{{ request()->route()->getName() }}';

            // Master Data routes
            @if($isAdmin)
                if (currentRoute && (currentRoute.startsWith('store-profile') ||
                    currentRoute.startsWith('users') ||
                    currentRoute.startsWith('categories') ||
                    currentRoute.startsWith('suppliers') ||
                    currentRoute.startsWith('products') ||
                    currentRoute.startsWith('payment'))) {
                    const dropdown = document.getElementById('master-data-dropdown');
                    const arrow = document.getElementById('master-data-arrow');
                    if (dropdown && arrow) {
                        dropdown.classList.remove('hidden');
                        arrow.style.transform = 'rotate(180deg)';
                    }
                }
            @endif

                // Transaksi routes
                if (currentRoute && (currentRoute.startsWith('cashier') ||
                currentRoute.startsWith('expenses') ||
                currentRoute.startsWith('returns'))) {
                const dropdown = document.getElementById('transaksi-dropdown');
                const arrow = document.getElementById('transaksi-arrow');
                if (dropdown && arrow) {
                    dropdown.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                }
            }

            // Laporan routes
            if (currentRoute && (currentRoute.startsWith('reports'))) {
                const dropdown = document.getElementById('laporan-dropdown');
                const arrow = document.getElementById('laporan-arrow');
                if (dropdown && arrow) {
                    dropdown.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                }
            }
        });

        // Fungsi untuk toggle sidebar di mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 20px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4a5568;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        /* Active menu indicator */
        .bg-primary-600 {
            background-color: #44A194;
        }

        .bg-primary-600:hover {
            background-color: #2c7a6e;
        }

        .text-primary-600 {
            color: #44A194;
        }

        /* Smooth transitions */
        .transition-transform {
            transition: transform 0.2s ease-in-out;
        }

        /* Hover effects */
        .hover\:bg-gray-100:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .hover\:bg-gray-700:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
@endpush