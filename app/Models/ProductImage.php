<?php
// app/Models/ProductImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id', 'color_id', 'file_id', 'url', 'alt_text', 'is_primary', 'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ─── Relationships ─────────────────────────────────
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // ─── Accessors ─────────────────────────────────────
    
    public function getImageUrl($width = null, $height = null)
    {
        if ($this->url) {
            return $this->url;
        }
        return asset('images/placeholder-product.jpg');
    }

    public function getThumbUrlAttribute()
    {
        return $this->getImageUrl(100, 120);
    }

    public function getCardUrlAttribute()
    {
        return $this->getImageUrl(300, 380);
    }
}
