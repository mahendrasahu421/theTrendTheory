<?php
// app/Models/CouponRule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponRule extends Model
{
    protected $fillable = [
        'coupon_id',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit_total',
        'usage_limit_per_user',
        'valid_from',
        'valid_until'
    ];
    
    protected $casts = [
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime'
    ];
    
    /**
     * Get the coupon that owns this rule
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}