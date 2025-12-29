<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'source',
        'order_id',
        'review_id',
        'description',
        'points_balance',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that earned these points.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that generated these points.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the review that generated these points.
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Scope for earned points.
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope for redeemed points.
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope for expired points.
     */
    public function scopeExpired($query)
    {
        return $query->where('type', 'expired');
    }

    /**
     * Scope for active points (not expired).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for points by source.
     */
    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get formatted type description.
     */
    public function getFormattedTypeAttribute(): string
    {
        $types = [
            'earned' => 'Points Earned',
            'redeemed' => 'Points Redeemed',
            'expired' => 'Points Expired',
            'bonus' => 'Bonus Points',
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get formatted source description.
     */
    public function getFormattedSourceAttribute(): string
    {
        $sources = [
            'purchase' => 'Purchase',
            'review' => 'Product Review',
            'referral' => 'Referral',
            'signup' => 'Account Signup',
            'redeem' => 'Points Redemption',
            'bonus' => 'Bonus Award',
        ];

        return $sources[$this->source] ?? ucfirst($this->source);
    }

    /**
     * Check if points are expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get time until expiration.
     */
    public function getExpiresInAttribute(): string
    {
        if (!$this->expires_at) {
            return 'Never';
        }

        return $this->expires_at->diffForHumans();
    }

    /**
     * Get points with sign (+ or -).
     */
    public function getSignedPointsAttribute(): string
    {
        return ($this->points >= 0 ? '+' : '') . $this->points;
    }
}