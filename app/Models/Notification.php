<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'action_url',
        'action_label',
        'image_url',
        'icon',
        'is_read',
        'read_at',
        'channels',
        'target_audience',
        'created_by',
    ];

    protected $casts = [
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
        'channels' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'product_launched'  => 'badge-notif-product',
            'offer_created'     => 'badge-notif-offer',
            'cart_abandoned'    => 'badge-notif-cart',
            'inactive_welcome'  => 'badge-notif-welcome',
            'order_status'      => 'badge-notif-order',
            'price_drop'        => 'badge-notif-price',
            default             => 'badge-notif-manual',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'product_launched'  => 'New Product Drop',
            'offer_created'     => 'Special Offer / Coupon',
            'cart_abandoned'    => 'Cart Abandonment Alert',
            'inactive_welcome'  => 'Explore Products / Welcome',
            'order_status'      => 'Order Status',
            'price_drop'        => 'Price Drop',
            default             => 'Manual Broadcast',
        };
    }
}
