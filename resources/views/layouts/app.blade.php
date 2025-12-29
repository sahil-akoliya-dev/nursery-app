<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Green Haven')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cpath fill='%2316a34a' d='M16 2c5 0 9 4 9 9 0 7-6 14-9 19-3-5-9-12-9-19 0-5 4-9 9-9zm0 4c-2.8 0-5 2.2-5 5 0 3.9 3.1 7.9 5 10.6 1.9-2.7 5-6.7 5-10.6 0-2.8-2.2-5-5-5z'/%3E%3C/svg%3E" />
    
    <!-- Fonts - Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animation Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Critical CSS (inline for above-the-fold content) -->
    <style>
        /* Critical design tokens */
        :root {
            --color-primary-600: #059669;
            --color-primary-700: #047857;
            --color-text-primary: #1f2937;
            --color-text-inverse: #ffffff;
            --spacing-2: 0.5rem;
            --spacing-3: 0.75rem;
            --border-radius-lg: 0.75rem;
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    
    <!-- Design tokens (critical) -->
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}?v=2.0">
    
    <!-- Critical component styles -->
    <link rel="stylesheet" href="{{ asset('css/components.css') }}?v=2.0">
    <link rel="stylesheet" href="{{ asset('css/button-fixes.css') }}?v=2.0">
    <link rel="stylesheet" href="{{ asset('css/critical-fixes.css') }}?v=2.0">
    
    <!-- Non-critical CSS (load asynchronously) -->
    <link rel="preload" href="{{ asset('css/modern-animations.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/professional-ui.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/micro-interactions.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/scroll-effects.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/layout-enhancements.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/responsive.css') }}?v=2.0" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/modern-animations.css') }}?v=2.0">
        <link rel="stylesheet" href="{{ asset('css/professional-ui.css') }}?v=2.0">
        <link rel="stylesheet" href="{{ asset('css/micro-interactions.css') }}?v=2.0">
        <link rel="stylesheet" href="{{ asset('css/scroll-effects.css') }}?v=2.0">
        <link rel="stylesheet" href="{{ asset('css/layout-enhancements.css') }}?v=2.0">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v=2.0">
    </noscript>
    <!-- Custom CSS -->
    <style>
        :root {
            /* Legacy support - these should use design-tokens.css variables */
            --primary-color: var(--color-primary-600);
            --secondary-color: var(--color-primary-700);
            --accent-color: var(--color-primary-500);
            --text-color: var(--color-text-primary);
            --light-bg: var(--color-bg-secondary);
        }
        
        body {
            font-family: var(--font-family-sans);
            color: var(--color-text-primary);
            background-color: var(--color-bg-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.875rem;
        }
        
        .btn-primary {
            background: var(--gradient-primary) !important;
            color: var(--color-text-inverse) !important;
            padding: var(--spacing-3) var(--spacing-8) !important;
            border-radius: var(--border-radius-lg) !important;
            font-weight: var(--font-weight-semibold) !important;
            transition: all var(--transition-base) !important;
            border: none !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: var(--spacing-2) !important;
            text-decoration: none !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-primary-600), var(--color-primary-700)) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-colored) !important;
        }
        
        .btn-secondary {
            background: var(--color-neutral-100) !important;
            color: var(--color-text-primary) !important;
            padding: var(--spacing-3) var(--spacing-8) !important;
            border-radius: var(--border-radius-lg) !important;
            font-weight: var(--font-weight-semibold) !important;
            transition: all var(--transition-base) !important;
            border: var(--border-width-1) solid var(--color-border-default) !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: var(--spacing-2) !important;
            text-decoration: none !important;
        }
        
        .btn-secondary:hover {
            background: var(--color-neutral-200) !important;
            transform: translateY(-2px) !important;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .price {
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            color: var(--color-primary-600);
        }
        
        .sale-price {
            text-decoration: line-through;
            color: var(--color-text-tertiary);
        }
        
        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            margin-top: auto;
        }
        
        .footer a {
            color: var(--color-neutral-200);
            text-decoration: none;
        }
        
        .footer a:hover {
            color: var(--color-text-inverse);
        }
        
        .img-wrap {
            display: block;
            overflow: hidden;
        }
        
        .img-wrap img {
            transition: transform 0.3s ease;
        }
        
        .img-wrap:hover img {
            transform: scale(1.05);
        }
        
        .search-input {
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .badge-featured {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            color: #000;
            font-weight: bold;
        }
        /* Dropdown fallback for when Alpine.js is not loaded */
        .dropdown-fallback {
            position: relative;
        }
        
        .dropdown-fallback:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-menu {
            display: none;
        }
        
        /* Smooth transitions */
        .dropdown-menu {
            transition: all 0.2s ease-in-out;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Main Navigation -->
    <x-navigation variant="public" :user="auth()->user()" />

    <!-- Main Content -->
    <main class="fade-in" style="flex: 1; width: 100%;">
        @yield('content')
    </main>

    @if(!request()->is('login') && !request()->is('register') && !request()->is('forgot-password') && !request()->is('reset-password*') && !request()->is('verify-email') && !request()->is('confirm-password') && !request()->is('/'))
    <!-- Stay Updated Section -->
    <section class="bg-green-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Stay Updated</h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Get the latest plant care tips, new arrivals, and exclusive offers delivered to your inbox.
            </p>
            <div class="max-w-md mx-auto flex">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-l-lg border-0 focus:ring-2 focus:ring-green-300 focus:outline-none">
                <button class="bg-green-700 text-white px-6 py-3 rounded-r-lg hover:bg-green-800 transition duration-300 font-semibold">
                    Subscribe
                </button>
            </div>
        </div>
    </section>
    @endif

    @if(!request()->is('login') && !request()->is('register') && !request()->is('forgot-password') && !request()->is('reset-password*') && !request()->is('verify-email') && !request()->is('confirm-password'))
    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden border-t border-green-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h5 class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-seedling mr-2 text-green-500"></i>Green Haven
                    </h5>
                    <p class="text-gray-300 mb-6 max-w-md">Your trusted partner in creating beautiful, healthy green spaces. We provide quality plants, expert care, and everything you need for your garden.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-green-500 transition duration-300">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-green-500 transition duration-300">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-green-500 transition duration-300">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-green-500 transition duration-300">
                            <i class="fab fa-pinterest text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h5 class="text-lg font-semibold mb-4 text-green-500">Quick Links</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-green-500 transition duration-300">All Products</a></li>
                        <li><a href="{{ route('plants.index') }}?category=indoor" class="text-gray-300 hover:text-green-500 transition duration-300">Indoor Plants</a></li>
                        <li><a href="{{ route('plants.index') }}?category=outdoor" class="text-gray-300 hover:text-green-500 transition duration-300">Outdoor Plants</a></li>
                        <li><a href="{{ route('plants.index') }}?category=succulents" class="text-gray-300 hover:text-green-500 transition duration-300">Succulents</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold mb-4 text-green-500">Customer Care</h5>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-green-500 transition duration-300">Plant Care Guide</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-500 transition duration-300">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-500 transition duration-300">Returns</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-500 transition duration-300">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-8 border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-6 mb-4 md:mb-0">
                    <p class="text-gray-300"><i class="fas fa-phone mr-2 text-green-500"></i>+1 (555) 123-4567</p>
                    <p class="text-gray-300"><i class="fas fa-envelope mr-2 text-green-500"></i>hello@greenhaven.com</p>
                </div>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Green Haven. All rights reserved. | College Project - MSC IT Sem 1</p>
            </div>
        </div>
    </footer>
    @endif

    <!-- Notification System -->
    <x-notification-system />

    <!-- Global Loading Overlay -->
    <div x-data="{ loading: false }" 
         @@start-loading.window="loading = true"
         @@stop-loading.window="loading = false"
         x-show="loading"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center"
         style="display: none;">
        
        <div class="glass p-8 rounded-2xl text-center">
            <div class="loading-spinner mb-4"></div>
            <p class="text-white font-semibold">Loading...</p>
        </div>
    </div>

    <style>
        .loading-spinner {
            width: 50px;
            height: 50px;
            margin: 0 auto;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Async CSS loading fallback
        !function(e){"use strict";var t=function(t,n,o){var i,r=e.document,a=r.createElement("link");if(n)i=n;else{var l=(r.body||r.getElementsByTagName("head")[0]).childNodes;i=l[l.length-1]}var d=r.styleSheets;a.rel="stylesheet",a.href=t,a.media="only x",function e(t){if(r.body)return t();setTimeout(function(){e(t)})}(function(){i.parentNode.insertBefore(a,n?i:i.nextSibling)});var f=function(e){for(var t=a.href,n=d.length;n--;)if(d[n].href===t)return e();setTimeout(function(){f(e)})};return a.addEventListener&&a.addEventListener("load",function(){this.media=o||"all"}),a.onloadcssdefined=f,f(function(){a.media!==o&&(a.media=o||"all")}),a};"undefined"!=typeof exports?exports.loadCSS=t:e.loadCSS=t}("undefined"!=typeof global?global:this);
        
        // Global error handler
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: 'error',
                    message: 'An unexpected error occurred. Please try again.'
                }
            }));
        });

        // Show success messages from Laravel
        @if(session('success'))
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: 'success',
                    message: '{{ session('success') }}'
                }
            }));
        @endif

        @if(session('error'))
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: 'error',
                    message: '{{ session('error') }}'
                }
            }));
        @endif

        @if(session('warning'))
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: 'warning',
                    message: '{{ session('warning') }}'
                }
            }));
        @endif

        // Legacy support for old showNotification function
        function showNotification(message, type = 'success', duration = 5000) {
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: type,
                    message: message,
                    duration: duration
                }
            }));
        }

        // Legacy support for loading functions
        function showLoading() {
            window.dispatchEvent(new CustomEvent('start-loading'));
        }

        function hideLoading() {
            window.dispatchEvent(new CustomEvent('stop-loading'));
        }

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


        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputs = document.querySelectorAll('input[placeholder*="Search"]');
            searchInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = '{{ route("products.index") }}?search=' + encodeURIComponent(query);
                        }
                    }
                });
            });
        });

        // Update cart and wishlist counts
        function updateCartCount(count) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = count;
            });
        }

        function updateWishlistCount(count) {
            const wishlistCountElements = document.querySelectorAll('.wishlist-count');
            wishlistCountElements.forEach(element => {
                element.textContent = count;
            });
        }
    </script>

    <!-- GSAP for advanced animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <!-- Lottie for animated icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/modern-animations.js') }}" defer></script>
    <script src="{{ asset('js/scroll-animations.js') }}" defer></script>
    <script src="{{ asset('js/interactions.js') }}" defer></script>
    <script src="{{ asset('js/cart-animations.js') }}" defer></script>

    @stack('scripts')
</body>
</html>