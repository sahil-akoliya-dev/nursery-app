<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Green Haven</title>
    
    <!-- Admin-specific CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Admin-specific color scheme - more professional/utilitarian */
        :root {
            --admin-primary: #1e40af;
            --admin-secondary: #3b82f6;
            --admin-accent: #f59e0b;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-dark: #1f2937;
            --admin-light: #f8fafc;
        }
        
        /* Admin-specific animations */
        .admin-sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .admin-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Admin-specific glass effect */
        .admin-glass {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Admin navigation active states */
        .admin-nav-active {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
        }
        
        /* Admin-specific form styling */
        .admin-input {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
        }
        
        .admin-btn-primary {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl;
        }
        
        .admin-btn-secondary {
            @apply bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 border border-gray-300;
        }
        
        .admin-btn-danger {
            @apply bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl;
        }
        
        /* Admin-specific table styling */
        .admin-table {
            @apply w-full bg-white rounded-lg shadow-sm overflow-hidden;
        }
        
        .admin-table th {
            @apply bg-gray-50 px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
        }
        
        .admin-table td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
        }
        
        /* Admin-specific status badges */
        .status-badge {
            @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
        }
        
        .status-active { @apply bg-green-100 text-green-800; }
        .status-inactive { @apply bg-red-100 text-red-800; }
        .status-pending { @apply bg-yellow-100 text-yellow-800; }
        .status-completed { @apply bg-blue-100 text-blue-800; }
    </style>
    
    @stack('styles')
</head>

<body class="h-full bg-gray-50" x-data="{ sidebarOpen: false, userMenuOpen: false }">
    <!-- Admin Top Bar -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-900 text-white py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-6">
                    <span><i class="fas fa-shield-alt mr-2"></i>Admin Panel</span>
                    <span><i class="fas fa-clock mr-2"></i>{{ now()->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="hidden md:inline">Secure Admin Access</span>
                    <div class="flex space-x-2">
                        <span class="bg-green-500 px-2 py-1 rounded text-xs">2FA Enabled</span>
                        <span class="bg-blue-500 px-2 py-1 rounded text-xs">Audit Logged</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen">
        <!-- Admin Sidebar -->
        <div class="admin-sidebar-transition bg-white shadow-lg w-64 flex-shrink-0" 
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0"
             x-transition>
            <div class="p-6">
                <!-- Admin Logo -->
                <div class="flex items-center mb-8">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cogs text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Green Haven</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>

                <!-- Admin Navigation -->
                <nav class="space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-chart-line mr-3"></i>
                        Dashboard
                    </a>

                    <!-- Products -->
                    <div x-data="{ open: {{ request()->routeIs('admin.products*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-seedling mr-3"></i>
                                Products
                            </div>
                            <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('admin.products.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">All Products</a>
                            <a href="{{ route('admin.products.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">Add Product</a>
                        </div>
                    </div>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.orders*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        Orders
                    </a>

                    <!-- Customers -->
                    <a href="{{ route('admin.customers') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.customers*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Customers
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.categories*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-tags mr-3"></i>
                        Categories
                    </a>

                    <!-- Analytics -->
                    <div x-data="{ open: {{ request()->routeIs('admin.analytics*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-chart-bar mr-3"></i>
                                Analytics
                            </div>
                            <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('admin.analytics') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">Overview</a>
                            <a href="{{ route('admin.analytics.sales') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">Sales Report</a>
                            <a href="{{ route('admin.analytics.customers') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">Customers</a>
                            <a href="{{ route('admin.analytics.inventory') }}" 
                               class="block px-4 py-2 text-sm text-gray-600 rounded hover:bg-blue-50 hover:text-blue-600">Inventory</a>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <a href="{{ route('admin.reviews.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.reviews*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-star mr-3"></i>
                        Reviews
                    </a>

                    <!-- Database -->
                    <a href="{{ route('admin.database.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.database*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-database mr-3"></i>
                        Database
                    </a>

                    <!-- Audit Logs -->
                    <a href="{{ route('admin.audit-logs') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.audit-logs*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Audit Logs
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.settings*') ? 'admin-nav-active' : '' }}">
                        <i class="fas fa-cog mr-3"></i>
                        Settings
                    </a>
                </nav>

                <!-- Admin Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        <p><i class="fas fa-shield-alt mr-1"></i> Secure Admin Panel</p>
                        <p><i class="fas fa-clock mr-1"></i> Session: {{ now()->diffForHumans() }}</p>
                        <p><i class="fas fa-user-shield mr-1"></i> Role: {{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Admin Header -->
            <header class="admin-glass shadow-sm">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-bars"></i>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Admin Dashboard')</h1>
                            @hasSection('breadcrumbs')
                            <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-1">
                                @yield('breadcrumbs')
                            </nav>
                            @endif
                        </div>

                        <!-- Admin User Menu -->
                        <div class="flex items-center space-x-4" x-data="{ userMenuOpen: false }">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                                <i class="fas fa-bell"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>

                            <!-- User Menu -->
                            <div class="relative">
                                <button @click="userMenuOpen = !userMenuOpen" 
                                        class="flex items-center space-x-3 p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Administrator' }}</p>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="userMenuOpen" 
                                     @click.away="userMenuOpen = false"
                                     x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Settings
                                    </a>
                                    <a href="{{ route('admin.audit-logs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-clipboard-list mr-2"></i>Audit Logs
                                    </a>
                                    <div class="border-t border-gray-200"></div>
                                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-external-link-alt mr-2"></i>View Site
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Admin Content -->
            <main class="flex-1 overflow-auto p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Admin-specific JavaScript -->
    <script>
        // Admin-specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages
            setTimeout(function() {
                const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
                flashMessages.forEach(function(msg) {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(function() {
                        msg.remove();
                    }, 500);
                });
            }, 5000);

            // Admin confirmation dialogs
            window.adminConfirm = function(message, callback) {
                if (confirm(message)) {
                    callback();
                }
            };

            // Admin loading states
            window.showAdminLoading = function() {
                const loader = document.createElement('div');
                loader.id = 'admin-loader';
                loader.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                loader.innerHTML = '<div class="bg-white rounded-lg p-6 flex items-center space-x-3"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><span>Processing...</span></div>';
                document.body.appendChild(loader);
            };

            window.hideAdminLoading = function() {
                const loader = document.getElementById('admin-loader');
                if (loader) {
                    loader.remove();
                }
            };
        });
    </script>

    @stack('scripts')
</body>
</html>

