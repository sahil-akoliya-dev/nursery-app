<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantCareGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'plant_type',
        'title',
        'description',
        'care_level', // beginner, intermediate, expert
        'light_requirements',
        'water_needs',
        'humidity_requirements',
        'temperature_range',
        'soil_type',
        'fertilizer_schedule',
        'repotting_frequency',
        'pruning_instructions',
        'common_problems',
        'seasonal_care',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'seasonal_care' => 'array',
        'common_problems' => 'array',
        'temperature_range' => 'array',
        'is_active' => 'boolean',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function careReminders(): HasMany
    {
        return $this->hasMany(PlantCareReminder::class);
    }

    public function careTips()
    {
        // Return empty collection for now - tips are stored in seasonal_care JSON field
        return collect();
    }

    public function scopeByCareLevel($query, $level)
    {
        return $query->where('care_level', $level);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDifficultyBadgeAttribute()
    {
        $badges = [
            'beginner' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Beginner'],
            'intermediate' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Intermediate'],
            'expert' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Expert']
        ];

        return $badges[$this->care_level] ?? $badges['beginner'];
    }
}
