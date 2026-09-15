<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'image',
        'banner_image',
        'sort_order',
        'is_active',
        'show_in_nav',
        'show_in_home'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
        'show_in_home' => 'boolean'
    ];

    // ==================== RELATIONSHIPS ====================
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model')->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->morphOne(Media::class, 'model')->where('is_primary', true);
    }

    // ==================== ACCESSORS ====================
    public function getMainImageAttribute(): string
    {
        if (!empty($this->image)) {
            return $this->resolveImageUrl($this->image);
        }

        $primary = $this->relationLoaded('media')
            ? $this->media->where('is_primary', true)->first()
            : $this->primaryImage()->first();

        if ($primary) {
            return $primary->getImageUrl(600, 400);
        }

        return asset('images/placeholder-category.jpg');
    }

    // ✅ FIX: For array access when cached
    // app/Models/Product.php

    // Add this method after your existing accessors
    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            return $this->resolveImageUrl($this->image);
        }

        if ($this->relationLoaded('media') && $this->media->count() > 0) {
            $primary = $this->media->where('is_primary', true)->first();
            if ($primary) {
                return $primary->getImageUrl(400, 500);
            }
            return $this->media->first()->getImageUrl(400, 500);
        }

        return asset('images/placeholder-category.jpg');
    }
    // ✅ FIX: Banner image URL for array access
    public function getBannerImageUrlAttribute(): string
    {
        if (!empty($this->banner_image)) {
            return $this->resolveImageUrl($this->banner_image);
        }
        return $this->image_url;
    }

    private function resolveImageUrl(string $path): string
    {
        // Already a full URL (Cloudinary https://res.cloudinary.com/..., or /storage/...)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Absolute path starting with /storage/ or / — prefix with app URL
        if (str_starts_with($path, '/storage/') || str_starts_with($path, '/')) {
            return url($path);
        }

        // Relative storage path: 'categories/file.jpg' → /storage/categories/file.jpg
        if (str_starts_with($path, 'categories/') || str_starts_with($path, 'storage/')) {
            return url('/storage/' . ltrim($path, '/'));
        }

        // Legacy path — serve from local storage
        return url('/storage/' . $path);
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?? $this->name . ' Online India | Vayu';
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?? 'Shop ' . $this->name . ' online. Free shipping above Rs.999.';
    }

    // ==================== SCOPES ====================
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeForNav($q)
    {
        return $q->where('show_in_nav', true)->whereNull('parent_id');
    }

    public function scopeForHome($q)
    {
        return $q->where('show_in_home', true)->whereNull('parent_id');
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order', 'asc');
    }

    // ==================== STATIC METHODS ====================
    public static function navCategories()
    {
        return static::active()
            ->whereNull('parent_id')
            ->where('show_in_nav', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();
    }

    public static function homeCategories()
    {
        return static::active()
            ->whereNull('parent_id')
            ->where('show_in_home', true)
            ->orderBy('sort_order')
            ->get();
    }

    // ✅ NEW: For caching as array (used in HomeController)
    public static function homeCategoriesArray()
    {
        return static::homeCategories()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'image_url' => $category->image,
                'banner_image' => $category->banner_image_url,
                'sort_order' => $category->sort_order,
                'is_active' => $category->is_active,
                'show_in_home' => $category->show_in_home,
                'meta_title' => $category->meta_title,
                'meta_description' => $category->meta_description,
            ];
        })->toArray();
    }

    // ✅ NEW: For navbar categories as array
    public static function navCategoriesArray()
    {
        return static::navCategories()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image' => $category->image_url,
                'children' => $category->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'slug' => $child->slug,
                        'image' => $child->image_url,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }

    // ✅ NEW: Helper to convert single category to array
    public function toArrayForCache()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image_url,
            'image_url' => $this->image_url,
            'banner_image' => $this->banner_image_url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'show_in_home' => $this->show_in_home,
            'show_in_nav' => $this->show_in_nav,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'parent_id' => $this->parent_id,
            'children' => $this->children->map->toArrayForCache()->toArray(),
        ];
    }


}
