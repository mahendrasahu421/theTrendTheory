<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    // ── GET /cart ──────────────────────────────────────────

    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal >= 999 ? 0 : 50;
        $couponDiscount = session()->get('coupon_discount', 0);
        $total = $subtotal + $shipping - $couponDiscount;

        return view('froentend.cart.index', compact('cart', 'subtotal', 'shipping', 'total', 'couponDiscount'));
    }

    public function calculateCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $user = Auth::user();
        $cart = $this->getCartObject();

        $result = $this->couponService->validateCoupon($request->coupon_code, $user, $cart);

        return response()->json($result);
    }

    /**
     * Get cart as object
     */
    private function getCartObject()
    {
        $cartItems = session()->get('cart', []);
        $cart = new \stdClass();
        $cart->items = collect($cartItems)->map(function ($item, $key) {
            $cartItem = new \stdClass();
            $cartItem->product_id = $item['product_id'] ?? $key;
            $cartItem->price = $item['price'];
            $cartItem->quantity = $item['quantity'];
            return $cartItem;
        });

        return $cart;
    }
    // ── POST /cart/add/{id} ────────────────────────────────
    public function add(Request $request, $id = null)
    {
        // Login check
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'redirect' => url('/login'),
            ]);
        }

        $productId = $id ?? $request->input('product_id');
        $product = Product::where('is_active', true)->find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ]);
        }

        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.',
            ]);
        }

        $qty = max(1, (int) $request->input('quantity', 1));
        $size = $request->input('size', '');
        $cart = session()->get('cart', []);

        // Same product + same size = same cart item
        $key = $product->id . ($size ? '_' . $size : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'image' => $product->image_url ?? asset('images/placeholder-product.jpg'),
                'size' => $size,
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => $product->name . ' added to cart!',
            'cart_count' => $cartCount,
        ]);
    }

    // ── PATCH /cart/update/{key} ───────────────────────────
    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, (int) $request->input('quantity', 1));

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => collect($cart)->sum('quantity'),
        ]);
    }

    // ── DELETE /cart/remove/{key} ──────────────────────────
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'cart_count' => collect($cart)->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    // ── GET /checkout ──────────────────────────────────────
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $total = $subtotal + $shipping;

        return view('froentend.cart.checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    // ── POST /checkout/place ───────────────────────────────
    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'payment' => 'required|in:cod,upi,card',
        ]);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $total = $subtotal + $shipping;

        // Create order
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'shipping_charge' => $shipping,
            'total_amount' => $total,
            'payment_method' => $request->payment,
            'payment_status' => $request->payment === 'cod' ? 'pending' : 'pending',
            'status' => 'confirmed',
            'shipping_name' => $request->name,
            'shipping_phone' => $request->phone,
            'shipping_address' => $request->address,
            'shipping_city' => $request->city,
            'shipping_state' => $request->state,
            'shipping_pincode' => $request->pincode,
        ]);

        // Order items save
        foreach ($cart as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_image' => $item['image'],
                'size' => $item['size'] ?? '',
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            // Stock update
            \App\Models\Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            \App\Models\Product::where('id', $item['id'])->increment('total_sold', $item['quantity']);
        }

        // Cart clear
        session()->forget('cart');

        return redirect()->route('order.success', $order->order_number);
    }
}
