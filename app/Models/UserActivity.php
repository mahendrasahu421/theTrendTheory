<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'event_type',
        'event_title',
        'event_details',
        'url',
        'source',
        'city',
        'state',
        'country',
        'device_type',
        'device_brand',
        'device_model',
        'browser',
        'contacted_at',
        'contacted_channel',
    ];

    protected $casts = [
        'event_details' => 'array',
        'contacted_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEventBadgeClassAttribute(): string
    {
        return match ($this->event_type) {
            'checkout_started' => 'badge-checkout-started',
            'order_placed'     => 'badge-order-placed',
            'cart_added'       => 'badge-cart-added',
            'cart_removed'     => 'badge-cart-removed',
            'wishlist_added'   => 'badge-wishlist-added',
            'product_viewed'   => 'badge-product-viewed',
            'search_performed' => 'badge-search',
            'coupon_applied'   => 'badge-coupon',
            default            => 'badge-generic-event',
        };
    }

    public function getEventIconAttribute(): string
    {
        return match ($this->event_type) {
            'checkout_started' => 'bi-cart-check-fill',
            'order_placed'     => 'bi-bag-check-fill',
            'cart_added'       => 'bi-bag-plus-fill',
            'cart_removed'     => 'bi-bag-x-fill',
            'wishlist_added'   => 'bi-heart-fill',
            'product_viewed'   => 'bi-eye-fill',
            'search_performed' => 'bi-search',
            'coupon_applied'   => 'bi-ticket-perforated-fill',
            default            => 'bi-lightning-fill',
        };
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event_type) {
            'checkout_started' => 'Checkout Started (Cart)',
            'order_placed'     => 'Order Placed',
            'cart_added'       => 'Added to Bag',
            'cart_removed'     => 'Removed from Bag',
            'wishlist_added'   => 'Wishlisted Item',
            'product_viewed'   => 'Product Viewed',
            'search_performed' => 'Search Query',
            'coupon_applied'   => 'Applied Coupon',
            default            => ucwords(str_replace('_', ' ', $this->event_type)),
        };
    }
}
