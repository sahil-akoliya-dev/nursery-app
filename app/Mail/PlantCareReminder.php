<?php

namespace App\Mail;

use App\Models\PlantCareReminder as PlantCareReminderModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlantCareReminder extends Mailable
{
    use Queueable, SerializesModels;

    public PlantCareReminderModel $reminder;

    /**
     * Create a new message instance.
     *
     * @param PlantCareReminderModel $reminder
     */
    public function __construct(PlantCareReminderModel $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Plant Care Reminder: ' . $this->reminder->title)
            ->view('emails.plant-care-reminder')
            ->with([
                'reminder' => $this->reminder,
            ]);
    }
}

