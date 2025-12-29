<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    private const MINIMUM_REDEMPTION = 100;
    private const POINTS_PER_DOLLAR = 1;
    private const DOLLARS_PER_POINT = 0.01;

    /**
     * Get user's loyalty points balance
     *
     * @param int $userId
     * @return int
     */
    public function getBalance(int $userId): int
    {
        return LoyaltyPoint::where('user_id', $userId)
            ->active()
            ->sum('points');
    }

    /**
     * Get total earned points
     *
     * @param int $userId
     * @return int
     */
    public function getTotalEarned(int $userId): int
    {
        return LoyaltyPoint::where('user_id', $userId)
            ->where('type', 'earned')
            ->sum('points');
    }

    /**
     * Get total redeemed points
     *
     * @param int $userId
     * @return int
     */
    public function getTotalRedeemed(int $userId): int
    {
        return abs(LoyaltyPoint::where('user_id', $userId)
            ->where('type', 'redeemed')
            ->sum('points'));
    }

    /**
     * Get points expiring soon (within 30 days)
     *
     * @param int $userId
     * @return int
     */
    public function getPointsExpiringSoon(int $userId): int
    {
        return LoyaltyPoint::where('user_id', $userId)
            ->active()
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->sum('points');
    }

    /**
     * Redeem loyalty points
     *
     * @param int $userId
     * @param int $points
     * @return array
     * @throws \Exception
     */
    public function redeemPoints(int $userId, int $points): array
    {
        if ($points < self::MINIMUM_REDEMPTION) {
            throw new \Exception("Minimum redemption is " . self::MINIMUM_REDEMPTION . " points");
        }

        $balance = $this->getBalance($userId);

        if ($balance < $points) {
            throw new \Exception("Insufficient points. You have {$balance} points available.");
        }

        // Calculate discount (1 point = $0.01)
        $discountAmount = round($points * self::DOLLARS_PER_POINT, 2);
        $newBalance = $balance - $points;

        DB::beginTransaction();

        try {
            LoyaltyPoint::create([
                'user_id' => $userId,
                'points' => -$points,
                'type' => 'redeemed',
                'source' => 'redeem',
                'points_balance' => $newBalance,
                'description' => "Redeemed {$points} points for \${$discountAmount} discount"
            ]);

            DB::commit();

            return [
                'new_balance' => $newBalance,
                'discount_amount' => $discountAmount,
                'points_redeemed' => $points,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Award loyalty points to user
     *
     * @param int $userId
     * @param int $points
     * @param string $type
     * @param int|null $orderId
     * @param string|null $description
     * @return LoyaltyPoint
     */
    public function awardPoints(int $userId, int $points, string $type = 'earned', ?int $orderId = null, ?string $description = null): LoyaltyPoint
    {
        $currentBalance = $this->getBalance($userId);

        return LoyaltyPoint::create([
            'user_id' => $userId,
            'points' => $points,
            'type' => $type,
            'source' => 'bonus',
            'order_id' => $orderId,
            'points_balance' => $currentBalance + $points,
            'description' => $description ?? 'Bonus points awarded',
            'expires_at' => now()->addMonths(12)
        ]);
    }

    /**
     * Get points history
     *
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHistory(int $userId, array $filters = [], int $perPage = 20)
    {
        $query = LoyaltyPoint::where('user_id', $userId)
            ->with(['order', 'review']);

        // Filter by type
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by source
        if (isset($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            switch ($filters['status']) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'expiring_soon':
                    $query->active()
                        ->where('expires_at', '<=', now()->addDays(30))
                        ->where('expires_at', '>', now());
                    break;
            }
        }

        $sortBy = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate(min($perPage, 50));
    }

    /**
     * Calculate user tier based on total points
     *
     * @param int $totalPoints
     * @return string
     */
    public function calculateTier(int $totalPoints): string
    {
        if ($totalPoints >= 10000) {
            return 'platinum';
        } elseif ($totalPoints >= 5000) {
            return 'gold';
        } elseif ($totalPoints >= 1000) {
            return 'silver';
        }
        return 'bronze';
    }

    /**
     * Get tier information
     *
     * @param string $tier
     * @return array
     */
    public function getTierInfo(string $tier): array
    {
        $tiers = [
            'bronze' => [
                'name' => 'Bronze',
                'min_points' => 0,
                'max_points' => 999,
                'discount_percentage' => 0,
                'benefits' => ['Earn points on purchases'],
            ],
            'silver' => [
                'name' => 'Silver',
                'min_points' => 1000,
                'max_points' => 4999,
                'discount_percentage' => 5,
                'benefits' => ['Earn points on purchases', '5% discount on all orders'],
            ],
            'gold' => [
                'name' => 'Gold',
                'min_points' => 5000,
                'max_points' => 9999,
                'discount_percentage' => 10,
                'benefits' => ['Earn points on purchases', '10% discount on all orders', 'Priority customer support'],
            ],
            'platinum' => [
                'name' => 'Platinum',
                'min_points' => 10000,
                'max_points' => null,
                'discount_percentage' => 15,
                'benefits' => ['Earn points on purchases', '15% discount on all orders', 'Priority customer support', 'Exclusive deals'],
            ],
        ];

        return $tiers[$tier] ?? $tiers['bronze'];
    }

    /**
     * Get next tier information
     *
     * @param int $currentPoints
     * @return array|null
     */
    public function getNextTier(int $currentPoints): ?array
    {
        $tiers = [
            ['name' => 'silver', 'min_points' => 1000],
            ['name' => 'gold', 'min_points' => 5000],
            ['name' => 'platinum', 'min_points' => 10000],
        ];

        foreach ($tiers as $tier) {
            if ($currentPoints < $tier['min_points']) {
                return [
                    'name' => $tier['name'],
                    'min_points' => $tier['min_points'],
                    'info' => $this->getTierInfo($tier['name']),
                ];
            }
        }

        return null; // Already at highest tier
    }

    /**
     * Get points needed to reach next tier
     *
     * @param int $currentPoints
     * @return int
     */
    public function getPointsToNextTier(int $currentPoints): int
    {
        $nextTier = $this->getNextTier($currentPoints);

        if (!$nextTier) {
            return 0; // Already at highest tier
        }

        return max(0, $nextTier['min_points'] - $currentPoints);
    }

    /**
     * Calculate redemption value
     *
     * @param int $points
     * @return float
     */
    public function calculateRedemptionValue(int $points): float
    {
        return round($points * self::DOLLARS_PER_POINT, 2);
    }

    /**
     * Get minimum redemption amount
     *
     * @return int
     */
    public function getMinimumRedemption(): int
    {
        return self::MINIMUM_REDEMPTION;
    }
}

