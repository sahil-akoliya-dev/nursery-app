<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\LoyaltyService;
use App\Models\User;
use App\Models\LoyaltyPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoyaltyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LoyaltyService $loyaltyService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loyaltyService = new LoyaltyService();
        $this->user = User::factory()->create();
    }

    public function test_can_get_loyalty_balance(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addYear(),
        ]);

        $balance = $this->loyaltyService->getBalance($this->user->id);

        $this->assertEquals(100, $balance);
    }

    public function test_balance_excludes_expired_points(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addYear(),
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 150,
            'expires_at' => now()->subDay(), // Expired
        ]);

        $balance = $this->loyaltyService->getBalance($this->user->id);

        $this->assertEquals(100, $balance); // Only active points
    }

    public function test_balance_excludes_redeemed_points(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 200,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 200,
            'expires_at' => now()->addYear(),
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => -100, // Redeemed
            'type' => 'redeemed',
            'source' => 'redeem',
            'points_balance' => 100,
            'expires_at' => null,
        ]);

        $balance = $this->loyaltyService->getBalance($this->user->id);

        $this->assertEquals(100, $balance);
    }

    public function test_can_get_total_earned(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'review',
            'points_balance' => 150,
        ]);

        $totalEarned = $this->loyaltyService->getTotalEarned($this->user->id);

        $this->assertEquals(150, $totalEarned);
    }

    public function test_can_get_total_redeemed(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => -100,
            'type' => 'redeemed',
            'source' => 'redeem',
            'points_balance' => 0,
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => -50,
            'type' => 'redeemed',
            'source' => 'redeem',
            'points_balance' => -50,
        ]);

        $totalRedeemed = $this->loyaltyService->getTotalRedeemed($this->user->id);

        $this->assertEquals(150, $totalRedeemed); // Absolute value
    }

    public function test_can_get_points_expiring_soon(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addDays(20), // Expiring soon (< 30 days)
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 150,
            'expires_at' => now()->addDays(40), // Not expiring soon
        ]);

        $expiringSoon = $this->loyaltyService->getPointsExpiringSoon($this->user->id);

        $this->assertEquals(100, $expiringSoon);
    }

    public function test_can_calculate_tier(): void
    {
        $bronzeTier = $this->loyaltyService->calculateTier(500);
        $this->assertEquals('bronze', $bronzeTier);

        $silverTier = $this->loyaltyService->calculateTier(1500);
        $this->assertEquals('silver', $silverTier);

        $goldTier = $this->loyaltyService->calculateTier(6000);
        $this->assertEquals('gold', $goldTier);

        $platinumTier = $this->loyaltyService->calculateTier(12000);
        $this->assertEquals('platinum', $platinumTier);
    }

    public function test_can_get_tier_info(): void
    {
        $bronzeInfo = $this->loyaltyService->getTierInfo('bronze');
        $this->assertEquals('Bronze', $bronzeInfo['name']);
        $this->assertEquals(0, $bronzeInfo['min_points']);
        $this->assertEquals(999, $bronzeInfo['max_points']);

        $silverInfo = $this->loyaltyService->getTierInfo('silver');
        $this->assertEquals('Silver', $silverInfo['name']);
        $this->assertEquals(1000, $silverInfo['min_points']);
        $this->assertEquals(4999, $silverInfo['max_points']);

        $goldInfo = $this->loyaltyService->getTierInfo('gold');
        $this->assertEquals('Gold', $goldInfo['name']);
        $this->assertEquals(5000, $goldInfo['min_points']);
        $this->assertEquals(9999, $goldInfo['max_points']);

        $platinumInfo = $this->loyaltyService->getTierInfo('platinum');
        $this->assertEquals('Platinum', $platinumInfo['name']);
        $this->assertEquals(10000, $platinumInfo['min_points']);
        $this->assertNull($platinumInfo['max_points']);
    }

    public function test_can_get_next_tier(): void
    {
        $nextTier = $this->loyaltyService->getNextTier(500);
        $this->assertNotNull($nextTier);
        $this->assertEquals('silver', $nextTier['name']);
        $this->assertEquals(1000, $nextTier['min_points']);

        $nextTierGold = $this->loyaltyService->getNextTier(2000);
        $this->assertNotNull($nextTierGold);
        $this->assertEquals('gold', $nextTierGold['name']);

        $noNextTier = $this->loyaltyService->getNextTier(15000);
        $this->assertNull($noNextTier); // Already at highest tier
    }

    public function test_can_get_points_to_next_tier(): void
    {
        $pointsNeeded = $this->loyaltyService->getPointsToNextTier(500);
        $this->assertEquals(500, $pointsNeeded); // Need 500 more to reach 1000 (Silver)

        $pointsNeededGold = $this->loyaltyService->getPointsToNextTier(7500);
        $this->assertEquals(2500, $pointsNeededGold); // Need 2500 more to reach 10000 (Platinum)

        $pointsNeededMax = $this->loyaltyService->getPointsToNextTier(15000);
        $this->assertEquals(0, $pointsNeededMax); // Already at max tier
    }

    public function test_can_redeem_points(): void
    {
        // Create points
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 500,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 500,
            'expires_at' => now()->addYear(),
        ]);

        $result = $this->loyaltyService->redeemPoints($this->user->id, 200);

        $this->assertEquals(300, $result['new_balance']);
        $this->assertEquals(2.00, $result['discount_amount']); // 200 points * 0.01
        $this->assertEquals(200, $result['points_redeemed']);

        // Verify redemption record was created
        $redemption = LoyaltyPoint::where('user_id', $this->user->id)
            ->where('type', 'redeemed')
            ->latest()
            ->first();

        $this->assertNotNull($redemption);
        $this->assertEquals(-200, $redemption->points);
    }

    public function test_cannot_redeem_insufficient_points(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 50,
            'expires_at' => now()->addYear(),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient points');

        $this->loyaltyService->redeemPoints($this->user->id, 200);
    }

    public function test_cannot_redeem_below_minimum(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 500,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 500,
            'expires_at' => now()->addYear(),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Minimum redemption');

        $this->loyaltyService->redeemPoints($this->user->id, 50); // Below 100 minimum
    }

    public function test_cannot_redeem_exact_minimum_allowed(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addYear(),
        ]);

        $result = $this->loyaltyService->redeemPoints($this->user->id, 100); // Exactly minimum

        $this->assertEquals(0, $result['new_balance']);
        $this->assertEquals(1.00, $result['discount_amount']);
    }

    public function test_can_get_points_history(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'review',
            'points_balance' => 150,
        ]);

        $history = $this->loyaltyService->getHistory($this->user->id, [], 10);

        $this->assertCount(2, $history->items());
    }

    public function test_can_filter_history_by_type(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => -50,
            'type' => 'redeemed',
            'source' => 'redeem',
            'points_balance' => 50,
        ]);

        $history = $this->loyaltyService->getHistory($this->user->id, ['type' => 'earned'], 10);

        $this->assertCount(1, $history->items());
        $this->assertEquals('earned', $history->items()[0]->type);
    }

    public function test_can_filter_history_by_source(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'review',
            'points_balance' => 150,
        ]);

        $history = $this->loyaltyService->getHistory($this->user->id, ['source' => 'purchase'], 10);

        $this->assertCount(1, $history->items());
        $this->assertEquals('purchase', $history->items()[0]->source);
    }

    public function test_can_filter_history_by_status_active(): void
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addYear(), // Active
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 50,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 150,
            'expires_at' => now()->subDay(), // Expired
        ]);

        $history = $this->loyaltyService->getHistory($this->user->id, ['status' => 'active'], 10);

        $this->assertCount(1, $history->items());
    }

    public function test_can_calculate_redemption_value(): void
    {
        $value = $this->loyaltyService->calculateRedemptionValue(500);
        $this->assertEquals(5.00, $value);

        $value2 = $this->loyaltyService->calculateRedemptionValue(100);
        $this->assertEquals(1.00, $value2);

        $value3 = $this->loyaltyService->calculateRedemptionValue(1250);
        $this->assertEquals(12.50, $value3);
    }

    public function test_can_get_minimum_redemption(): void
    {
        $minimum = $this->loyaltyService->getMinimumRedemption();
        $this->assertEquals(100, $minimum);
    }

    public function test_redeem_uses_oldest_points_first(): void
    {
        // Create points with different expiration dates
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 100,
            'expires_at' => now()->addMonths(6), // Expires sooner
            'created_at' => now()->subMonths(6),
        ]);

        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'points_balance' => 200,
            'expires_at' => now()->addYear(), // Expires later
            'created_at' => now()->subMonths(3),
        ]);

        // Redeem 150 points
        $result = $this->loyaltyService->redeemPoints($this->user->id, 150);

        $this->assertEquals(50, $result['new_balance']);
    }
}
