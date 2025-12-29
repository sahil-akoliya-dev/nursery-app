<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\Mail\PlantCareReminder;
use App\Models\PlantCareReminder as PlantCareReminderModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class PlantCareReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_plant_care_reminder_email_can_be_sent(): void
    {
        $user = User::factory()->create();
        $reminder = PlantCareReminderModel::factory()->create([
            'user_id' => $user->id,
            'title' => 'Water your plant',
        ]);

        Mail::fake();

        Mail::to($user->email)->send(new PlantCareReminder($reminder));

        Mail::assertSent(PlantCareReminder::class, function ($mail) use ($reminder) {
            return $mail->reminder->id === $reminder->id;
        });
    }
}

