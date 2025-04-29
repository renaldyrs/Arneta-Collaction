<div class="flex flex-col h-full">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <i class="fas fa-store text-xl text-primary-600 dark:text-primary-400 mr-2"></i>
            <span class="text-lg font-bold text-gray-800 dark:text-white">Arneta Collection</span>
        </div>
        <button class="md:hidden text-gray-500 dark:text-gray-300" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-2 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mx-1 text-white bg-primary-600 dark:bg-primary-700">
                <i class="fas fa-home mr-3 w-5 text-center"></i>
                Dashboard
            </a>

            <!-- Master Data -->
            <div class="mt-4">
                <button onclick="toggleDropdown('master-data-dropdown', 'master-data-arrow')" class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-database mr-3 w-5 text-center"></i>
                        Master Data
                    </div>
                    <i id="master-data-arrow" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div id="master-data-dropdown" class="hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('store-profile.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-store mr-3 w-5 text-center"></i> Toko
                    </a>
                    <a href="{{ route('users.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-users mr-3 w-5 text-center"></i> Pengguna
                    </a>
                    <a href="{{ route('categories.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-tags mr-3 w-5 text-center"></i> Kategori
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-truck mr-3 w-5 text-center"></i> Supplier
                    </a>
                    <a href="{{ route('products.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-boxes mr-3 w-5 text-center"></i> Produk
                    </a>
                    <a href="{{ route('payment.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-credit-card mr-3 w-5 text-center"></i> Payment
                    </a>
                </div>
            </div>

            <!-- Transaksi -->
            <div class="mt-1">
                <button onclick="toggleDropdown('transaksi-dropdown', 'transaksi-arrow')" class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-exchange-alt mr-3 w-5 text-center"></i>
                        Transaksi
                    </div>
                    <i id="transaksi-arrow" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div id="transaksi-dropdown" class="hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('cashier.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cash-register mr-3 w-5 text-center"></i> Kasir
                    </a>
                    <a href="{{ route('expenses.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i> Pengeluaran
                    </a>
                    <a href="{{ route('returns.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-undo mr-3 w-5 text-center"></i> Return
                    </a>
                </div>
            </div>

            <!-- Laporan -->
            <div class="mt-1">
                <button onclick="toggleDropdown('laporan-dropdown', 'laporan-arrow')" class="group flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                        Laporan
                    </div>
                    <i id="laporan-arrow" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div id="laporan-dropdown" class="hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('reports.index') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i> Laporan Pesanan
                    </a>
                    <a href="{{ route('reports.financial') }}" class="group flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i> Laporan Keuangan
                    </a>
                </div>
            </div>
        </nav>

        <!-- Settings Section -->
        <div class="mt-auto px-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                Pengaturan</h3>
            <nav class="space-y-1">
                <a href="#" class="group flex items-center px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-user-cog mr-3 w-5 text-center"></i> Profil
                </a>
                <a href="#" class="group flex items-center px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-cog mr-3 w-5 text-center"></i> Pengaturan
                </a>
            </nav>
        </div>
    </div>
</div>