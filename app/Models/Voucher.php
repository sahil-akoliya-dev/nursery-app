<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->is_active)
            return false;
        if ($this->expires_at && $this->expires_at->isPast())
            return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit)
            return false;
        return true;
    }
}
