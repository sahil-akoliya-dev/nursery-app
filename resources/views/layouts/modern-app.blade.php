<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Green Haven - Premium Plant Nursery')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cpath fill='%2310b981' d='M16 2c5 0 9 4 9 9 0 7-6 14-9 19-3-5-9-12-9-19 0-5 4-9 9-9zm0 4c-2.8 0-5 2.2-5 5 0 3.9 3.1 7.9 5 10.6 1.9-2.7 5-6.7 5-10.6 0-2.8-2.2-5-5-5z'/%3E%3C/svg%3E" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Tailwind config must be before CSS files
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Playfair Display', 'serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        secondary: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        },
                        accent: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        neutral: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 0.6s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceSubtle: {
                            '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0)' },
                            '40%': { transform: 'translateY(-4px)' },
                            '60%': { transform: 'translateY(-2px)' }
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'large': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'colored': '0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05)',
                    }
                }
            }
        }
    </script>
    <noscript>
        <!-- Fallback if JavaScript is disabled -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css">
    </noscript>
    <!-- Design System - Load in order -->
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/professional-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- Modern Animations & Effects -->
    <link rel="stylesheet" href="{{ asset('css/modern-animations.css') }}">
    <!-- Advanced Animations -->
    <link rel="stylesheet" href="{{ asset('css/advanced-animations.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-secondary: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            --gradient-accent: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            --text-gradient: linear-gradient(135deg, #10b981, #059669);
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        .text-gradient {
            background: var(--text-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .bg-gradient-primary {
            background: var(--gradient-primary);
        }
        
        .bg-gradient-secondary {
            background: var(--gradient-secondary);
        }
        
        .bg-gradient-accent {
            background: var(--gradient-accent);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .search-input {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        
        .search-input:focus {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1), 0 4px 20px -5px rgba(0, 0, 0, 0.1);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .border-gradient {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        var(--gradient-primary) border-box;
        }
        
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .notification-slide {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        .breadcrumb-item {
            position: relative;
        }
        
        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            position: absolute;
            right: -12px;
            color: #9ca3af;
            font-weight: 300;
        }
        
        .filter-chip {
            transition: all 0.3s ease;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .filter-chip.active {
            background: var(--gradient-primary);
            color: white;
            transform: scale(1.05);
        }
        
        .filter-chip:hover {
            background: rgba(16, 185, 129, 0.2);
            transform: scale(1.02);
        }
        
        .rating-star {
            transition: all 0.2s ease;
        }
        
        .rating-star:hover {
            transform: scale(1.2);
        }
        
        .price-highlight {
            position: relative;
            overflow: hidden;
        }
        
        .price-highlight::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .price-highlight:hover::before {
            left: 100%;
        }
        
        /* Responsive Design Enhancements */
        @media (max-width: 768px) {
            .product-card:hover {
                transform: translateY(-4px) scale(1.01);
            }
            
            .btn-primary:hover {
                transform: translateY(-1px);
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .glass-effect {
                background: rgba(31, 41, 55, 0.95);
                border: 1px solid rgba(75, 85, 99, 0.2);
            }
        }
    </style>
    
    @yield('styles')
</head>

<body class="antialiased">
    <!-- Top Announcement Bar -->
    <div class="bg-gradient-primary text-white py-2 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <span class="flex items-center">
                        <i class="fas fa-truck mr-2"></i>
                        Free shipping on orders over $50
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-leaf mr-2"></i>
                        Expert plant care advice
                    </span>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="flex items-center">
                        <i class="fas fa-phone mr-2"></i>
                        +1 (555) 123-4567
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        hello@greenhaven.com
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation - Professional Grade -->
    <nav class="nav-pro" x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = window.scrollY > 50"
         :class="{ 'nav-pro-scrolled': scrolled }">
        <div class="container-pro">
            <div class="flex justify-between items-center h-20">
                <!-- Logo - Professional -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-xl font-display font-bold text-neutral-900">Green Haven</div>
                            <div class="text-xs text-neutral-500">Premium Plants</div>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="{{ route('home') }}" class="nav-link-pro {{ request()->is('/') ? 'active' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link-pro {{ request()->is('products*') ? 'active' : '' }}">
                            Products
                        </a>
                        <a href="{{ route('plants.index') }}" class="nav-link-pro {{ request()->is('plants*') ? 'active' : '' }}">
                            Plants
                        </a>
                        <a href="{{ route('plant-care.index') }}" class="nav-link-pro {{ request()->is('plant-care*') ? 'active' : '' }}">
                            Care
                        </a>
                        <a href="{{ route('reviews.index') }}" class="nav-link-pro {{ request()->is('reviews*') ? 'active' : '' }}">
                            Reviews
                        </a>
                        <a href="{{ route('about') }}" class="nav-link-pro">
                            About
                        </a>
                    </div>
                </div>
                
                <!-- Search & Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar - Professional -->
                    <div class="hidden md:block">
                        <div class="input-pro-group">
                            <i class="input-pro-icon fas fa-search"></i>
                            <input type="text" 
                                   placeholder="Search plants..." 
                                   class="input-pro w-64 pl-12">
                        </div>
                    </div>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="ripple click-scale relative p-3 text-neutral-700 hover:text-primary-600 transition-all duration-300 hover:bg-primary-50 rounded-full hover-lift">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-gradient-primary text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-semibold cart-count badge-pulse">
                            @auth
                                {{ \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity') ?? 0 }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                    
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="ripple click-scale relative p-3 text-neutral-700 hover:text-red-500 transition-all duration-300 hover:bg-red-50 rounded-full hover-lift">
                        <i class="fas fa-heart text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-semibold wishlist-count badge-pulse">
                            @auth
                                {{ \App\Models\WishlistItem::where('user_id', Auth::id())->count() ?? 0 }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                    
                    <!-- User Menu -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    class="flex items-center space-x-2 p-2 text-neutral-700 hover:text-primary-600 transition-all duration-300 hover:bg-primary-50 rounded-full">
                                <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"></i>
                            </button>
                            
                            <div x-show="userMenuOpen" 
                                 @click.away="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 glass-effect rounded-xl shadow-large py-2 z-50 border border-white/20">
                                
                                <div class="px-4 py-3 border-b border-neutral-200/50">
                                    <p class="text-sm font-medium text-neutral-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-neutral-500">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-neutral-700 hover:bg-primary-50 transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-3 text-primary-500"></i>
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-neutral-700 hover:bg-primary-50 transition-colors duration-200">
                                    <i class="fas fa-user mr-3 text-primary-500"></i>
                                    Profile Settings
                                </a>
                                
                                <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-3 text-sm text-neutral-700 hover:bg-primary-50 transition-colors duration-200">
                                    <i class="fas fa-box mr-3 text-primary-500"></i>
                                    My Orders
                                </a>
                                
                                @if(Auth::user()->email === 'admin@greenhaven.com')
                                    <div class="border-t border-neutral-200/50 my-2"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-neutral-700 hover:bg-primary-50 transition-colors duration-200">
                                        <i class="fas fa-cog mr-3 text-primary-500"></i>
                                        Admin Panel
                                    </a>
                                @endif
                                
                                <div class="border-t border-neutral-200/50 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden md:flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-neutral-700 hover:text-primary-600 px-4 py-2 text-sm font-medium transition-colors duration-200">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2 rounded-full text-sm font-semibold">
                                Get Started
                            </a>
                        </div>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden p-2 text-neutral-700 hover:text-primary-600 transition-colors duration-200">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="lg:hidden glass-effect border-t border-white/20">
            <div class="px-4 py-4 space-y-3">
                <!-- Mobile Search -->
                <div class="relative mb-4">
                    <input type="text" 
                           placeholder="Search plants..." 
                           class="w-full pl-12 pr-4 py-3 rounded-full search-input border border-neutral-200 focus:outline-none focus:ring-0">
                    <i class="fas fa-search absolute left-4 top-4 text-neutral-400"></i>
                </div>
                
                <a href="{{ route('home') }}" class="block px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200 {{ request()->is('/') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200 {{ request()->is('products*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Products
                </a>
                <a href="{{ route('plants.index') }}" class="block px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200 {{ request()->is('plants*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Plants
                </a>
                <a href="{{ route('plant-care.index') }}" class="block px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200">
                    Plant Care
                </a>
                <a href="{{ route('about') }}" onclick="handleAboutClick()" class="block px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200 cursor-pointer">
                    About
                </a>
                
                @guest
                    <div class="pt-4 border-t border-neutral-200/50 space-y-2">
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 text-neutral-700 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center btn-primary text-white px-4 py-3 rounded-lg text-sm font-semibold">
                            Get Started
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Breadcrumbs -->
    @hasSection('breadcrumbs')
    <div class="bg-neutral-50 border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                @yield('breadcrumbs')
            </nav>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Newsletter Section -->
    @if(!request()->is('login') && !request()->is('register') && !request()->is('forgot-password') && !request()->is('reset-password*') && !request()->is('verify-email') && !request()->is('confirm-password'))
    <section class="bg-gradient-primary py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full floating-animation"></div>
            <div class="absolute top-20 right-20 w-16 h-16 bg-white/5 rounded-full floating-animation" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-10 left-1/4 w-12 h-12 bg-white/10 rounded-full floating-animation" style="animation-delay: 4s;"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-white mb-6 text-shadow">
                Stay Connected with Green Haven
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed">
                Get expert plant care tips, exclusive offers, and early access to new arrivals. Join our community of plant lovers!
            </p>
            
            <div class="max-w-md mx-auto flex bg-white/10 backdrop-blur-sm rounded-full p-1 border border-white/20">
                <input type="email" 
                       placeholder="Enter your email address" 
                       class="flex-1 px-6 py-4 rounded-full bg-transparent text-white placeholder-white/70 focus:outline-none focus:ring-0">
                <button class="bg-white text-primary-600 px-8 py-4 rounded-full font-semibold hover:bg-white/90 transition-all duration-300 hover:scale-105">
                    Subscribe
                </button>
            </div>
            
            <p class="text-white/70 text-sm mt-4">
                No spam, just plant love. Unsubscribe anytime.
            </p>
        </div>
    </section>
    @endif

    <!-- Footer - Professional Design -->
    @if(!request()->is('login') && !request()->is('register') && !request()->is('forgot-password') && !request()->is('reset-password*') && !request()->is('verify-email') && !request()->is('confirm-password'))
    <footer class="bg-neutral-900 text-white">
        <div class="container-pro py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold">Green Haven</h3>
                            <p class="text-neutral-400 text-sm">Premium Plant Nursery</p>
                        </div>
                    </div>
                    <p class="text-neutral-300 mb-6 max-w-md leading-relaxed">
                        Your trusted partner in creating beautiful, healthy green spaces. We provide quality plants, expert care advice, and everything you need for your garden.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-12 h-12 bg-neutral-800 rounded-xl flex items-center justify-center hover:bg-primary-600 hover:scale-110 transition-all duration-300 shadow-lg group">
                            <i class="fab fa-facebook text-white group-hover:scale-110 transition-transform"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-neutral-800 rounded-xl flex items-center justify-center hover:bg-primary-600 hover:scale-110 transition-all duration-300 shadow-lg group">
                            <i class="fab fa-instagram text-white group-hover:scale-110 transition-transform"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-neutral-800 rounded-xl flex items-center justify-center hover:bg-primary-600 hover:scale-110 transition-all duration-300 shadow-lg group">
                            <i class="fab fa-twitter text-white group-hover:scale-110 transition-transform"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-neutral-800 rounded-xl flex items-center justify-center hover:bg-primary-600 hover:scale-110 transition-all duration-300 shadow-lg group">
                            <i class="fab fa-pinterest text-white group-hover:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-primary-400">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}" class="text-neutral-300 hover:text-white transition-colors duration-200">All Products</a></li>
                        <li><a href="{{ route('plants.index') }}?category=indoor" class="text-neutral-300 hover:text-white transition-colors duration-200">Indoor Plants</a></li>
                        <li><a href="{{ route('plants.index') }}?category=outdoor" class="text-neutral-300 hover:text-white transition-colors duration-200">Outdoor Plants</a></li>
                        <li><a href="{{ route('plants.index') }}?category=succulents" class="text-neutral-300 hover:text-white transition-colors duration-200">Succulents</a></li>
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">Plant Care Guide</a></li>
                    </ul>
                </div>
                
                <!-- Customer Care -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-primary-400">Customer Care</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">Plant Care Tips</a></li>
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">Shipping Information</a></li>
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">Returns & Exchanges</a></li>
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">Contact Support</a></li>
                        <li><a href="#" class="text-neutral-300 hover:text-white transition-colors duration-200">FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-12 border-neutral-700">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6 mb-4 md:mb-0">
                    <p class="flex items-center text-neutral-400">
                        <i class="fas fa-phone mr-2 text-primary-500"></i>
                        +1 (555) 123-4567
                    </p>
                    <p class="flex items-center text-neutral-400">
                        <i class="fas fa-envelope mr-2 text-primary-500"></i>
                        hello@greenhaven.com
                    </p>
                </div>
                <p class="text-neutral-400 text-sm text-center md:text-right">
                    &copy; {{ date('Y') }} Green Haven. All rights reserved.<br>
                    <span class="text-xs text-neutral-500">College Project - MSC IT Sem 1</span>
                </p>
            </div>
        </div>
    </footer>
    @endif

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-24 right-4 z-50 space-y-3 max-w-sm"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center h-full">
            <div class="glass-effect rounded-2xl p-8 flex flex-col items-center space-y-4">
                <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                <span class="text-neutral-700 font-medium">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Global JavaScript -->
    <script>
        // Enhanced notification system
        function showNotification(message, type = 'success', duration = 4000) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            
            const typeConfig = {
                success: { 
                    bg: 'bg-gradient-primary', 
                    icon: 'fas fa-check-circle',
                    title: 'Success'
                },
                error: { 
                    bg: 'bg-red-500', 
                    icon: 'fas fa-exclamation-circle',
                    title: 'Error'
                },
                warning: { 
                    bg: 'bg-yellow-500', 
                    icon: 'fas fa-exclamation-triangle',
                    title: 'Warning'
                },
                info: { 
                    bg: 'bg-blue-500', 
                    icon: 'fas fa-info-circle',
                    title: 'Info'
                }
            };
            
            const config = typeConfig[type] || typeConfig.success;
            
            notification.className = `${config.bg} text-white rounded-xl shadow-large p-4 notification-slide`;
            notification.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="${config.icon} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium">${config.title}</p>
                        <p class="text-sm opacity-90 mt-1">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-white/80 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, duration);
        }

        // Loading overlay functions
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Global error handler
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            showNotification('An unexpected error occurred. Please try again.', 'error');
        });

        // Show Laravel flash messages
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif

        @if(session('warning'))
            showNotification('{{ session('warning') }}', 'warning');
        @endif

        @if(session('info'))
            showNotification('{{ session('info') }}', 'info');
        @endif

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Handle About tab click
        function handleAboutClick() {
            console.log('About tab clicked!');
            // You can add any custom logic here
            // For example: analytics tracking, animations, etc.
            
            // Optional: Add a small delay for better UX
            setTimeout(function() {
                // Any additional actions after click
            }, 100);
        }

        // Add loading states to buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.form && this.form.checkValidity()) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                        this.disabled = true;
                    }
                });
            });
        });
    </script>

    <!-- Modern Animations JavaScript -->
    <script src="{{ asset('js/modern-animations.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
