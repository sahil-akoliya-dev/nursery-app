<?php

namespace App\Console\Commands;

use App\Models\LoyaltyPoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredLoyaltyPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired loyalty points as expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning expired loyalty points...');

        $expiredCount = LoyaltyPoint::where('type', 'earned')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->where('type', '!=', 'expired')
            ->update([
                'type' => 'expired',
            ]);

        $this->info("Marked {$expiredCount} loyalty points as expired");
        return 0;
    }
}

