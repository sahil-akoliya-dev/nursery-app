<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->file(public_path('index.html'));
});

Route::get('/shop', function () {
    return response()->file(public_path('shop.html'));
});

Route::get('/plant-finder', function () {
    return response()->file(public_path('plant-finder.html'));
});

Route::get('/about', function () {
    return response()->file(public_path('about.html'));
});

Route::get('/cart', function () {
    return response()->file(public_path('cart.html'));
});

Route::get('/checkout', function () {
    return response()->file(public_path('checkout.html'));
});

Route::get('/login', function () {
    return response()->file(public_path('login.html'));
});

Route::get('/register', function () {
    return response()->file(public_path('register.html'));
});

Route::get('/profile', function () {
    return response()->file(public_path('profile.html'));
});

Route::get('/admin-dashboard', function () {
    return response()->file(public_path('admin-dashboard.html'));
});

Route::get('/product-detail', function () {
    return response()->file(public_path('product-detail.html'));
});

Route::get('/vendor/register', function () {
    return response()->file(public_path('vendor-register.html'));
});

Route::get('/vendor-dashboard', function () {
    return response()->file(public_path('vendor-dashboard.html'));
});

Route::get('/store', function () {
    return response()->file(public_path('store.html'));
});

Route::get('/blog', function () {
    return response()->file(public_path('blog.html'));
});

Route::get('/blog-detail', function () {
    return response()->file(public_path('blog-detail.html'));
});

// Social Authentication Routes
use App\Http\Controllers\Auth\SocialAuthController;

Route::prefix('auth')->group(function () {
    // Google
    Route::get('google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
});

// Explicitly handle assets if they fall through to Laravel
Route::get('/assets/{path}', function ($path) {
    $path = public_path('assets/' . $path);
    if (File::exists($path)) {
        $mime = File::mimeType($path);
        // Manually fix CSS mime type if detection fails
        if (str_ends_with($path, '.css')) {
            $mime = 'text/css';
        }
        return response()->file($path, ['Content-Type' => $mime]);
    }
    abort(404);
})->where('path', '.*');

// Fallback for any other web route to avoid 404s looking like broken pages
Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found. This is an API-only backend.'
    ], 404);
});

// Explicit Web Logout Route (Session-based) to fix persistent login issues
Route::post('/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'Logged out (Session invalidated)']);
});
