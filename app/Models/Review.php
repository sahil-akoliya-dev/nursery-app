<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'title',
        'content',
        'images',
        'is_verified_purchase',
        'is_featured',
        'is_approved',
        'helpful_count',
        'not_helpful_count'
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function helpfulVotes(): MorphMany
    {
        return $this->morphMany(HelpfulVote::class, 'voteable');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getHelpfulnessPercentageAttribute()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        return $total > 0 ? round(($this->helpful_count / $total) * 100) : 0;
    }

    public function getRatingColorAttribute()
    {
        return match($this->rating) {
            5 => 'text-green-600',
            4 => 'text-green-500',
            3 => 'text-yellow-500',
            2 => 'text-orange-500',
            1 => 'text-red-500',
            default => 'text-neutral-400'
        };
    }

    public function incrementHelpful()
    {
        $this->increment('helpful_count');
    }

    public function incrementNotHelpful()
    {
        $this->increment('not_helpful_count');
    }
}

