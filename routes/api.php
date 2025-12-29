<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])
        ->middleware('throttle:10,1'); // 10 attempts per minute

    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])
        ->middleware('throttle:5,1'); // 5 attempts per minute

    Route::post('/password/email', [\App\Http\Controllers\Api\AuthController::class, 'forgotPassword'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::post('/password/reset', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.reset');
});

// Protected Authentication Routes
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
    Route::get('/permissions', [\App\Http\Controllers\Api\RoleController::class, 'myPermissions']);
});

// Role & Permission Management (Admin only)
Route::middleware(['auth:sanctum', 'ensure.permission:users.view'])->prefix('roles')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\RoleController::class, 'index']);
    Route::get('/permissions', [\App\Http\Controllers\Api\RoleController::class, 'permissions']);
    Route::post('/{user}/assign', [\App\Http\Controllers\Api\RoleController::class, 'assignRole']);
    Route::post('/{user}/remove', [\App\Http\Controllers\Api\RoleController::class, 'removeRole']);
});

// Newsletter
Route::post('/newsletter/subscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);

// Protected API endpoints
Route::middleware('auth:sanctum')->group(function () {
    // Cart API endpoints
    Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::get('/cart/count', [\App\Http\Controllers\Api\CartController::class, 'count']);
    Route::post('/cart/add', [\App\Http\Controllers\Api\CartController::class, 'add']);
    Route::put('/cart/update/{id}', [\App\Http\Controllers\Api\CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [\App\Http\Controllers\Api\CartController::class, 'remove']);
    Route::delete('/cart/clear', [\App\Http\Controllers\Api\CartController::class, 'clear']);

    // Order API endpoints
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Api\OrderController::class, 'cancel']);

    // Wishlist API endpoints
    Route::get('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
    Route::get('/wishlist/check', [\App\Http\Controllers\Api\WishlistController::class, 'check']);
    Route::post('/wishlist/add', [\App\Http\Controllers\Api\WishlistController::class, 'add']);
    Route::post('/wishlist/toggle', [\App\Http\Controllers\Api\WishlistController::class, 'toggle']);
    Route::get('/wishlist/check/{productId}', [\App\Http\Controllers\Api\WishlistController::class, 'check']);

    // Reviews
    Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);

    // Address Management
    Route::get('/addresses', [\App\Http\Controllers\Api\AddressController::class, 'index']);
    Route::apiResource('addresses', App\Http\Controllers\Api\AddressController::class)->except(['index']);

    // Price Alerts
    Route::get('/price-alerts', [App\Http\Controllers\Api\PriceAlertController::class, 'index']);
    Route::post('/price-alerts', [App\Http\Controllers\Api\PriceAlertController::class, 'store']);
    Route::delete('/price-alerts/{id}', [App\Http\Controllers\Api\PriceAlertController::class, 'destroy']);

    Route::delete('/wishlist/remove/{productId}', [\App\Http\Controllers\Api\WishlistController::class, 'remove']);
    Route::delete('/wishlist/clear', [\App\Http\Controllers\Api\WishlistController::class, 'clear']);

    // Reviews API endpoints
    Route::get('/reviews/{id}', [\App\Http\Controllers\Api\ReviewController::class, 'show']);
    Route::put('/reviews/{id}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    Route::post('/reviews/{id}/vote', [\App\Http\Controllers\Api\ReviewController::class, 'vote']);

    // Loyalty Program API endpoints
    Route::get('/loyalty', [\App\Http\Controllers\Api\LoyaltyController::class, 'index']);
    Route::get('/loyalty/history', [\App\Http\Controllers\Api\LoyaltyController::class, 'history']);
    Route::get('/loyalty/tier', [\App\Http\Controllers\Api\LoyaltyController::class, 'tier']);
    Route::get('/loyalty/expiring', [\App\Http\Controllers\Api\LoyaltyController::class, 'expiring']);
    Route::post('/loyalty/redeem', [\App\Http\Controllers\Api\LoyaltyController::class, 'redeem']);

    // User Profile API endpoints
    Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/password', [\App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
    Route::get('/profile/data-export', [\App\Http\Controllers\Api\ProfileController::class, 'exportData']); // GDPR: Data export
    Route::post('/profile/avatar', [\App\Http\Controllers\Api\ProfileController::class, 'updateAvatar']);
    Route::delete('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'deleteAccount']);

    // Address Management
    Route::get('/profile/addresses', [\App\Http\Controllers\Api\ProfileController::class, 'addresses']);
    Route::post('/profile/addresses', [\App\Http\Controllers\Api\ProfileController::class, 'createAddress']);
    Route::put('/profile/addresses/{id}', [\App\Http\Controllers\Api\ProfileController::class, 'updateAddress']);
    Route::delete('/profile/addresses/{id}', [\App\Http\Controllers\Api\ProfileController::class, 'deleteAddress']);
    Route::post('/profile/addresses/{id}/default', [\App\Http\Controllers\Api\ProfileController::class, 'setDefaultAddress']);

    // Plant Care Reminders API
    Route::get('/plant-care-reminders', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'index']);

    // Vouchers & Gift Cards
    Route::post('/vouchers/verify', [App\Http\Controllers\Api\VoucherController::class, 'verify']);
    Route::get('/gift-cards', [App\Http\Controllers\Api\GiftCardController::class, 'index']);
    Route::post('/gift-cards/verify', [App\Http\Controllers\Api\GiftCardController::class, 'verify']);
    Route::post('/gift-cards/purchase', [App\Http\Controllers\Api\GiftCardController::class, 'purchase']);

    // Loyalty Points
    Route::get('/loyalty/points', [App\Http\Controllers\Api\LoyaltyController::class, 'index']);
    Route::post('/loyalty/redeem', [App\Http\Controllers\Api\LoyaltyController::class, 'redeem']);

    // Plant Health Logs
    Route::get('/plant-care-reminders/{reminder}/logs', [App\Http\Controllers\Api\HealthLogController::class, 'index']);
    Route::post('/plant-care-reminders/{reminder}/logs', [App\Http\Controllers\Api\HealthLogController::class, 'store']);
    Route::delete('/health-logs/{id}', [App\Http\Controllers\Api\HealthLogController::class, 'destroy']);

    // Community Features
    Route::get('/community/posts', [App\Http\Controllers\Api\CommunityController::class, 'index']);
    Route::post('/community/posts', [App\Http\Controllers\Api\CommunityController::class, 'store']);
    Route::post('/community/posts/{id}/like', [App\Http\Controllers\Api\CommunityController::class, 'like']);
    Route::post('/community/posts/{id}/comment', [App\Http\Controllers\Api\CommunityController::class, 'comment']);

    Route::get('/plant-care-reminders/upcoming', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'upcoming']);
    Route::get('/plant-care-reminders/overdue', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'overdue']);
    Route::get('/plant-care-reminders/calendar', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'calendar']);
    Route::get('/plant-care-reminders/{id}', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'show']);
    Route::post('/plant-care-reminders', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'store']);
    Route::put('/plant-care-reminders/{id}', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'update']);
    Route::post('/plant-care-reminders/{id}/complete', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'complete']);
    Route::delete('/plant-care-reminders/{id}', [\App\Http\Controllers\Api\PlantCareReminderController::class, 'destroy']);

    // Vendor Routes
    // Vendor Routes
    // 1. Registration - Accessible to any authenticated user (who wants to be a vendor)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/vendor/register', [\App\Http\Controllers\Api\VendorController::class, 'register']);
        Route::get('/vendor/profile', [\App\Http\Controllers\Api\VendorController::class, 'profile']); // Profile check might be needed for status
    });

    // 2. Protected Vendor Routes - Require Approved Status
    Route::middleware(['auth:sanctum', 'vendor.approved'])->prefix('vendor')->group(function () {
        Route::put('/profile', [\App\Http\Controllers\Api\VendorController::class, 'update']);

        // Vendor Product Management
        Route::get('/products', [\App\Http\Controllers\Api\VendorProductController::class, 'index']);
        Route::post('/products', [\App\Http\Controllers\Api\VendorProductController::class, 'store']);
        Route::put('/products/{id}', [\App\Http\Controllers\Api\VendorProductController::class, 'update']);
        Route::delete('/products/{id}', [\App\Http\Controllers\Api\VendorProductController::class, 'destroy']);

        // Vendor Order Management
        Route::get('/orders', [\App\Http\Controllers\Api\VendorOrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\VendorOrderController::class, 'show']);
        Route::put('/orders/{orderId}/items/{itemId}/status', [\App\Http\Controllers\Api\VendorOrderController::class, 'updateItemStatus']);

        // Vendor Wallet
        Route::get('/wallet', [\App\Http\Controllers\Api\VendorWalletController::class, 'index']);
        Route::post('/wallet/payout', [\App\Http\Controllers\Api\VendorWalletController::class, 'requestPayout']);
    });
});

// Blog Routes
Route::get('/posts', [App\Http\Controllers\Api\BlogController::class, 'index']);
Route::get('/posts/latest', [App\Http\Controllers\Api\BlogController::class, 'latest']);
Route::get('/posts/{slug}', [App\Http\Controllers\Api\BlogController::class, 'show']);

// Admin APIs (Admin only)
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Analytics & Dashboard
    Route::middleware('ensure.permission:analytics.view')->prefix('analytics')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'dashboard']);
        Route::get('/sales', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'salesReport']);
        Route::get('/customers', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'customerReport']);
        Route::get('/inventory', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'inventoryReport']);
    });

    // Order Management
    Route::middleware('ensure.permission:orders.update')->group(function () {
        Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'adminIndex']);
        Route::put('/orders/{id}/status', [\App\Http\Controllers\Api\OrderController::class, 'updateStatus']);
    });

    // Product Management
    Route::middleware('ensure.permission:products.manage')->prefix('products')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\ProductController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\Admin\ProductController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'destroy']);
    });

    // Review Management
    Route::middleware('ensure.permission:reviews.manage')->prefix('reviews')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'index']);
        Route::post('/{id}/approve', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'approve']);
        Route::post('/{id}/reject', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'reject']);
        Route::post('/{id}/feature', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'feature']);
        Route::post('/{id}/unfeature', [\App\Http\Controllers\Api\Admin\ReviewController::class, 'unfeature']);
    });

    // User Management
    Route::middleware('ensure.permission:users.manage')->prefix('users')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'update']);

        // Admin Vendor Management
        Route::get('/vendors', [\App\Http\Controllers\Api\AdminVendorController::class, 'index']);
        Route::put('/vendors/{id}/approve', [\App\Http\Controllers\Api\AdminVendorController::class, 'approve']);
        Route::put('/vendors/{id}/reject', [\App\Http\Controllers\Api\AdminVendorController::class, 'reject']);
        Route::put('/vendors/{id}/suspend', [\App\Http\Controllers\Api\AdminVendorController::class, 'suspend']);
        Route::put('/vendors/{id}/unsuspend', [\App\Http\Controllers\Api\AdminVendorController::class, 'unsuspend']);

        // Audit Logs
        Route::get('/audit-logs', [\App\Http\Controllers\Api\AuditLogController::class, 'index']);
    });

    // System Settings
    Route::middleware('ensure.permission:system.settings')->prefix('settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SystemSettingController::class, 'index']);
        Route::put('/', [\App\Http\Controllers\Api\SystemSettingController::class, 'update']);
        Route::post('/backup', [\App\Http\Controllers\Api\SystemSettingController::class, 'backup']);
    });

    // Audit Logs
    Route::middleware('ensure.permission:audit.view')->prefix('audit-logs')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\AuditLogController::class, 'index']);
        Route::get('/statistics', [\App\Http\Controllers\Api\Admin\AuditLogController::class, 'statistics']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\AuditLogController::class, 'show']);
        Route::get('/model/{modelType}/{modelId}', [\App\Http\Controllers\Api\Admin\AuditLogController::class, 'forModel']);
    });

    // Media Management
    Route::get('/media', [\App\Http\Controllers\Api\Admin\MediaController::class, 'index']);
    Route::post('/media', [\App\Http\Controllers\Api\Admin\MediaController::class, 'store']);
});

// Public Vendor Store Routes
Route::get('/stores/{slug}', [\App\Http\Controllers\Api\VendorStoreController::class, 'show']);
Route::get('/stores/{slug}/products', [\App\Http\Controllers\Api\VendorStoreController::class, 'products']);

// Public Product & Category Endpoints
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
Route::get('/products/{id}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']); // Public review route for a specific product
Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::get('/categories/tree', [\App\Http\Controllers\Api\CategoryController::class, 'tree']);
Route::get('/categories/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);

// Public Review Endpoints
Route::get('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

// Public Plant Care Guides Endpoints
Route::get('/plant-care-guides', [\App\Http\Controllers\Api\PlantCareGuideController::class, 'index']);
Route::get('/plant-care-guides/seasonal', [\App\Http\Controllers\Api\PlantCareGuideController::class, 'seasonalCare']);
Route::get('/plant-care-guides/plant/{plantId}', [\App\Http\Controllers\Api\PlantCareGuideController::class, 'byPlant']);
Route::get('/plant-care-guides/{id}', [\App\Http\Controllers\Api\PlantCareGuideController::class, 'show']);

// Plant Finder Quiz
Route::post('/plant-finder/results', [\App\Http\Controllers\Api\PlantCareController::class, 'plantFinderResults']);

// Health check endpoint (no authentication required)
Route::get('/health', function () {
    try {
        // Check database connection
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }

    // Check Redis connection (if configured)
    $redisStatus = 'not_configured';
    try {
        if (config('cache.default') === 'redis') {
            \Illuminate\Support\Facades\Redis::ping();
            $redisStatus = 'connected';
        }
    } catch (\Exception $e) {
        $redisStatus = 'disconnected';
    }

    // Check disk space (warning if < 10%)
    $diskUsage = disk_free_space(storage_path());
    $diskTotal = disk_total_space(storage_path());
    $diskPercent = ($diskUsage / $diskTotal) * 100;

    $status = ($dbStatus === 'connected' && $diskPercent > 10) ? 'healthy' : 'unhealthy';

    return response()->json([
        'status' => $status,
        'timestamp' => now()->toIso8601String(),
        'services' => [
            'database' => $dbStatus,
            'cache' => $redisStatus,
            'disk' => [
                'free_percent' => round($diskPercent, 2),
                'status' => $diskPercent > 10 ? 'ok' : 'warning',
            ],
        ],
    ], $status === 'healthy' ? 200 : 503);
});

// Public API endpoints
Route::get('/product-reviews/{productId}', [\App\Http\Controllers\Api\ReviewController::class, 'getProductReviews']);

// Features & Testimonials (Database-driven)
Route::get('/features', [App\Http\Controllers\Api\FeatureController::class, 'index']);
Route::get('/testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'index']);

// Related Products
Route::get('/products/{id}/related', function ($id) {
    $product = \App\Models\Product::find($id);
    if (!$product) {
        return response()->json(['success' => false, 'data' => []]);
    }

    $related = \App\Models\Product::where('category_id', $product->category_id)
        ->where('id', '!=', $id)
        ->where('is_active', true)
        ->inRandomOrder()
        ->limit(4)
        ->get();

    // Transform to match frontend expectation
    $data = $related->map(function ($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->price,
            'images' => $p->formatted_images,
        ];
    });

    return response()->json(['success' => true, 'data' => $data]);
});
