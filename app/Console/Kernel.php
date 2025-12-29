<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send abandoned cart reminders daily
        $schedule->command('cart:send-reminders')->daily();

        // Clean expired loyalty points daily
        $schedule->command('loyalty:clean-expired')->daily();

        // Send plant care reminders daily
        $schedule->command('plant-care:send-reminders')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

