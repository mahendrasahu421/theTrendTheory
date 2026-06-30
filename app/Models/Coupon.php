<?php
// app/Models/Coupon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2'
    ];

    /**
     * Get the rules for this coupon
     */
    public function rules(): HasOne
    {
        return $this->hasOne(CouponRule::class);
    }

    /**
     * Get usage records for this coupon
     */
    public function usage(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Products this coupon applies to
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_products', 'coupon_id', 'product_id')
            ->withTimestamps();
    }

    /**
     * Categories this coupon applies to
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_categories', 'coupon_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * Check if coupon applies to a specific product
     */
    public function appliesToProduct($productId): bool
    {
        // If no restrictions, applies to all
        if ($this->products()->count() === 0 && $this->categories()->count() === 0) {
            return true;
        }

        // Check direct product association
        if ($this->products()->where('product_id', $productId)->exists()) {
            return true;
        }

        // Check category association
        $product = Product::find($productId);
        if ($product && $this->categories()->count() > 0) {
            $productCategories = $product->categories()->pluck('categories.id')->toArray();
            $couponCategories = $this->categories()->pluck('categories.id')->toArray();

            if (array_intersect($productCategories, $couponCategories)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if coupon has any restrictions
     */
    public function hasRestrictions(): bool
    {
        return $this->products()->count() > 0 || $this->categories()->count() > 0;
    }
}