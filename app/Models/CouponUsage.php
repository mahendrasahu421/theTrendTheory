<?php
// app/Models/CouponUsage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUsage extends Model
{
    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_applied',
        'used_at'
    ];

    protected $casts = [
        'discount_applied' => 'decimal:2',
        'used_at' => 'datetime'
    ];

    /**
     * Get the coupon that was used
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the user who used the coupon
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order this coupon was used on
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}