<?php
// app/Models/Size.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'name', 'label', 'value', 'type', 'is_active', 'sort_order'
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
    public function getDisplayNameAttribute(): string
    {
        return $this->label ? $this->name . ' (' . $this->label . ')' : $this->name;
    }
}