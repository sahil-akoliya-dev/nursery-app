<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total',
        'subtotal',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate the total price of all items in the cart.
     */
    public function calculateTotal()
    {
        $this->subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        // Add shipping, tax, etc. here if needed
        $this->total = $this->subtotal;
        
        $this->save();
    }

    /**
     * Get the total number of items in the cart.
     */
    public function getItemCountAttribute()
    {
        return $this->items->sum('quantity');
    }
}