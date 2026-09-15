<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'parent_product_id',
        'product_type',
        'color_name',
        'color_hex',
        'short_description',
        'description',
        'price',
        'original_price',
        'cost_price',
        'image', // ✅ Make sure image is in fillable
        'front_image',
        'back_image',
        'available_print_sides',
        'stock',
        'has_variants',
        'low_stock_alert',
        'total_sold',
        'fabric',
        'fit',
        'care_instructions',
        'weight',
        'weight_grams',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_active',
        'is_featured',
        'is_new',
        'is_trending',
        'is_on_sale'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'weight_grams' => 'integer',
        'is_active' => 'boolean',
        'has_variants' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_trending' => 'boolean',
        'is_on_sale' => 'boolean',
        'stock' => 'integer',
        'total_sold' => 'integer',
    ];

    // ─── Relationships ─────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model')->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->morphOne(Media::class, 'model')->where('is_primary', true);
    }

    public function galleryImages()
    {
        return $this->morphMany(Media::class, 'model')->where('is_primary', false)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_variants')
            ->withPivot('id', 'stock', 'price', 'sku')
            ->distinct();
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_variants')
            ->withPivot('id', 'stock', 'price', 'sku')
            ->distinct();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class);
    }

    // ─── Accessors ─────────────────────────────────────

    public function getMainImageAttribute(): string
    {
        if (!empty($this->image) && $this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            if (!str_starts_with($this->image, '/') && !str_starts_with($this->image, 'http')) {
                return url('/storage/' . $this->image);
            }
            return $this->image;
        }

        $primaryImage = $this->productImages()->where('is_primary', true)->first();
        if ($primaryImage && $primaryImage->url) {
            return $primaryImage->getImageUrl(400, 500);
        }

        $primary = $this->media->where('is_primary', true)->first();
        if ($primary) {
            return $primary->getImageUrl(400, 500);
        }

        $first = $this->media->first();
        if ($first) {
            return $first->getImageUrl(400, 500);
        }

        return asset('images/placeholder-product.jpg');
    }

    public function getCardImageAttribute(): string
    {
        if (!empty($this->image) && $this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            if (!str_starts_with($this->image, '/') && !str_starts_with($this->image, 'http')) {
                return url('/storage/' . $this->image);
            }
            return $this->image;
        }

        $primaryImage = $this->productImages()->where('is_primary', true)->first();
        if ($primaryImage && $primaryImage->url) {
            return $primaryImage->getImageUrl(300, 380);
        }

        $primary = $this->media->where('is_primary', true)->first();
        if ($primary) {
            return $primary->getImageUrl(300, 380);
        }

        $first = $this->media->first();
        if ($first) {
            return $first->getImageUrl(300, 380);
        }

        return asset('images/placeholder-product.jpg');
    }

    // ✅ FIX: Add this method for image_url
    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image) && $this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            if (!str_starts_with($this->image, '/') && !str_starts_with($this->image, 'http')) {
                return url('/storage/' . $this->image);
            }
            return $this->image;
        }

        // First try: card_image
        $cardImage = $this->card_image;
        if ($cardImage && !str_contains($cardImage, 'placeholder')) {
            return $cardImage;
        }
        
        // Second try: main_image
        $mainImage = $this->main_image;
        if ($mainImage && !str_contains($mainImage, 'placeholder')) {
            return $mainImage;
        }
        
        // Fourth try: media relationship
        if ($this->relationLoaded('media') && $this->media && $this->media->count() > 0) {
            $primary = $this->media->where('is_primary', true)->first();
            if ($primary) {
                return $primary->getUrl();
            }
            return $this->media->first()->getUrl();
        }
        
        return asset('images/placeholder-product.jpg');
    }

    public function getAllImagesListAttribute(): array
    {
        $list = [];

        // 1. Base image from product table
        if (!empty($this->image)) {
            $url = filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : (str_starts_with($this->image, '/') ? $this->image : url('/storage/' . $this->image));
            $list[] = $url;
        }

        // 2. ProductImage records
        $pImages = $this->relationLoaded('productImages') ? $this->productImages : $this->productImages()->orderByDesc('is_primary')->orderBy('sort_order')->get();
        foreach ($pImages as $pi) {
            if (!empty($pi->url) && !in_array($pi->url, $list)) {
                $list[] = $pi->url;
            }
        }

        // 3. Media records
        $mediaItems = $this->relationLoaded('media') ? $this->media : $this->media()->orderByDesc('is_primary')->orderBy('sort_order')->get();
        foreach ($mediaItems as $m) {
            $mUrl = $m->getUrl();
            if (!empty($mUrl) && !in_array($mUrl, $list)) {
                $list[] = $mUrl;
            }
        }

        // 4. Fallback if empty
        if (empty($list)) {
            $list[] = asset('images/placeholder-product.jpg');
        }

        return array_values(array_unique($list));
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        return $this->has_discount ? (int) round((($this->original_price - $this->price) / $this->original_price) * 100) : 0;
    }

    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0)
            return 'out_of_stock';
        if ($this->stock <= $this->low_stock_alert)
            return 'low_stock';
        return 'in_stock';
    }

    public function getFormattedSoldAttribute(): string
    {
        $s = $this->total_sold ?? 0;
        return $s >= 1000 ? round($s / 1000, 1) . 'k' : (string) $s;
    }

    // ─── Scopes ────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeNew($q)
    {
        return $q->where('is_new', true);
    }

    public function scopeTrending($q)
    {
        return $q->where('is_trending', true);
    }

    public function scopeInStock($q)
    {
        return $q->where('stock', '>', 0);
    }

    public function scopeOnSale($q)
    {
        return $q->where('is_on_sale', true)->whereNotNull('original_price')->whereRaw('original_price > price');
    }

    // ─── Static Methods ─────────────────────────────────
    public static function mostPurchased(int $limit = 10)
    {
        return static::active()->orderByDesc('total_sold')->limit($limit)->get();
    }

    public static function newArrivals(int $limit = 8)
    {
        return static::active()->where('is_new', true)->latest()->limit($limit)->get();
    }

    public static function byCategory(string $slug, int $limit = 12)
    {
        return static::active()->whereHas('category', fn($q) => $q->where('slug', $slug))->latest()->limit($limit)->get();
    }

    // ─── Boot ──────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }

    public function images()
    {
        return $this->media();
    }

    public function getImagesAttribute()
    {
        return $this->media;
    }

    public static function generateColorSlug($productName, $colorName)
    {
        $slug = Str::slug($productName . '-' . $colorName);
        $originalSlug = $slug;
        $counter = 1;
        
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    public function getColorVariants()
    {
        if ($this->parent_product_id) {
            return Product::where('parent_product_id', $this->parent_product_id)
                ->orWhere('id', $this->parent_product_id)
                ->where('is_active', true)
                ->get();
        } elseif ($this->product_type === 'color_variant_parent') {
            return $this->childVariants()->where('is_active', true)->get();
        }
        return collect();
    }
    
    public function childVariants()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }
    
    public function getPrimaryImageUrl($width = null, $height = null)
    {
        if ($this->main_image) {
            if ($width && $height) {
                return $this->main_image . "?tr=w-{$width},h-{$height},fo-auto,q-80";
            }
            return $this->main_image;
        }

        $primaryMedia = $this->media()->where('is_primary', true)->first();
        if ($primaryMedia) {
            return $primaryMedia->getImageUrl($width, $height);
        }

        return asset('images/placeholder-product.jpg');
    }
}
