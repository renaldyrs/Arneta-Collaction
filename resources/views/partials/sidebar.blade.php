<div class="flex pt-16">
        <!-- Sidebar - Mobile Overlay -->
        <div id="sidebar-mobile" class="md:hidden fixed inset-0 z-30 bg-gray-800 bg-opacity-75 hidden transition-opacity duration-300 ease-in-out opacity-0">
            <!-- Sidebar - Mobile Content -->
            <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full">
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-rocket text-xl text-primary-600 dark:text-primary-400 mr-2"></i>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">Arneta Collection</span>
                    </div>
                    <button id="close-sidebar"
                        class="p-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="h-[calc(100%-4rem)] overflow-y-auto py-4 px-2">
                    <!-- Mobile Navigation -->
                    <nav class="space-y-1">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mx-2 text-white bg-primary-600 dark:bg-primary-700">
                            <i class="fas fa-home mr-3 w-5 text-center"></i>
                            Dashboard
                        </a>
                        
                        <!-- Master Data Section -->
                        <div class="mt-4 px-2">
                            <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleMobileDropdown('mobile-master-data-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-database mr-3 w-5 text-center"></i>
                                    Master Data
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="mobile-master-data-arrow"></i>
                            </div>
                            
                            <div id="mobile-master-data-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('store-profile.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-store mr-3 w-5 text-center"></i> Toko
                                </a>
                                <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-users mr-3 w-5 text-center"></i> Pengguna
                                </a>
                                <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-tags mr-3 w-5 text-center"></i> Kategori
                                </a>
                                <a href="{{ route('suppliers.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-truck mr-3 w-5 text-center"></i> Supplier
                                </a>
                                <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-boxes mr-3 w-5 text-center"></i> Produk
                                </a>
                                <a href="{{ route('payment.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-credit-card mr-3 w-5 text-center"></i> Payment
                                </a>
                            </div>
                        </div>
                        
                        <!-- Transaksi Section -->
                        <div class="mt-1 px-2">
                            <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleMobileDropdown('mobile-transaksi-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-exchange-alt mr-3 w-5 text-center"></i>
                                    Transaksi
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="mobile-transaksi-arrow"></i>
                            </div>
                            
                            <div id="mobile-transaksi-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('cashier.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-cash-register mr-3 w-5 text-center"></i> Kasir
                                </a>
                                <a href="{{ route('expenses.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i> Pengeluaran
                                </a>
                                <a href="{{ route('returns.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-undo mr-3 w-5 text-center"></i> Return
                                </a>
                            </div>
                        </div>
                        
                        <!-- Laporan Section -->
                        <div class="mt-1 px-2">
                            <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleMobileDropdown('mobile-laporan-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                                    Laporan
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="mobile-laporan-arrow"></i>
                            </div>
                            
                            <div id="mobile-laporan-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('reports.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i> Laporan Pesanan
                                </a>
                                <a href="{{ route('reports.financial') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i> Laporan Keuangan
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Settings Section -->
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 px-4">
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Pengaturan</h3>
                        <nav class="space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-user-cog mr-3 w-5 text-center"></i> Profil
                            </a>
                            <a href="#" class="block px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 w-5 text-center"></i> Pengaturan
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="w-64 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 fixed h-full z-10 shadow-lg border-r border-gray-200 dark:border-gray-700">
                
                
                <div class="h-[calc(100%-4rem)] overflow-y-auto py-4">
                    <nav class="space-y-1 px-2">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mx-2 text-white bg-primary-600 dark:bg-primary-700">
                            <i class="fas fa-home mr-3 w-5 text-center"></i>
                            Dashboard
                        </a>
                        
                        <!-- Master Data Section -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleDropdown('master-data-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-database mr-3 w-5 text-center"></i>
                                    Master Data
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="master-data-arrow"></i>
                            </div>
                            
                            <div id="master-data-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('store-profile.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-store mr-3 w-5 text-center"></i> Toko
                                </a>
                                <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-users mr-3 w-5 text-center"></i> Pengguna
                                </a>
                                <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-tags mr-3 w-5 text-center"></i> Kategori
                                </a>
                                <a href="{{ route('suppliers.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-truck mr-3 w-5 text-center"></i> Supplier
                                </a>
                                <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-boxes mr-3 w-5 text-center"></i> Produk
                                </a>
                                <a href="{{ route('payment.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-credit-card mr-3 w-5 text-center"></i> Payment
                                </a>
                            </div>
                        </div>
                        
                        <!-- Transaksi Section -->
                        <div class="mt-1">
                            <div class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleDropdown('transaksi-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-exchange-alt mr-3 w-5 text-center"></i>
                                    Transaksi
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="transaksi-arrow"></i>
                            </div>
                            
                            <div id="transaksi-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('cashier.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-cash-register mr-3 w-5 text-center"></i> Kasir
                                </a>
                                <a href="{{ route('expenses.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i> Pengeluaran
                                </a>
                                <a href="{{ route('returns.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-undo mr-3 w-5 text-center"></i> Return
                                </a>
                            </div>
                        </div>
                        
                        <!-- Laporan Section -->
                        <div class="mt-1">
                            <div class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="toggleDropdown('laporan-dropdown')">
                                <div class="flex items-center">
                                    <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                                    Laporan
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="laporan-arrow"></i>
                            </div>
                            
                            <div id="laporan-dropdown" class="hidden mt-1 ml-8 space-y-1">
                                <a href="{{ route('reports.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i> Laporan Pesanan
                                </a>
                                <a href="{{ route('reports.financial') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i> Laporan Keuangan
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Settings Section -->
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 px-4">
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Pengaturan</h3>
                        <nav class="space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-user-cog mr-3 w-5 text-center"></i> Profil
                            </a>
                            <a href="#" class="block px-3 py-2 text-sm rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 w-5 text-center"></i> Pengaturan
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>