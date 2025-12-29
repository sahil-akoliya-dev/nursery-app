# Social Login Integration Guide (Google & Facebook)
**Priority:** HIGH (Easy to implement)
**Time Required:** 30-45 minutes
**Difficulty:** Easy

---

## 📋 What You'll Get

After this integration:
- ✅ "Sign in with Google" button (functional)
- ✅ "Sign in with Facebook" button (functional)
- ✅ Automatic user registration from social accounts
- ✅ Profile picture from social account
- ✅ Seamless authentication experience

---

## 🎯 Overview

Your app already has:
- ✅ UI buttons on login page
- ✅ Laravel Socialite package installed
- ❌ Missing: API credentials and configuration

We need to:
1. Get Google OAuth credentials
2. Get Facebook App credentials
3. Configure `.env` file
4. Update `config/services.php`
5. Test the integration

---

## 📦 Step 1: Install Laravel Socialite (If Not Installed)

Check if already installed:
```bash
composer show laravel/socialite
```

If not installed:
```bash
composer require laravel/socialite
```

---

## 🔑 Step 2: Get Google OAuth Credentials

### A. Create Google Cloud Project

1. **Go to:** https://console.cloud.google.com/
2. **Sign in** with your Google account
3. **Create New Project:**
   - Click "Select a Project" → "New Project"
   - Project Name: `Nursery App`
   - Click "Create"

### B. Enable Google+ API

1. **Left Menu** → "APIs & Services" → "Library"
2. **Search:** "Google+ API"
3. **Click** on it → Click "Enable"

### C. Create OAuth 2.0 Credentials

1. **Left Menu** → "APIs & Services" → "Credentials"
2. **Click** "Create Credentials" → "OAuth client ID"
3. **Application type:** Web application
4. **Name:** Nursery App Web Client
5. **Authorized JavaScript origins:**
   ```
   http://localhost:8000
   http://127.0.0.1:8000
   ```
6. **Authorized redirect URIs:**
   ```
   http://localhost:8000/auth/google/callback
   http://127.0.0.1:8000/auth/google/callback
   ```
7. **Click "Create"**
8. **Copy:**
   - Client ID (looks like: `123456789-abc.apps.googleusercontent.com`)
   - Client Secret (looks like: `GOCSPX-abcdefg`)

### D. For Production (Later)

When deploying, add your production URLs:
```
https://yourdomain.com
https://yourdomain.com/auth/google/callback
```

---

## 📘 Step 3: Get Facebook App Credentials

### A. Create Facebook App

1. **Go to:** https://developers.facebook.com/
2. **Sign in** with Facebook
3. **Click** "My Apps" → "Create App"
4. **Select:** "Consumer" (for login)
5. **App Name:** Nursery App
6. **App Contact Email:** your-email@example.com
7. **Click** "Create App"

### B. Add Facebook Login Product

1. **Dashboard** → Click "Add Product"
2. **Find** "Facebook Login" → Click "Set Up"
3. **Choose** "Web"
4. **Site URL:** `http://localhost:8000`
5. **Save**

### C. Configure Facebook Login Settings

1. **Left Menu** → "Facebook Login" → "Settings"
2. **Valid OAuth Redirect URIs:**
   ```
   http://localhost:8000/auth/facebook/callback
   http://127.0.0.1:8000/auth/facebook/callback
   ```
3. **Save Changes**

### D. Get App Credentials

1. **Left Menu** → "Settings" → "Basic"
2. **Copy:**
   - App ID (looks like: `123456789012345`)
   - App Secret (Click "Show" to reveal, looks like: `abc123def456`)

### E. Make App Public (Later for Production)

For now, your app is in "Development Mode" - only you can use it.
To make it public:
1. Top right → Switch to "Live Mode"
2. Complete App Review process (add Privacy Policy, etc.)

---

## ⚙️ Step 4: Configure Environment Variables

Open your `.env` file and add:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your-google-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your-facebook-app-id-here
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret-here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

**Replace** the placeholder values with your actual credentials from Steps 2 and 3.

---

## 🔧 Step 5: Update config/services.php

Open `config/services.php` and add Google and Facebook configurations.

**Check if these already exist.** If not, add them to the array:

```php
<?php

return [
    // ... existing services ...

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],
];
```

---

## 🛣️ Step 6: Add Routes

Open `routes/web.php` and add these routes:

```php
use App\Http\Controllers\Auth\SocialAuthController;

// Social Authentication Routes
Route::prefix('auth')->group(function () {
    // Google
    Route::get('google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

    // Facebook
    Route::get('facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
});
```

---

## 🎮 Step 7: Create Controller (If Not Exists)

Check if `app/Http/Controllers/Auth/SocialAuthController.php` exists.

If not, create it:

```bash
php artisan make:controller Auth/SocialAuthController
```

Then add this implementation:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $this->findOrCreateUser($googleUser, 'google');

            Auth::login($user, true);

            return redirect()->intended('/dashboard')->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google. Please try again.');
        }
    }

    /**
     * Redirect to Facebook for authentication
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = $this->findOrCreateUser($facebookUser, 'facebook');

            Auth::login($user, true);

            return redirect()->intended('/dashboard')->with('success', 'Successfully logged in with Facebook!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Facebook. Please try again.');
        }
    }

    /**
     * Find or create user from social provider
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Check if user exists with this social ID
        $user = User::where($provider . '_id', $socialUser->getId())->first();

        if ($user) {
            // Update user info if needed
            $user->update([
                'name' => $socialUser->getName() ?? $user->name,
                'avatar' => $socialUser->getAvatar() ?? $user->avatar,
            ]);
            return $user;
        }

        // Check if user exists with this email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Link social account to existing user
            $user->update([
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar() ?? $user->avatar,
            ]);
            return $user;
        }

        // Create new user
        return User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(16)), // Random password (won't be used)
            $provider . '_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(), // Auto-verify email from social login
            'role' => 'customer', // Default role
        ]);
    }
}
```

---

## 🗄️ Step 8: Update Database Schema

Add social login columns to users table:

**Create migration:**
```bash
php artisan make:migration add_social_login_to_users_table
```

**Edit the migration file** (database/migrations/YYYY_MM_DD_*_add_social_login_to_users_table.php):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('facebook_id')->nullable()->unique()->after('google_id');
            $table->string('avatar')->nullable()->after('facebook_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'facebook_id', 'avatar']);
        });
    }
};
```

**Run the migration:**
```bash
php artisan migrate
```

---

## 🎨 Step 9: Update Frontend (Login Page)

Update your login page to include working social login buttons.

**Check:** `resources/views/auth/login.blade.php` or your React/Inertia login component.

### For Blade Template:

```blade
<!-- Google Login Button -->
<a href="{{ route('auth.google') }}"
   class="btn btn-outline-dark w-100 mb-2">
    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Continue with Google
</a>

<!-- Facebook Login Button -->
<a href="{{ route('auth.facebook') }}"
   class="btn btn-primary w-100"
   style="background-color: #1877F2;">
    <svg class="w-5 h-5 mr-2" fill="white" viewBox="0 0 24 24">
        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
    </svg>
    Continue with Facebook
</a>
```

### For React/Inertia:

```jsx
{/* Google Login Button */}
<a
    href="/auth/google"
    className="btn btn-outline-dark w-full mb-2 flex items-center justify-center"
>
    <svg className="w-5 h-5 mr-2" viewBox="0 0 24 24">{/* SVG paths */}</svg>
    Continue with Google
</a>

{/* Facebook Login Button */}
<a
    href="/auth/facebook"
    className="btn btn-primary w-full"
    style={{backgroundColor: '#1877F2'}}
>
    <svg className="w-5 h-5 mr-2" fill="white" viewBox="0 0 24 24">{/* SVG paths */}</svg>
    Continue with Facebook
</a>
```

---

## ✅ Step 10: Test the Integration

### Test Google Login:

1. **Start your server:**
   ```bash
   php artisan serve
   ```

2. **Visit:** http://localhost:8000/login

3. **Click** "Continue with Google" button

4. **You should see:**
   - Redirected to Google login page
   - Prompted to select Google account
   - Redirected back to your app
   - Logged in automatically

5. **Check database:**
   ```sql
   SELECT id, name, email, google_id FROM users ORDER BY id DESC LIMIT 1;
   ```
   Should show your new user with google_id populated.

### Test Facebook Login:

1. **Click** "Continue with Facebook" button

2. **You should see:**
   - Redirected to Facebook login page
   - Prompted to authorize app
   - Redirected back to your app
   - Logged in automatically

3. **Check database:**
   ```sql
   SELECT id, name, email, facebook_id FROM users ORDER BY id DESC LIMIT 1;
   ```

---

## 🐛 Troubleshooting

### Error: "Invalid redirect URI"

**Cause:** Redirect URI in Google/Facebook doesn't match your app.

**Fix:**
- Double-check URLs in Google Cloud Console / Facebook App
- Make sure they match exactly: `http://localhost:8000/auth/google/callback`
- No trailing slashes

### Error: "Client authentication failed"

**Cause:** Wrong Client ID or Client Secret

**Fix:**
- Re-copy credentials from Google/Facebook
- Make sure no extra spaces in `.env` file
- Run `php artisan config:clear`

### Error: "Class Socialite not found"

**Cause:** Laravel Socialite not installed

**Fix:**
```bash
composer require laravel/socialite
php artisan config:clear
```

### Error: "Column google_id doesn't exist"

**Cause:** Migration not run

**Fix:**
```bash
php artisan migrate
```

### User redirected to /home instead of /dashboard

**Fix:** Update `RouteServiceProvider.php`:
```php
public const HOME = '/dashboard';
```

---

## 🔒 Security Best Practices

1. **Never commit credentials:**
   - Add `.env` to `.gitignore`
   - Use `.env.example` with placeholder values

2. **Use HTTPS in production:**
   - Update redirect URIs to `https://yourdomain.com`

3. **Validate email:**
   - Social login emails are already verified
   - Set `email_verified_at` automatically

4. **Rate limiting:**
   - Add throttle middleware to auth routes

---

## 🚀 Production Deployment Checklist

Before going live:

- [ ] Update Google OAuth redirect URIs with production domain
- [ ] Update Facebook OAuth redirect URIs with production domain
- [ ] Make Facebook App public (complete App Review)
- [ ] Add Privacy Policy URL to Facebook App
- [ ] Add Terms of Service URL to Facebook App
- [ ] Test social login on production domain
- [ ] Monitor error logs for failed auth attempts

---

## 📊 Testing Checklist

- [ ] Google login works on localhost
- [ ] Facebook login works on localhost
- [ ] New users created automatically
- [ ] Existing users linked to social account
- [ ] Profile pictures imported from social accounts
- [ ] Email verification auto-completed
- [ ] Users redirected to dashboard after login
- [ ] Avatar displays in user profile

---

## 🎉 Success!

Once all tests pass, social login is fully integrated!

**What You've Achieved:**
- ✅ Google OAuth working
- ✅ Facebook OAuth working
- ✅ Seamless user registration
- ✅ Better user experience

**Next Integration:** Payment Gateway or Email Service?

---

## 📚 Additional Resources

- [Laravel Socialite Docs](https://laravel.com/docs/10.x/socialite)
- [Google OAuth Setup](https://console.cloud.google.com/)
- [Facebook App Dashboard](https://developers.facebook.com/apps/)
