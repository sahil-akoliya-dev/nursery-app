<?php

namespace App\Console\Commands;

use App\Models\PlantCareReminder;
use App\Mail\PlantCareReminder as PlantCareReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPlantCareReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plant-care:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send plant care reminder emails for due reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for due plant care reminders...');

        // Find reminders due today or overdue that haven't been completed
        $reminders = PlantCareReminder::where('scheduled_date', '<=', now())
            ->where('is_completed', false)
            ->where('is_active', true)
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($reminders as $reminder) {
            if (!$reminder->user) {
                continue;
            }

            try {
                Mail::to($reminder->user->email)->send(new PlantCareReminderMail($reminder));
                $sentCount++;
                $this->info("Sent reminder to {$reminder->user->email}: {$reminder->title}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to {$reminder->user->email}: " . $e->getMessage());
            }
        }

        $this->info("Sent {$sentCount} plant care reminder emails");
        return 0;
    }
}

