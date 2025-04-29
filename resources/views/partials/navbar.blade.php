<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar & Sidebar Responsif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 
                            "50": "#eff6ff", 
                            "100": "#dbeafe", 
                            "200": "#bfdbfe", 
                            "300": "#93c5fd", 
                            "400": "#60a5fa", 
                            "500": "#3b82f6", 
                            "600": "#2563eb", 
                            "700": "#1d4ed8", 
                            "800": "#1e40af", 
                            "900": "#1e3a8a" 
                        }
                    },
                    transitionDuration: {
                        '250': '250ms'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg fixed w-full z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Mobile Menu Button -->
                <div class="flex items-center">
                    <button id="mobile-menu-button"
                        class="md:hidden text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition-colors duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex-shrink-0 flex items-center ml-4 md:ml-0">
                    @php
                    $defaultLogo = asset('images/default-logo.png');
                    $logoUrl = $defaultLogo;

                    if (!empty($profile->logo)) {
                        $logoUrl = $profile->logo; // Langsung gunakan value dari database
                    }
                @endphp

                <img src="{{ $logoUrl }}" alt="Logo Toko" class="h-8 w-8 rounded-full object-cover"
                    onerror="this.onerror=null;this.src='{{ $defaultLogo }}'" id="store-logo">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">{{ DB::table('store_profiles')->value('name') }}</span>
                    </div>
                </div>

                <!-- Right Side Items -->
                <div class="flex items-center space-x-4">
                    <button id="theme-toggle"
                        class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white focus:outline-none rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>
                    
                    <div class="relative ml-2">
                        <button id="user-menu-button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            <img class="h-8 w-8 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="Profil">
                            <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium hidden md:inline">{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs hidden md:inline"></i>
                        </button>
                        
                        <!-- User dropdown menu -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 py-1 z-30">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    

    <script>
        // Toggle mobile sidebar
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebarContent = sidebarMobile.querySelector('div > div');

        mobileMenuButton.addEventListener('click', () => {
            sidebarMobile.classList.remove('hidden');
            setTimeout(() => {
                sidebarMobile.classList.add('opacity-100');
                sidebarContent.classList.remove('-translate-x-full');
            }, 10);
        });

        closeSidebar.addEventListener('click', () => {
            sidebarMobile.classList.remove('opacity-100');
            sidebarContent.classList.add('-translate-x-full');
            setTimeout(() => {
                sidebarMobile.classList.add('hidden');
            }, 300);
        });

        // Toggle dark mode
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.querySelector('html');

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Check for saved theme preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        // Toggle dropdowns for desktop
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
            
            // Rotate arrow icon
            const arrowId = id.replace('-dropdown', '-arrow');
            const arrow = document.getElementById(arrowId);
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        // Toggle dropdowns for mobile
        function toggleMobileDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
            
            // Rotate arrow icon
            const arrowId = id.replace('-dropdown', '-arrow');
            const arrow = document.getElementById(arrowId);
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        // User dropdown menu
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // User dropdown
            if (!event.target.closest('#user-menu-button') && !userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
            
            // Mobile sidebar
            if (!event.target.closest('#mobile-menu-button') && 
                !event.target.closest('#sidebar-mobile > div') && 
                !sidebarMobile.classList.contains('hidden')) {
                closeSidebar.click();
            }
        });
    </script>
</body>
</html>