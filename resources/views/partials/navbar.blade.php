<div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
    <!-- Left side - Mobile menu button -->
    <div class="flex items-center">
        <button class="md:hidden text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 mr-2" onclick="toggleSidebar()">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="hidden md:flex items-center">
            <i class="fas fa-store text-xl text-primary-600 dark:text-primary-400 mr-2"></i>
            <span class="text-xl font-bold text-gray-800 dark:text-white">Arneta Collection</span>
        </div>
    </div>

    <!-- Right side - User menu -->
    <div class="flex items-center space-x-4">
        <!-- Dark mode toggle -->
        <button id="darkModeToggle" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-moon dark:hidden text-gray-600"></i>
            <i class="fas fa-sun hidden dark:block text-yellow-300"></i>
        </button>

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

<script>
    // Toggle dropdown function
    function toggleDropdown(dropdownId, arrowId = null) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
        
        if (arrowId) {
            const arrow = document.getElementById(arrowId);
            arrow.classList.toggle('rotate-180');
        }
    }

    // Initialize user dropdown
    document.getElementById('userMenuButton').addEventListener('click', function() {
        toggleDropdown('userDropdown');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#userMenuButton') && !document.getElementById('userDropdown').classList.contains('hidden')) {
            document.getElementById('userDropdown').classList.add('hidden');
        }
    });

    // Dark mode toggle
    document.getElementById('darkModeToggle').addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    });
</script>