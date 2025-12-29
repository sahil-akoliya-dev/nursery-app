<?php

namespace App\Console\Commands;

use App\Models\CartItem;
use App\Models\Order;
use App\Mail\AbandonedCartReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendAbandonedCartEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send abandoned cart reminder emails to users who haven\'t completed their purchase';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for abandoned carts...');

        // Find users with abandoned carts (created 3 days ago, no order placed)
        $abandonedCarts = CartItem::where('created_at', '>=', now()->subDays(3))
            ->where('created_at', '<=', now()->subDay())
            ->with(['user', 'item'])
            ->get()
            ->groupBy('user_id');

        $sentCount = 0;

        foreach ($abandonedCarts as $userId => $items) {
            $user = $items->first()->user;
            
            if (!$user) {
                continue;
            }

            // Check if user placed an order in the last 3 days
            $hasRecentOrder = Order::where('user_id', $userId)
                ->where('created_at', '>=', now()->subDays(3))
                ->exists();

            if ($hasRecentOrder) {
                continue; // Skip if order was placed
            }

            // Calculate cart total
            $cartTotal = $items->sum(function($item) {
                return $item->quantity * $item->price;
            });

            try {
                Mail::to($user->email)->send(new AbandonedCartReminder($user, $items, $cartTotal));
                $sentCount++;
                $this->info("Sent reminder to {$user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$sentCount} reminder emails");
        return 0;
    }
}

