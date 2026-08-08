<?php
// app/Models/HeroSlide.php - Simple version

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'media_type',
        'image',
        'mobile_image',
        'button_text',
        'button_link',
        'product_id',
        'alt_text',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'product_id' => 'integer',
        'sort_order' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getMediaTypeAttribute($value): string
    {
        return $value ?: 'image';
    }

    // Get full image URL from ImageKit
    public function getImageUrl()
    {
        if (empty($this->image)) {
            return asset('images/placeholder-slide.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        if (str_starts_with($this->image, '/storage/') || str_starts_with($this->image, 'storage/')) {
            return asset(ltrim($this->image, '/'));
        }

        return "https://ik.imagekit.io/zjhpv2mbz/" . $this->image;
    }

    // Get mobile image URL
    public function getMobileImageUrl()
    {
        if (empty($this->mobile_image)) {
            return $this->getImageUrl();
        }

        if (filter_var($this->mobile_image, FILTER_VALIDATE_URL)) {
            return $this->mobile_image;
        }

        if (str_starts_with($this->mobile_image, '/storage/') || str_starts_with($this->mobile_image, 'storage/')) {
            return asset(ltrim($this->mobile_image, '/'));
        }

        return "https://ik.imagekit.io/zjhpv2mbz/" . $this->mobile_image;
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl();
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->getMobileImageUrl();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
