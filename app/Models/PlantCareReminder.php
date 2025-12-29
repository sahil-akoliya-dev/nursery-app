<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PlantCareReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plant_care_guide_id',
        'plant_id',
        'reminder_type', // watering, fertilizing, repotting, pruning, general
        'title',
        'description',
        'scheduled_date',
        'frequency', // daily, weekly, monthly, seasonal, custom
        'frequency_value', // number for custom frequency
        'is_completed',
        'completed_at',
        'is_active',
        'notification_sent'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_active' => 'boolean',
        'notification_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function healthLogs(): HasMany
    {
        return $this->hasMany(HealthLog::class);
    }

    public function plantCareGuide(): BelongsTo
    {
        return $this->belongsTo(PlantCareGuide::class);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('scheduled_date', '<=', Carbon::now()->addDays($days))
            ->where('scheduled_date', '>=', Carbon::now())
            ->where('is_completed', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', Carbon::now())
            ->where('is_completed', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('reminder_type', $type);
    }

    public function getStatusAttribute()
    {
        if ($this->is_completed) {
            return 'completed';
        }

        if ($this->scheduled_date < Carbon::now()) {
            return 'overdue';
        }

        if ($this->scheduled_date <= Carbon::now()->addDays(3)) {
            return 'due_soon';
        }

        return 'upcoming';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'completed' => 'text-green-600 bg-green-100',
            'overdue' => 'text-red-600 bg-red-100',
            'due_soon' => 'text-yellow-600 bg-yellow-100',
            'upcoming' => 'text-blue-600 bg-blue-100'
        ];

        return $colors[$this->status] ?? $colors['upcoming'];
    }

    public function getIconAttribute()
    {
        $icons = [
            'watering' => 'fas fa-tint',
            'fertilizing' => 'fas fa-seedling',
            'repotting' => 'fas fa-seedling',
            'pruning' => 'fas fa-cut',
            'general' => 'fas fa-leaf'
        ];

        return $icons[$this->reminder_type] ?? $icons['general'];
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => Carbon::now()
        ]);

        // Schedule next reminder if recurring
        if ($this->frequency !== 'one_time') {
            $this->scheduleNextReminder();
        }
    }

    private function scheduleNextReminder()
    {
        $nextDate = match ($this->frequency) {
            'daily' => Carbon::parse($this->scheduled_date)->addDay(),
            'weekly' => Carbon::parse($this->scheduled_date)->addWeek(),
            'monthly' => Carbon::parse($this->scheduled_date)->addMonth(),
            'seasonal' => Carbon::parse($this->scheduled_date)->addMonths(3),
            'custom' => Carbon::parse($this->scheduled_date)->addDays($this->frequency_value),
            default => null
        };

        if ($nextDate) {
            static::create([
                'user_id' => $this->user_id,
                'plant_care_guide_id' => $this->plant_care_guide_id,
                'plant_id' => $this->plant_id,
                'reminder_type' => $this->reminder_type,
                'title' => $this->title,
                'description' => $this->description,
                'scheduled_date' => $nextDate,
                'frequency' => $this->frequency,
                'frequency_value' => $this->frequency_value,
                'is_active' => true,
                'notification_sent' => false
            ]);
        }
    }
}

