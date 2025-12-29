<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\PasswordResetRequest;
use App\Http\Requests\Api\PasswordForgotRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
        ]);

        // Assign customer role (if role exists, create if not)
        try {
            // Check if role exists, create if not
            $role = \Spatie\Permission\Models\Role::firstOrCreate(
                ['name' => 'customer', 'guard_name' => 'web']
            );
            $user->assignRole('customer');
        } catch (\Exception $e) {
            // Log error but don't fail registration if role doesn't exist
            Log::warning('Failed to assign customer role: ' . $e->getMessage());
        }

        // Award signup bonus (50 points)
        try {
            // Resolve service manually since we are in a controller method and didn't inject it in constructor
            // Ideally, inject in constructor, but for this specific fix, resolve it.
            $loyaltyService = app(\App\Services\LoyaltyService::class);
            $loyaltyService->awardPoints($user->id, 50, 'signup_bonus', null, 'Welcome Bonus');
        } catch (\Exception $e) {
            // Log error but don't fail registration
            Log::error('Failed to award signup points: ' . $e->getMessage());
        }

        // Generate API token (24-hour expiry by default, configurable via SANCTUM_EXPIRATION)
        $expirationMinutes = config('sanctum.expiration', 1440);
        $token = $user->createToken('api-token', ['*'], now()->addMinutes($expirationMinutes));

        return $this->successResponse([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token->plainTextToken,
            'token_expires_at' => $token->accessToken->expires_at->toIso8601String(),
            'loyalty_points' => $user->fresh()->current_loyalty_points,
        ], 'Registration successful', 201);
    }

    /**
     * Login user and generate API token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Check rate limiting (5 attempts per minute)
        $this->ensureIsNotRateLimited($request);

        // Attempt authentication
        $remember = $request->boolean('remember', false);
        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            RateLimiter::hit($this->throttleKey($request));

            return $this->errorResponse(
                'INVALID_CREDENTIALS',
                'The email or password you entered is incorrect. Please check your credentials and try again.',
                401
            );
        }

        // Clear rate limit on successful login
        RateLimiter::clear($this->throttleKey($request));

        $user = Auth::user();

        // Eager load necessary relationships
        $user->load([
            'vendor', // Load vendor profile
            'roles.permissions', // Load role permissions
            'permissions' // Load direct permissions
        ]);

        // Revoke all existing tokens (optional - for security)
        // $user->tokens()->delete();

        // Generate new API token (24-hour expiry by default, configurable via SANCTUM_EXPIRATION)
        // If "remember" is true, extend token expiry
        $expirationMinutes = $remember ? 43200 : config('sanctum.expiration', 1440); // 30 days vs 24 hours
        $token = $user->createToken('api-token', ['*'], now()->addMinutes($expirationMinutes));

        // Get roles and permissions
        try {
            $roles = $user->getRoleNames(); // Returns collection of strings
            $permissions = $user->getAllPermissions()->pluck('name');
        } catch (\Exception $e) {
            $roles = collect([$user->role]);
            $permissions = collect([]);
        }

        // Construct user data
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role, // Legacy fallback
            'roles' => $user->roles, // Spatie roles with permissions loaded
            'permissions' => $permissions,
            'vendor' => $user->vendor, // Vendor profile if exists
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ];

        return $this->successResponse([
            'message' => 'Login successful',
            'user' => $userData,
            'token' => $token->plainTextToken,
            'token_expires_at' => $token->accessToken->expires_at->toIso8601String(),
        ], 'Login successful');
    }

    /**
     * Logout user and revoke token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Safely get roles and permissions
            try {
                $roles = $user->getRoleNames();
                $permissions = $user->getAllPermissions()->pluck('name');
            } catch (\Exception $e) {
                // If Spatie Permission is not configured, use defaults
                $roles = collect([$user->role ?? 'customer']);
                $permissions = collect([]);
                Log::warning('Roles/permissions not available: ' . $e->getMessage());
            }



            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'customer',
                    'roles' => $roles,
                    'permissions' => $permissions,
                    'phone' => $user->phone,
                    'country_code' => $user->country_code,
                    'date_of_birth' => $user->date_of_birth,
                    'address' => $user->address,
                    'avatar' => $user->avatar,
                    'created_at' => is_string($user->created_at) ? $user->created_at : $user->created_at->toIso8601String(),
                    'updated_at' => is_string($user->updated_at) ? $user->updated_at : $user->updated_at->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Get User Error: ' . $e->getMessage());
            return $this->serverErrorResponse('Failed to retrieve user information.');
        }
    }

    /**
     * Send password reset link
     *
     * @param PasswordForgotRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(PasswordForgotRequest $request): JsonResponse
    {
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent to your email.',
                ]);
            }

            return $this->badRequestResponse(
                'We were unable to send a password reset link to this email address. Please verify your email address and try again.',
                'PASSWORD_RESET_FAILED'
            );
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            // Return success even if email fails (for testing purposes)
            // In production, you might want to return an error
            return response()->json([
                'success' => true,
                'message' => 'If the email exists, a password reset link has been sent.',
            ]);
        }
    }

    /**
     * Reset password
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset successful.',
            ]);
        }

        return $this->badRequestResponse(
            'The password reset token is invalid or has expired. Please request a new password reset link.',
            'PASSWORD_RESET_FAILED'
        );
    }

    /**
     * Refresh authentication token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke old token
        $request->user()->currentAccessToken()->delete();

        // Generate new token (24-hour expiry by default, configurable via SANCTUM_EXPIRATION)
        $expirationMinutes = config('sanctum.expiration', 1440);
        $token = $user->createToken('api-token', ['*'], now()->addMinutes($expirationMinutes));

        return response()->json([
            'success' => true,
            'token' => $token->plainTextToken,
            'token_expires_at' => $token->accessToken->expires_at->toIso8601String(),
        ]);
    }

    /**
     * Ensure the login request is not rate limited
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->status(429);
        }
    }

    /**
     * Get the rate limiting throttle key for the request
     *
     * @param Request $request
     * @return string
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }
}

