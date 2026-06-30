<?php
// app/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_code',
        'coupon_discount',
        'coupon_applied_at',
        'coupon_usage_id'
    ];
    
    protected $casts = [
        'coupon_discount' => 'decimal:2',
        'coupon_applied_at' => 'datetime'
    ];
    
    /**
     * Get cart items
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
    
    /**
     * Get the user who owns the cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Calculate cart total
     */
    public function getTotal(): float
    {
        return $this->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }
    
    /**
     * Get final total after discount
     */
    public function getFinalTotal(): float
    {
        return max(0, $this->getTotal() - $this->coupon_discount);
    }
    
    /**
     * Check if coupon is applied
     */
    public function hasCoupon(): bool
    {
        return !is_null($this->coupon_code) && $this->coupon_discount > 0;
    }
    
    /**
     * Get coupon details
     */
    public function getCoupon(): ?Coupon
    {
        if (!$this->hasCoupon()) {
            return null;
        }
        
        return Coupon::where('code', $this->coupon_code)->first();
    }
}