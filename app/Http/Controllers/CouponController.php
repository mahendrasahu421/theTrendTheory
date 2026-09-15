<?php
// app/Http/Controllers/CouponController.php
namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    protected $couponService;
    
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    /**
     * Apply coupon to cart
     */
    public function apply(Request $request)
    {
        $code = strtoupper(trim($request->input('coupon_code') ?: $request->input('code') ?: ''));
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a coupon code.'
            ], 422);
        }

        $user = Auth::user();
        $cart = $user ? $user->cart : null;

        // If DB cart exists and has items
        if ($cart && $cart->items && $cart->items->count() > 0) {
            $result = $this->couponService->applyCoupon($code, $user, $cart);
            if ($result['valid']) {
                session([
                    'coupon_code' => $code,
                    'coupon_discount' => $result['discount'],
                ]);
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'discount' => $result['discount'],
                    'cart_total' => $cart->getTotal(),
                    'final_total' => $cart->getFinalTotal(),
                    'coupon_code' => $code
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 422);
        }

        // Handle Session Cart
        $sessionCart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($sessionCart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        if ($subtotal <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your shopping bag is empty.'
            ], 404);
        }

        $coupon = \App\Models\Coupon::with(['rules'])->where('code', $code)->where('is_active', true)->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.'
            ], 422);
        }

        $rules = $coupon->rules;
        if ($rules && $rules->valid_until && \Carbon\Carbon::now()->gt($rules->valid_until)) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.'], 422);
        }
        if ($rules && $rules->valid_from && \Carbon\Carbon::now()->lt($rules->valid_from)) {
            return response()->json(['success' => false, 'message' => 'This coupon is not yet active.'], 422);
        }
        if ($rules && $rules->min_order_amount > 0 && $subtotal < $rules->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ₹' . number_format($rules->min_order_amount) . ' required.'
            ], 422);
        }

        $type = strtolower((string) $coupon->type);
        $isPercentage = in_array($type, ['percent', 'percentage'], true);

        $discount = 0;
        if ($isPercentage) {
            $discount = ($subtotal * (float)$coupon->value) / 100;
            if ($rules && $rules->max_discount_amount > 0) {
                $discount = min($discount, (float)$rules->max_discount_amount);
            }
        } else {
            $discount = min($subtotal, (float)$coupon->value);
        }

        session([
            'coupon_code' => $code,
            'coupon_discount' => $discount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon ' . $code . ' applied! You saved ₹' . number_format(round($discount)),
            'discount' => $discount,
            'coupon_code' => $code,
            'cart_total' => $subtotal,
            'final_total' => max(0, $subtotal - $discount)
        ]);
    }

    /**
     * Remove coupon from cart
     */
    public function remove()
    {
        session()->forget(['coupon_code', 'coupon_discount']);
        
        $user = Auth::user();
        $cart = $user ? $user->cart : null;

        if ($cart) {
            $this->couponService->removeCoupon($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully'
        ]);
    }

    /**
     * Get available coupons
     */
    public function available()
    {
        $sessionCart = session()->get('cart', []);
        $subtotal = 0;
        $cartCount = 0;
        foreach ($sessionCart as $item) {
            $quantity = (int) ($item['quantity'] ?? 1);
            $subtotal += ((float) ($item['price'] ?? 0) * $quantity);
            $cartCount += $quantity;
        }
        if ($subtotal <= 0 && auth()->check() && auth()->user()->cart) {
            $cart = auth()->user()->cart;
            $subtotal = (float) $cart->getTotal();
            $cartCount = $cart->items ? (int) $cart->items->sum('quantity') : 0;
        }

        $coupons = \App\Models\Coupon::with(['rules'])
            ->where('is_active', true)
            ->get();

        $list = [];
        foreach ($coupons as $coupon) {
            $rules = $coupon->rules;
            $minSpend = $rules ? (float) $rules->min_order_amount : 0;
            $isEligible = ($subtotal >= $minSpend);
            $type = strtolower((string) $coupon->type);
            $isPercentage = in_array($type, ['percent', 'percentage'], true);
            
            $saving = 0;
            if ($subtotal > 0) {
                if ($isPercentage) {
                    $saving = ($subtotal * (float)$coupon->value) / 100;
                    if ($rules && $rules->max_discount_amount > 0) {
                        $saving = min($saving, (float)$rules->max_discount_amount);
                    }
                } else {
                    $saving = min($subtotal, (float) $coupon->value);
                }
            } else {
                $saving = $isPercentage ? 0 : (float) $coupon->value;
            }

            $diff = max(0, $minSpend - $subtotal);
            $couponCategory = strtoupper($coupon->code) === 'PREPAID5' ? 'payment' : 'brand';

            $list[] = [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'description' => $coupon->description ?: ($isPercentage ? (int)$coupon->value.'% OFF on all orders' : 'Flat ₹'.(int)$coupon->value.' OFF'),
                'type' => $isPercentage ? 'percent' : 'flat',
                'value' => (float) $coupon->value,
                'min_spend' => $minSpend,
                'saving' => round($saving),
                'is_eligible' => $isEligible,
                'shortage' => round($diff),
                'category' => $couponCategory,
                'unlock_text' => $minSpend > 0
                    ? 'Add items worth ₹' . number_format(round($minSpend)) . ' to unlock ' . ($isPercentage ? (int) $coupon->value . '% off' : '₹' . number_format(round($coupon->value)) . ' off') . ' with code ' . $coupon->code
                    : null,
            ];
        }

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'cart_count' => $cartCount,
            'applied_code' => session('coupon_code', ''),
            'coupons' => $list
        ]);
    }
}
