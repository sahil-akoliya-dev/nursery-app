<?php

namespace Tests\Unit\Console;

use Tests\TestCase;
use App\Console\Commands\SendPlantCareReminders;
use App\Models\PlantCareReminder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class SendPlantCareRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_reminders_for_due_tasks(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        
        PlantCareReminder::factory()->create([
            'user_id' => $user->id,
            'scheduled_date' => now()->subDay(),
            'is_completed' => false,
            'is_active' => true,
        ]);

        $this->artisan('plant-care:send-reminders')
            ->expectsOutput('Checking for due plant care reminders...')
            ->assertExitCode(0);

        Mail::assertSent(\App\Mail\PlantCareReminder::class);
    }

    public function test_command_skips_completed_reminders(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        
        PlantCareReminder::factory()->create([
            'user_id' => $user->id,
            'scheduled_date' => now()->subDay(),
            'is_completed' => true,
            'is_active' => true,
        ]);

        $this->artisan('plant-care:send-reminders')
            ->assertExitCode(0);

        Mail::assertNothingSent();
    }
}

