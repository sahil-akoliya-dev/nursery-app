<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'shipping_address',
        'billing_address',
        'notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format((float) $this->total_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get items relationship (alias for orderItems)
     */
    public function getItemsAttribute()
    {
        return $this->orderItems;
    }

    /**
     * Get progress percentage for order timeline
     */
    public function getProgressPercentageAttribute()
    {
        $progress = [
            'pending' => 25,
            'processing' => 50,
            'shipped' => 75,
            'delivered' => 100,
            'cancelled' => 0,
        ];

        return $progress[$this->status] ?? 0;
    }

    /**
     * Get status order for timeline steps
     */
    public function getStatusOrderAttribute()
    {
        $order = [
            'pending' => 1,
            'processing' => 2,
            'shipped' => 3,
            'delivered' => 4,
            'cancelled' => 0,
        ];

        return $order[$this->status] ?? 0;
    }

    /**
     * Get processing date
     */
    public function getProcessingAtAttribute()
    {
        if ($this->status_order >= 2) {
            return $this->updated_at;
        }
        return null;
    }

    /**
     * Get shipped date
     */
    public function getShippedAtAttribute()
    {
        if ($this->status_order >= 3) {
            return $this->updated_at;
        }
        return null;
    }

    /**
     * Get delivered date
     */
    public function getDeliveredAtAttribute()
    {
        if ($this->status_order >= 4) {
            return $this->updated_at;
        }
        return null;
    }
}