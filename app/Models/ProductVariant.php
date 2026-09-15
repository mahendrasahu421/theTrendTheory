<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'size',
        'color',
        'color_hex',
        'sku',
        'price',           // ✅ Selling Price
        'original_price',  // ✅ MRP
        'cost_price',
        'stock',
        'image',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];


    // ─── Relationships ─────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // ─── Accessors ─────────────────────────────────────

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return url('/storage/' . ltrim($this->image, '/'));
        }
        return $this->product->main_image;
    }

    public function getPriceAttribute($value)
    {
        return $value ?? $this->product->price;
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getFormattedStockAttribute()
    {
        if ($this->stock <= 0)
            return 'Out of Stock';
        if ($this->stock <= 5)
            return 'Only ' . $this->stock . ' left';
        return 'In Stock';

    }

    public function getDiscountPercentAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }
}
