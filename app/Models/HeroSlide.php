<?php
// app/Models/HeroSlide.php - Simple version

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'mobile_image',
        'button_text',
        'button_link',
        'alt_text',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Get full image URL from ImageKit
    public function getImageUrl()
    {
        if (empty($this->image)) {
            return asset('images/placeholder-slide.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
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