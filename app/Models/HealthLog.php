<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthLog extends Model
{
    protected $fillable = [
        'plant_care_reminder_id',
        'status',
        'notes',
        'photo_url'
    ];

    public function reminder()
    {
        return $this->belongsTo(PlantCareReminder::class, 'plant_care_reminder_id');
    }
}
