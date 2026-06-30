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
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);
        
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }
        
        $result = $this->couponService->applyCoupon(
            $request->coupon_code,
            $user,
            $cart
        );
        
        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'discount' => $result['discount'],
                'cart_total' => $cart->getTotal(),
                'final_total' => $cart->getFinalTotal(),
                'coupon_code' => $cart->coupon_code
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 422);
    }
    
    /**
     * Remove coupon from cart
     */
    public function remove()
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }
        
        $result = $this->couponService->removeCoupon($cart);
        
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'cart_total' => $cart->getTotal(),
            'final_total' => $cart->getTotal()
        ]);
    }
    
    /**
     * Check coupon validity
     */
    public function check(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);
        
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart) {
            return response()->json([
                'valid' => false,
                'message' => 'Cart not found'
            ], 404);
        }
        
        $result = $this->couponService->validateCoupon(
            $request->coupon_code,
            $user,
            $cart
        );
        
        return response()->json($result);
    }
    
    /**
     * Get available coupons
     */
    public function available()
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart) {
            return response()->json([]);
        }
        
        $coupons = $this->couponService->getAvailableCoupons($user, $cart);
        
        return response()->json($coupons);
    }
}