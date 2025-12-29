<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user registration
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
                'token',
                'token_expires_at',
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'role' => 'customer',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'customer',
        ]);

        // Verify token exists
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user->tokens()->first());
    }

    /**
     * Test registration validation errors
     */
    public function test_registration_requires_valid_data(): void
    {
        // Missing required fields
        $response = $this->postJson('/api/auth/register', []);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'error' => [
                    'code',
                    'message',
                    'details',
                ]
            ]);

        // Invalid email
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(422);

        // Password mismatch
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);
        $response->assertStatus(422);

        // Duplicate email
        User::factory()->create(['email' => 'existing@example.com']);
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(422);
    }

    /**
     * Test user login
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'two_factor_enabled',
                ],
                'token',
                'token_expires_at',
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'email' => 'test@example.com',
                ]
            ]);

        // Verify token was created
        $this->assertNotNull($user->fresh()->tokens()->first());
    }

    /**
     * Test login with invalid credentials
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'success',
                'error' => [
                    'code',
                    'message',
                ]
            ])
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                ]
            ]);
    }

    /**
     * Test login rate limiting
     */
    public function test_login_is_rate_limited(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Attempt login 6 times (limit is 5)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        // Should be rate limited
        $response->assertStatus(429);
    }

    /**
     * Test get authenticated user
     */
    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'phone',
                    'two_factor_enabled',
                ]
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ]
            ]);
    }

    /**
     * Test logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->assertNotNull($user->tokens()->first());

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);

        // Token should be deleted
        $this->assertNull($user->fresh()->tokens()->first());
    }

    /**
     * Test token refresh
     */
    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();
        $oldToken = $user->createToken('api-token')->plainTextToken;

        // Get the old token ID before refresh
        $oldTokenId = $user->tokens()->first()->id;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $oldToken)
            ->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'token',
                'token_expires_at',
            ]);

        // Old token should be deleted - check by ID
        $this->assertNull($user->fresh()->tokens()->find($oldTokenId));

        // New token should exist
        $this->assertCount(1, $user->fresh()->tokens);
    }

    /**
     * Test protected routes require authentication
     */
    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->getJson('/api/auth/user');
        $response->assertStatus(401);

        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(401);
    }

    /**
     * Test password reset request
     */
    public function test_user_can_request_password_reset(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/auth/password/email', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test password reset validation
     */
    public function test_password_reset_requires_valid_token(): void
    {
        $response = $this->postJson('/api/auth/password/reset', [
            'email' => 'test@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'error' => [
                    'code',
                    'message',
                ]
            ]);
    }
}

