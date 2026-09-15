<?php
// app/Services/CouponService.php
namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Validate coupon for cart
     */
    public function validateCoupon(string $couponCode, ?User $user, Cart $cart): array
    {
        // Find active coupon with all relationships
        $coupon = Coupon::with(['rules', 'products', 'categories'])
            ->where('code', strtoupper($couponCode))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code'];
        }

        $rules = $coupon->rules;

        // Check expiry
        if ($rules && $rules->valid_until && Carbon::now()->gt($rules->valid_until)) {
            return ['valid' => false, 'message' => 'This coupon has expired'];
        }

        // Check validity start date
        if ($rules && $rules->valid_from && Carbon::now()->lt($rules->valid_from)) {
            return ['valid' => false, 'message' => 'This coupon is not yet active'];
        }

        // Get cart items and totals
        $cartItems = $cart->items;
        $cartTotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Get applicable items based on coupon restrictions
        $applicableItems = $cartItems->filter(function ($item) use ($coupon) {
            return $coupon->appliesToProduct($item->product_id);
        });

        $applicableTotal = $applicableItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Check if coupon has restrictions and no applicable items
        if ($coupon->hasRestrictions() && $applicableItems->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'This coupon is not applicable to any items in your cart'
            ];
        }

        // Check minimum order amount
        $minOrderCheckTotal = $coupon->hasRestrictions() ? $applicableTotal : $cartTotal;

        if ($rules && $rules->min_order_amount > 0 && $minOrderCheckTotal < $rules->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount of ₹" . number_format($rules->min_order_amount) . " required"
            ];
        }

        // Check total usage limit
        if ($rules && $rules->usage_limit_total) {
            $usedCount = CouponUsage::where('coupon_id', $coupon->id)->count();
            if ($usedCount >= $rules->usage_limit_total) {
                return ['valid' => false, 'message' => 'This coupon has reached its usage limit'];
            }
        }

        // Check per user usage limit
        if ($user && $rules && $rules->usage_limit_per_user) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();
            if ($userUsageCount >= $rules->usage_limit_per_user) {
                return ['valid' => false, 'message' => 'You have already used this coupon the maximum number of times'];
            }
        }

        // Calculate discount
        $discount = $this->calculateDiscount($coupon, $cart, $applicableTotal);

        if ($discount <= 0) {
            return ['valid' => false, 'message' => 'Coupon does not provide any discount on your cart'];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'applicable_items' => $applicableItems,
            'applicable_total' => $applicableTotal,
            'message' => 'Coupon is valid!'
        ];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(Coupon $coupon, Cart $cart, ?float $applicableTotal = null): float
    {
        if ($applicableTotal === null) {
            $cartItems = $cart->items;
            $applicableItems = $cartItems->filter(function ($item) use ($coupon) {
                return $coupon->appliesToProduct($item->product_id);
            });
            $applicableTotal = $applicableItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        // If coupon has restrictions and no applicable items, return 0
        if ($coupon->hasRestrictions() && $applicableTotal == 0) {
            return 0;
        }

        // Calculate discount base
        $discountBase = $coupon->hasRestrictions() ? $applicableTotal : $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;

        $type = strtolower((string) $coupon->type);
        if (in_array($type, ['percent', 'percentage'], true)) {
            $discount = ($coupon->value / 100) * $discountBase;

            // Apply max discount cap if exists
            if ($coupon->rules && $coupon->rules->max_discount_amount) {
                $discount = min($discount, $coupon->rules->max_discount_amount);
            }
        } else {
            // Flat discount
            $discount = min($coupon->value, $discountBase);
        }

        return max(0, $discount);
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(string $couponCode, User $user, Cart $cart): array
    {
        $validation = $this->validateCoupon($couponCode, $user, $cart);

        if (!$validation['valid']) {
            return $validation;
        }

        $coupon = $validation['coupon'];

        // Save coupon usage
        $usage = CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'order_id' => null,
            'discount_applied' => $validation['discount'],
            'used_at' => Carbon::now()
        ]);

        // Update cart with coupon info
        $cart->update([
            'coupon_code' => $couponCode,
            'coupon_discount' => $validation['discount'],
            'coupon_applied_at' => Carbon::now(),
            'coupon_usage_id' => $usage->id
        ]);

        return [
            'valid' => true,
            'discount' => $validation['discount'],
            'coupon' => $coupon,
            'usage_id' => $usage->id,
            'message' => 'Coupon applied successfully! You saved ₹' . number_format($validation['discount'])
        ];
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(Cart $cart): array
    {
        $cart->update([
            'coupon_code' => null,
            'coupon_discount' => 0,
            'coupon_applied_at' => null,
            'coupon_usage_id' => null
        ]);

        return ['success' => true, 'message' => 'Coupon removed successfully'];
    }

    /**
     * Get available coupons for user
     */
    public function getAvailableCoupons(User $user, Cart $cart): array
    {
        $coupons = Coupon::with(['rules', 'products', 'categories'])
            ->where('is_active', true)
            ->get();

        $availableCoupons = [];

        foreach ($coupons as $coupon) {
            $validation = $this->validateCoupon($coupon->code, $user, $cart);
            if ($validation['valid']) {
                $availableCoupons[] = [
                    'code' => $coupon->code,
                    'description' => $coupon->description,
                    'discount_value' => $validation['discount'],
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'has_restrictions' => $coupon->hasRestrictions(),
                    'restricted_to' => $this->getRestrictionInfo($coupon),
                    'rules' => $coupon->rules
                ];
            }
        }

        return $availableCoupons;
    }

    /**
     * Get restriction info for display
     */
    private function getRestrictionInfo(Coupon $coupon): string
    {
        $restrictions = [];

        if ($coupon->products()->count() > 0) {
            $productNames = $coupon->products()->limit(3)->pluck('name')->toArray();
            $restrictions[] = 'Products: ' . implode(', ', $productNames) .
                ($coupon->products()->count() > 3 ? ' + ' . ($coupon->products()->count() - 3) . ' more' : '');
        }

        if ($coupon->categories()->count() > 0) {
            $categoryNames = $coupon->categories()->limit(3)->pluck('name')->toArray();
            $restrictions[] = 'Categories: ' . implode(', ', $categoryNames) .
                ($coupon->categories()->count() > 3 ? ' + ' . ($coupon->categories()->count() - 3) . ' more' : '');
        }

        return implode(' • ', $restrictions);
    }

    /**
     * Finalize coupon usage when order is placed
     */
    public function finalizeCouponUsage(Cart $cart, Order $order): void
    {
        if ($cart->coupon_usage_id) {
            CouponUsage::where('id', $cart->coupon_usage_id)
                ->update(['order_id' => $order->id]);
        }
    }
}
