<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
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

    public function getFormattedImagesAttribute()
    {
        if (empty($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            // If it's a data URI (Base64), return as is
            if (str_starts_with($image, 'data:image')) {
                return $image;
            }

            // If it's already a full URL, return as is
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            // If path starts with /, it's already a public path
            if (str_starts_with($image, '/')) {
                return asset($image);
            }

            // Otherwise, assume it's in storage
            return asset('storage/' . $image);
        }, $this->images);
    }
}