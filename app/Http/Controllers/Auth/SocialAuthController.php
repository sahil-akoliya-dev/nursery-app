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

            // Redirect based on user role
            $redirectUrl = $this->getRedirectUrl($user);

            return redirect($redirectUrl)->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google. Please try again.');
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
            'role' => 'customer', // Default role
        ]);
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl($user)
    {
        // Check role and redirect accordingly
        if (in_array($user->role, ['super_admin', 'admin', 'manager'])) {
            return '/admin-dashboard';
        }

        if ($user->role === 'vendor') {
            // Load vendor relationship if not already loaded
            if (!$user->relationLoaded('vendor')) {
                $user->load('vendor');
            }

            if ($user->vendor) {
                if ($user->vendor->status === 'approved') {
                    return '/vendor-dashboard';
                } elseif ($user->vendor->status === 'pending') {
                    return '/vendor-pending';
                }
            }
            return '/';
        }

        // Default for customers
        return '/profile';
    }
}
