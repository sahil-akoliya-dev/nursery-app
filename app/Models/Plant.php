<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'scientific_name',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock_quantity',
        'in_stock',
        'is_featured',
        'is_active',
        'sku',
        'images',
        'care_instructions',
        'plant_characteristics',
        'plant_type',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'care_instructions' => 'array',
        'plant_characteristics' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): MorphMany
    {
        return $this->morphMany(CartItem::class, 'item');
    }

    public function wishlistItems(): MorphMany
    {
        return $this->morphMany(WishlistItem::class, 'item');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('plant_type', $type);
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format((float) $this->price, 2);
    }

    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? '₹' . number_format((float) $this->sale_price, 2) : null;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }
}





