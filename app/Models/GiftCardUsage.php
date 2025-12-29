<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardUsage extends Model
{
    protected $fillable = [
        'gift_card_id',
        'order_id',
        'amount_used'
    ];

    protected $casts = [
        'amount_used' => 'decimal:2'
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
