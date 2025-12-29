<?php

namespace Tests\Unit\Console;

use Tests\TestCase;
use App\Console\Commands\CleanExpiredLoyaltyPoints;
use App\Models\LoyaltyPoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleanExpiredLoyaltyPointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_marks_expired_points(): void
    {
        $user = User::factory()->create();

        $expiredPoint = LoyaltyPoint::create([
            'user_id' => $user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'expires_at' => now()->subDay(),
        ]);

        $this->artisan('loyalty:clean-expired')
            ->expectsOutput('Cleaning expired loyalty points...')
            ->assertExitCode(0);

        $expiredPoint->refresh();
        $this->assertEquals('expired', $expiredPoint->type);
    }
}

