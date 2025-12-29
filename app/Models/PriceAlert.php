<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'target_price',
        'current_price',
        'is_active',
        'is_triggered',
        'triggered_at'
    ];

    protected $casts = [
        'target_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_triggered' => 'boolean',
        'triggered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
