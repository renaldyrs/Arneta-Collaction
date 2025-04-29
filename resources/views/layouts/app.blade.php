<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arneta Collection</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Mobile Sidebar Overlay - Improved -->
    <div id="sidebarOverlay" 
         class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity duration-300 ease-in-out opacity-0"
         onclick="toggleSidebar()"></div>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed md:relative w-64 h-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="h-full overflow-y-auto">
                @include('partials.sidebar')
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm z-30">
                @include('partials.navbar')
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto pt-16 md:pt-0 px-4 md:pl-4 bg-gray-50 dark:bg-gray-800">
                <div class="container mx-auto py-6">
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-4">
                @include('partials.footer')
            </footer>
        </div>
    </div>

    <script>
        // Improved toggle sidebar function with overlay animation
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Opening sidebar
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                sidebar.classList.remove('-translate-x-full');
                document.body.classList.add('overflow-hidden');
            } else {
                // Closing sidebar
                overlay.classList.remove('opacity-100');
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Initialize sidebar based on screen size
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            } else {
                sidebar.classList.remove('-translate-x-full');
            }
        }

        // Initialize on load and resize
        document.addEventListener('DOMContentLoaded', function() {
            initSidebar();
            window.addEventListener('resize', initSidebar);
        });
    </script>
</body>
</html>