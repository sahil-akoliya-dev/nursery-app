/**
 * Nursery App 2.0 - Core Application Logic
 * 
 * Stack: Vanilla JS + Alpine.js + Axios + Tailwind CSS
 */

// --- Configuration ---
const CONFIG = {
    API_URL: '/api',
    IMAGE_PATH: '/assets/images',
};

// --- Axios Setup ---
// Note: We assume axios is loaded via CDN in the HTML
const api = axios.create({
    baseURL: CONFIG.API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true // Important for Sanctum cookie-based auth
});

// Expose api globally
window.api = api;

// Add auth token if available (for token-based auth, though Sanctum uses cookies primarily for SPA)
const token = localStorage.getItem('auth_token');
if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// --- Global State (Alpine.js) ---
document.addEventListener('alpine:init', () => {

    // Store: Global state management
    Alpine.store('auth', {
        user: (() => {
            try {
                const stored = localStorage.getItem('user');
                if (!stored || stored === 'undefined') return null;
                return JSON.parse(stored);
            } catch (e) {
                console.warn('Failed to parse user from local storage, clearing it.', e);
                localStorage.removeItem('user');
                return null;
            }
        })(),
        isAuthenticated: (() => {
            try {
                const stored = localStorage.getItem('user');
                return !!(stored && stored !== 'undefined');
            } catch (e) { return false; }
        })(),

        async login(email, password, remember = false) {
            try {
                // CSRF protection for Laravel Sanctum
                await axios.get('/sanctum/csrf-cookie');

                const response = await api.post('/auth/login', { email, password, remember });

                // Check if login was successful
                if (response.data.success && response.data.data.user) {
                    this.user = response.data.data.user;
                    this.isAuthenticated = true;

                    localStorage.setItem('user', JSON.stringify(this.user));

                    // Store the token
                    if (response.data.data.token) {
                        localStorage.setItem('auth_token', response.data.data.token);
                        api.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.token}`;
                    }

                    return { success: true };
                }

                return {
                    success: false,
                    message: response.data.message || 'Login failed'
                };
            } catch (error) {
                console.error('Login failed:', error);
                return {
                    success: false,
                    message: error.response?.data?.error?.message || error.response?.data?.message || 'Login failed. Please try again.'
                };
            }
        },

        async register(name, email, password, phone = '', date_of_birth = '') {
            try {
                await axios.get('/sanctum/csrf-cookie');
                const response = await api.post('/auth/register', {
                    name,
                    email,
                    password,
                    phone,
                    date_of_birth
                });

                if (response.data.success) {
                    this.user = response.data.data.user;
                    this.isAuthenticated = true;
                    localStorage.setItem('user', JSON.stringify(this.user));

                    if (response.data.data.token) {
                        localStorage.setItem('auth_token', response.data.data.token);
                        api.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.token}`;
                    }

                    return { success: true };
                }

                return {
                    success: false,
                    message: response.data.message || 'Registration failed'
                };
            } catch (error) {
                console.error('Registration failed:', error);
                return {
                    success: false,
                    message: error.response?.data?.error?.message || error.response?.data?.message || 'Registration failed. Please try again.'
                };
            }
        },



        async updateProfile(profileData) {
            // Validation
            if (profileData.phone) {
                // Remove non-digits
                const cleanPhone = profileData.phone.replace(/[^0-9]/g, '');
                if (cleanPhone.length < 10) {
                    return { success: false, message: 'Phone number must be at least 10 digits.' };
                }
                if (cleanPhone.length > 15) {
                    return { success: false, message: 'Phone number is too long.' };
                }
            }

            try {
                const token = localStorage.getItem('auth_token');
                const response = await axios.put('/api/profile', profileData, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                if (response.data.success) {
                    this.user = response.data.data;
                    localStorage.setItem('user_data', JSON.stringify(this.user));
                    return { success: true, message: 'Profile updated successfully' };
                }
                return { success: false, message: response.data.message || 'Update failed' };
            } catch (error) {
                console.error('Profile update error:', error);
                return { success: false, message: error.response?.data?.message || 'Update failed' };
            }
        },

        async updateAvatar(file) {
            try {
                const formData = new FormData();
                formData.append('avatar', file);

                const response = await api.post('/profile/avatar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    // Update local user object
                    if (this.user) {
                        this.user.avatar = response.data.data.avatar;
                        localStorage.setItem('user', JSON.stringify(this.user));
                    }
                    return { success: true, message: response.data.message };
                }
                return { success: false, message: response.data.message || 'Failed to upload avatar' };
            } catch (error) {
                console.error('Avatar upload failed:', error);
                return {
                    success: false,
                    message: error.response?.data?.message || 'Avatar upload failed'
                };
            }
        },

        async logout() {
            try {
                // Attempt API logout (for token-based)
                if (localStorage.getItem('auth_token')) {
                    await api.post('/auth/logout');
                }
                // Attempt web logout (for session-based/Google)
                await axios.post('/logout');
            } catch (e) {
                console.warn('Logout API call failed, but clearing local state anyway:', e);
            } finally {
                this.user = null;
                this.isAuthenticated = false;
                localStorage.removeItem('user');
                localStorage.removeItem('user_data');
                localStorage.removeItem('auth_token');
                delete api.defaults.headers.common['Authorization'];

                // Force reload to clear any memory states or HttpOnly cookies
                window.location.href = '/login.html';
            }
        },

        // --- Helper Methods ---
        hasRole(role) {
            if (!this.user) return false;

            // 1. Check legacy role field (Primary check for backward compatibility)
            if (this.user.role === role) return true;

            // 2. Check Spatie roles array
            if (this.user.roles && Array.isArray(this.user.roles)) {
                // If roles is array of strings
                if (this.user.roles.length > 0 && typeof this.user.roles[0] === 'string') {
                    if (this.user.roles.includes(role)) return true;
                }
                // If roles is array of objects
                if (this.user.roles.some(r => r.name === role)) return true;
            }

            return false;
        },

        hasAnyRole(roles) {
            if (!this.user) return false;
            return roles.some(role => this.hasRole(role));
        },

        hasPermission(permission) {
            if (!this.user) return false;

            // Check user direct permissions array
            if (this.user.permissions && Array.isArray(this.user.permissions)) {
                if (typeof this.user.permissions[0] === 'string') {
                    return this.user.permissions.includes(permission);
                }
                return this.user.permissions.some(p => p.name === permission);
            }

            // Check if user has role that has permission (if frontend has that data)
            // Ideally backend sends flattened permissions list.

            return false;
        },

        isAdmin() {
            return this.hasAnyRole(['super_admin', 'admin', 'manager']);
        },

        isVendor() {
            return this.hasRole('vendor');
        }
    });

    Alpine.store('cart', {
        items: JSON.parse(localStorage.getItem('cart_items')) || [],
        count: 0,
        total: 0,

        async init() {
            this.calculateTotals();

            // Check server-side session first (for Google Login)
            if (!this.isAuthenticated) { // Only check if not already logged in locally
                try {
                    // Ensure CSRF token is set
                    await axios.get('/sanctum/csrf-cookie');

                    const response = await api.get('/auth/user');
                    if (response.data && response.data.user) {
                        // We found a session! Log them in on frontend
                        Alpine.store('auth').user = response.data.user;
                        Alpine.store('auth').isAuthenticated = true;
                        localStorage.setItem('user', JSON.stringify(response.data.user));

                        // If on login/register page, redirect to correct place
                        if (window.location.pathname === '/login' || window.location.pathname === '/register') {
                            const user = response.data.user;
                            const redirect = Alpine.store('auth').isAdmin() ? '/admin-dashboard' : (Alpine.store('auth').isVendor() ? '/vendor-dashboard' : '/profile');
                            window.location.href = redirect;
                        }
                    }
                } catch (e) {
                    // No session found, standard behavior
                }
            }

            // Sync with backend ONLY if logged in AND has auth token
            const authToken = localStorage.getItem('auth_token');
            const authStore = Alpine.store('auth');

            if (authStore && authStore.isAuthenticated && authToken) {
                await this.fetch();
            }
        },

        async fetch() {
            try {
                const response = await api.get('/cart');
                // Assuming backend returns { data: { items: [...] } } or similar
                // We might need to map backend structure to frontend structure
                // For now, let's assume backend returns the cart items directly or we merge
                if (response.data.data && response.data.data.items) {
                    // Merge strategy: Backend wins or Local wins? 
                    // Simple strategy: If local is empty, take backend. If local has items, push to backend then re-fetch.
                    if (this.items.length === 0 && response.data.data.items.length > 0) {
                        this.items = response.data.data.items.map(item => ({
                            id: item.product_id,
                            name: item.product.name,
                            price: item.product.price,
                            image: item.product.formatted_images[0],
                            quantity: item.quantity,
                            category: item.product.category ? item.product.category.name : 'Plant'
                        }));
                        this.calculateTotals();
                        this.saveToStorage();
                    } else if (this.items.length > 0) {
                        // Push local items to backend (naive sync)
                        // In a real app, we'd handle this more robustly
                        for (const item of this.items) {
                            try {
                                await api.post('/cart/add', {
                                    item_id: item.id,
                                    item_type: 'product',
                                    quantity: item.quantity
                                });
                            } catch (e) {
                                // Ignore duplicates or errors for now
                            }
                        }
                        // Re-fetch to get cleaner state
                        const refresh = await api.get('/cart');
                        if (refresh.data.data && refresh.data.data.items) {
                            this.items = refresh.data.data.items.map(item => ({
                                id: item.product_id,
                                name: item.product.name,
                                price: item.product.price,
                                image: item.product.formatted_images[0],
                                quantity: item.quantity,
                                category: item.product.category ? item.product.category.name : 'Plant'
                            }));
                            this.calculateTotals();
                            this.saveToStorage();
                        }
                    }
                }
            } catch (error) {
                // Only log non-401 errors (401 is expected when not authenticated)
                if (error.response?.status !== 401) {
                    console.warn('Failed to fetch cart from backend:', error);
                }
                // For 401, silently continue with local cart
            }
        },

        saveToStorage() {
            localStorage.setItem('cart_items', JSON.stringify(this.items));
        },

        async add(product, quantity = 1) {
            const existing = this.items.find(item => item.id === product.id);
            if (existing) {
                existing.quantity += quantity;
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.images && product.images.length > 0 ? product.images[0] : (product.image || 'https://via.placeholder.com/150'),
                    category: product.category ? (typeof product.category === 'object' ? product.category.name : product.category) : 'Plant',
                    quantity
                });
            }

            this.calculateTotals();
            this.saveToStorage();

            // Trigger a custom event for animations
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count } }));

            showToast(`Added ${product.name} to cart`);

            // Sync with backend if logged in
            if (Alpine.store('auth').isAuthenticated) {
                try {
                    await api.post('/cart/add', {
                        item_id: product.id,
                        item_type: 'product', // Default to product for now
                        quantity
                    });
                } catch (error) {
                    console.error('Failed to add to backend cart:', error);
                }
            }
        },

        async remove(productId) {
            this.items = this.items.filter(item => item.id !== productId);
            this.calculateTotals();
            this.saveToStorage();

            if (Alpine.store('auth').isAuthenticated) {
                try {
                    await api.delete(`/cart/remove/${productId}`);
                } catch (error) {
                    console.error('Failed to remove from backend cart:', error);
                }
            }
        },

        async updateQuantity(productId, quantity) {
            const item = this.items.find(item => item.id === productId);
            if (item) {
                const oldQty = item.quantity;
                item.quantity = parseInt(quantity);
                if (item.quantity <= 0) {
                    await this.remove(productId);
                } else {
                    this.calculateTotals();
                    this.saveToStorage();
                    if (Alpine.store('auth').isAuthenticated) {
                        try {
                            // Backend might expect 'quantity' to be absolute or relative. 
                            // Usually 'update' implies absolute.
                            // Checking route: Route::put('/cart/update/{id}', ...)
                            await api.put(`/cart/update/${productId}`, { quantity: item.quantity });
                        } catch (error) {
                            console.error('Failed to update backend cart:', error);
                            item.quantity = oldQty; // Revert on error
                        }
                    }
                }
            }
        },

        clear() {
            this.items = [];
            this.calculateTotals();
            this.saveToStorage();
            if (Alpine.store('auth').isAuthenticated) {
                api.delete('/cart/clear').catch(e => console.error(e));
            }
        },
        calculateTotals() {
            this.count = this.items.reduce((sum, item) => sum + item.quantity, 0);
            this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }
    });

    // Toast Notification Store
    Alpine.store('toast', {
        toasts: [],
        add(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.remove(id), 3000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    });
});

// Global Toast Helper
window.showToast = (message, type = 'success') => {
    if (window.Alpine) {
        Alpine.store('toast').add(message, type);
    }
};

// --- Utility Functions ---
const Utils = {
    formatPrice(amount) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            maximumFractionDigits: 0 // Usually INR doesn't show paise in round figures
        }).format(amount);
    },

    truncate(str, length = 50) {
        if (str.length <= length) return str;
        return str.substring(0, length) + '...';
    }
};

// --- Component Loader ---
async function loadComponent(id, url) {
    const element = document.getElementById(id);
    if (!element) return;

    try {
        const response = await fetch(url);
        const html = await response.text();
        element.innerHTML = html;

        // Re-initialize Alpine for the new content
        if (window.Alpine) {
            Alpine.initTree(element);
        }

        // Re-initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }
    } catch (error) {
        console.error(`Failed to load component ${url}:`, error);
    }
}

window.loadComponents = async () => {
    await Promise.all([
        loadComponent('header-placeholder', '/components/header.html'),
        loadComponent('footer-placeholder', '/components/footer.html'),
        loadComponent('toast-placeholder', '/components/toast.html')
    ]);
};

// --- Features Section (Why Choose Us) ---
function featuresSection() {
    return {
        features: [],
        loading: true,
        async init() {
            try {
                const response = await api.get('/features');
                this.features = response.data.data || [];
            } catch (error) {
                console.error('Failed to load features:', error);
                this.features = [];
            } finally {
                this.loading = false;
                setTimeout(() => AOS.refresh(), 100);
            }
        }
    };
}

// --- Testimonials Section ---
function testimonialsSection() {
    return {
        testimonials: [],
        loading: true,
        async init() {
            try {
                const response = await api.get('/testimonials');
                this.testimonials = response.data.data || [];
            } catch (error) {
                console.error('Failed to load testimonials:', error);
                this.testimonials = [];
            } finally {
                this.loading = false;
                setTimeout(() => AOS.refresh(), 100);
            }
        }
    };
}



// --- Related Products Section ---
function relatedProductsSection() {
    return {
        related: [],
        loading: true,
        async init() {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id');

                // Fetch products to simulate "related" items
                const response = await api.get('/products');
                let all = response.data.data || [];
                // Filter out current product and take up to 4
                this.related = all.filter(p => p.id != id).slice(0, 4);
            } catch (error) {
                console.error('Failed to load related products:', error);
                this.related = [];
            } finally {
                this.loading = false;
                setTimeout(() => AOS.refresh(), 100);
            }
        }
    };
}

// Navbar Scroll Effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;
    if (window.scrollY > 50) {
        navbar.classList.add('shadow-md');
        navbar.classList.remove('glass');
        navbar.classList.add('bg-white/90', 'backdrop-blur-md');
    } else {
        navbar.classList.remove('shadow-md', 'bg-white/90', 'backdrop-blur-md');
        navbar.classList.add('glass');
    }
});

// --- Tailwind Configuration (Runtime) ---
// This is used by the CDN version of Tailwind to configure the theme
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d', // Deep Green
                    950: '#052e16',
                },
                earth: {
                    100: '#f5f5f4', // Stone
                    200: '#e7e5e4',
                    800: '#44403c', // Dark Stone
                    900: '#292524',
                }
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                display: ['"Outfit"', 'sans-serif'], // For high-impact headings
                serif: ['"Outfit"', 'serif'], // Mapping serif to display for consistency
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                }
            }
        }
    }
};

// Page Transition
document.addEventListener('DOMContentLoaded', () => {
    // Small delay to ensure styles are applied
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 50);
});

// --- Profile Page Logic ---
function profilePage() {
    return {
        activeTab: 'profile',
        loading: true,
        loadingAlerts: false,
        loadingGiftCards: false,
        loadingLoyalty: false,
        loadingReminders: false,
        loadingHealthLog: false,
        loadingHealthHistory: false,

        // Data
        orders: [],
        wishlist: [],
        addresses: [],
        alerts: [],
        giftCards: [],
        giftCardBalance: 0,
        loyalty: { balance: 0, transactions: [] },

        // Garden/Reminders
        allReminders: [],
        overdueReminders: [],
        upcomingReminders: [],
        completedReminders: [],
        showReminderModal: false,
        isEditingReminder: false,
        reminderForm: {
            title: '',
            description: '',
            reminder_type: 'watering',
            frequency: 'weekly',
            scheduled_date: new Date().toISOString().split('T')[0]
        },

        // Health Logs
        healthLogs: [],
        showHealthModal: false,
        healthForm: {
            status: 'healthy',
            notes: '',
            photo_url: ''
        },

        // Address Modal
        showAddressModal: false,
        isEditingAddress: false,
        addressForm: {
            label: 'Home',
            address_line_1: '',
            address_line_2: '',
            city: '',
            state: '',
            postal_code: '',
            country: 'USA',
            is_default: false
        },

        async init() {
            // Wait for auth check
            await new Promise(resolve => setTimeout(resolve, 100));

            if (!Alpine.store('auth').isAuthenticated) {
                window.location.href = '/login';
                return;
            }

            // Load all data parallel
            await Promise.all([
                this.loadOrders(),
                this.loadWishlist(),
                this.loadAddresses(),
                this.loadLoyalty(),
                this.loadGiftCards(),
                this.loadAlerts(),
                this.loadReminders(),
                this.loadHealthLogs()
            ]);

            this.loading = false;
        },

        async loadOrders() {
            try {
                const response = await api.get('/orders');
                this.orders = response.data.data || [];
            } catch (e) { console.error('Orders load failed', e); this.orders = []; }
        },

        async loadWishlist() {
            try {
                const response = await api.get('/wishlist');
                this.wishlist = response.data.data || [];
            } catch (e) { console.error('Wishlist load failed', e); this.wishlist = []; }
        },

        async loadAddresses() {
            try {
                const response = await api.get('/addresses');
                this.addresses = response.data.data || [];
            } catch (e) { console.error('Addresses load failed', e); this.addresses = []; }
        },

        async loadLoyalty() {
            try {
                const response = await api.get('/loyalty/points');
                this.loyalty = response.data.data || { balance: 0, transactions: [] };
            } catch (e) { console.error('Loyalty load failed', e); }
        },

        async loadGiftCards() {
            try {
                // Mock endpoint if not exists
                // const response = await api.get('/gift-cards'); 
                this.giftCards = [];
            } catch (e) { console.error('Gift cards load failed', e); }
        },

        async loadAlerts() {
            try {
                // const response = await api.get('/price-alerts');
                this.alerts = [];
            } catch (e) { console.error('Alerts load failed', e); }
        },

        async loadReminders() {
            // Mock data or API
            this.allReminders = [];
            this.overdueReminders = [];
            this.upcomingReminders = [];
        },

        async loadHealthLogs() {
            this.healthLogs = [];
        },

        // Actions
        async removeFromWishlist(id) {
            try {
                await api.delete(`/wishlist/${id}`);
                this.wishlist = this.wishlist.filter(item => item.product.id !== id);
                showToast('Removed from wishlist');
            } catch (e) { showToast('Failed to remove', 'error'); }
        },

        editAddress(address) {
            this.addressForm = { ...address };
            this.isEditingAddress = true;
            this.showAddressModal = true;
        },

        async deleteAddress(id) {
            if (!confirm('Are you sure?')) return;
            try {
                await api.delete(`/addresses/${id}`);
                this.addresses = this.addresses.filter(a => a.id !== id);
                showToast('Address deleted');
            } catch (e) { showToast('Failed to delete address', 'error'); }
        },

        async saveAddress() {
            try {
                if (this.isEditingAddress) {
                    await api.put(`/addresses/${this.addressForm.id}`, this.addressForm);
                } else {
                    await api.post('/addresses', this.addressForm);
                }
                await this.loadAddresses();
                this.showAddressModal = false;
                showToast('Address saved successfully');
            } catch (e) { showToast('Failed to save address', 'error'); }
        },

        buyGiftCard() {
            showToast('Gift Card purchase coming soon!', 'info');
        },

        // Reminder Stubs
        completeReminder(id) { showToast('Marked complete'); },
        async deleteReminder(id) { },
        editReminder(reminder) { },

        // Alert Stubs
        async deleteAlert(id) { },

        // Health Log Stubs
        async saveHealthLog() { },
        async deleteHealthLog(id) { }
    };
}
