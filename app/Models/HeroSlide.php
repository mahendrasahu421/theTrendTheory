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

    // Get full image URL
    public function getImageUrl()
    {
        if (empty($this->image)) {
            return asset('images/placeholder-slide.jpg');
        }

        // Already a full URL (Cloudinary, etc.)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Absolute /storage/ path
        if (str_starts_with($this->image, '/storage/') || str_starts_with($this->image, '/')) {
            return url($this->image);
        }

        // Relative path like 'slides/file.jpg'
        return url('/storage/' . ltrim($this->image, '/'));
    }

    // Get mobile image URL
    public function getMobileImageUrl()
    {
        if (empty($this->mobile_image)) {
            return $this->getImageUrl();
        }

        // Already a full URL (Cloudinary, etc.)
        if (filter_var($this->mobile_image, FILTER_VALIDATE_URL)) {
            return $this->mobile_image;
        }

        // Absolute /storage/ path
        if (str_starts_with($this->mobile_image, '/storage/') || str_starts_with($this->mobile_image, '/')) {
            return url($this->mobile_image);
        }

        // Relative path like 'slides/mobile_file.jpg'
        return url('/storage/' . ltrim($this->mobile_image, '/'));
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
