<?php
// app/Models/Color.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name', 'label', 'value', 'type', 'code', 'hex', 'hex_code', 'is_active', 'sort_order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants')
                    ->withPivot('stock', 'price', 'sku');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Scopes
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    // Accessor
    public function getHexAttribute()
    {
        return $this->attributes['hex'] ?? $this->attributes['hex_code'] ?? '#cccccc';
    }

    public function getHexCodeAttribute()
    {
        return $this->attributes['hex_code'] ?? $this->attributes['hex'] ?? '#cccccc';
    }

    public function setHexCodeAttribute($value): void
    {
        $this->attributes['hex'] = $value;
    }
}
