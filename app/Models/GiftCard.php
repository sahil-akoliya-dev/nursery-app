<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $fillable = [
        'code',
        'initial_value',
        'current_balance',
        'user_id',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'initial_value' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usages()
    {
        return $this->hasMany(GiftCardUsage::class);
    }

    public function isValid()
    {
        if (!$this->is_active)
            return false;
        if ($this->current_balance <= 0)
            return false;
        if ($this->expires_at && $this->expires_at->isPast())
            return false;
        return true;
    }
}
