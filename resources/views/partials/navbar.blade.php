<div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
    <!-- Left side - Mobile menu button -->
    <div class="flex items-center">
        <button class="md:hidden text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 mr-2" onclick="toggleSidebar()">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="hidden md:flex items-center">
            
            
        </div>
    </div>

    <!-- Right side - User menu -->
    <div class="flex items-center space-x-4">
        <!-- Dark mode toggle -->
        <button id="darkModeToggle" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-moon dark:hidden text-gray-600"></i>
            <i class="fas fa-sun hidden dark:block text-yellow-300"></i>
        </button>

        <!-- Notifications -->
        <div class="relative">
            <button id="notificationButton" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 relative">
                <i class="fas fa-bell text-gray-600 dark:text-gray-300"></i>
                <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-gray-800 hidden">
                    0
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-72 rounded-2xl shadow-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-wider">Notifikasi</h3>
                    <span id="notifCountLabel" class="text-[10px] bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-full font-bold">0 Baru</span>
                </div>
                <div id="notificationList" class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700/50">
                    <!-- Notifications will be injected here via JS -->
                    <div class="p-6 text-center text-gray-400">
                        <i class="fas fa-bell-slash text-2xl mb-2 block opacity-20"></i>
                        <p class="text-xs">Tidak ada notifikasi baru</p>
                    </div>
                </div>
                <div class="px-4 py-2 bg-gray-50/50 dark:bg-gray-700/30 text-center border-t border-gray-100 dark:border-gray-700">
                    <button onclick="toggleDropdown('notificationDropdown')" class="text-[10px] text-gray-500 hover:text-emerald-500 font-medium">Tutup</button>
                </div>
            </div>
        </div>

        <!-- User dropdown -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center text-sm rounded-full focus:outline-none">
                <img class="h-8 w-8 rounded-full object-cover" 
                     src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                     alt="User profile">
                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium hidden md:inline">{{ Auth::user()->name ?? 'User' }}</span>
                <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs hidden md:inline"></i>
            </button>

            <!-- Dropdown menu -->
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 py-1 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <i class="fas fa-user-circle mr-2"></i> Profil
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <i class="fas fa-cog mr-2"></i> Pengaturan
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
