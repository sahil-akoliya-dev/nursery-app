<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\LoyaltyPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class LoyaltyApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_loyalty(): void
    {
        $response = $this->getJson('/api/loyalty');
        
        $response->assertStatus(401);
    }

    public function test_can_get_loyalty_overview(): void
    {
        Sanctum::actingAs($this->user);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 500,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 500,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->getJson('/api/loyalty');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'current_balance',
                'total_earned',
                'total_redeemed',
                'tier',
            ],
        ]);
    }

    public function test_can_get_loyalty_history(): void
    {
        Sanctum::actingAs($this->user);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
        ]);

        $response = $this->getJson('/api/loyalty/history');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'meta',
        ]);
    }

    public function test_can_redeem_loyalty_points(): void
    {
        Sanctum::actingAs($this->user);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 500,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 500,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/loyalty/redeem', [
            'points' => 200,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'new_balance' => 300,
                'discount_amount' => 2.00,
            ],
        ]);
    }

    public function test_cannot_redeem_insufficient_points(): void
    {
        Sanctum::actingAs($this->user);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 50,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/loyalty/redeem', [
            'points' => 200,
        ]);

        $response->assertStatus(400);
    }

    public function test_can_get_tier_information(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/loyalty/tier');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'current_tier',
                'tier_info',
                'next_tier',
            ],
        ]);
    }
}

