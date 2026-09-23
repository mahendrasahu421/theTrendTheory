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
        if (!empty($cart)) {
            $cart = $this->normalizeCartPricing($cart);
            session()->put('cart', $cart);
        }
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal >= 999 ? 0 : 50;
        $couponDiscount = session()->get('coupon_discount', 0);
        $total = $subtotal + $shipping - $couponDiscount;

        // 1. Recently Viewed Products from session
        $recentlyViewedIds = collect(session()->get('recently_viewed_products', []))
            ->filter()
            ->unique()
            ->values();

        $cartProductIds = collect($cart)
            ->map(fn($item, $key) => (int)($item['id'] ?? $item['product_id'] ?? $key))
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();

        $recentlyViewedProducts = collect();
        if ($recentlyViewedIds->isNotEmpty()) {
            $recentlyViewedProducts = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
                ->whereIn('id', $recentlyViewedIds)
                ->where('is_active', true)
                ->get()
                ->sortBy(fn($item) => $recentlyViewedIds->search($item->id))
                ->values();
        }

        // Fill recently viewed with active catalog items if user has browsed few/no products
        if ($recentlyViewedProducts->count() < 10) {
            $excludeRecentIds = $cartProductIds->merge($recentlyViewedProducts->pluck('id'))->unique();
            $recentFallback = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
                ->where('is_active', true)
                ->whereNotIn('id', $excludeRecentIds)
                ->inRandomOrder()
                ->limit(10 - $recentlyViewedProducts->count())
                ->get();
            $recentlyViewedProducts = $recentlyViewedProducts->merge($recentFallback)->values();
        }

        // 2. Related Products / Recommendations based on cart or top catalog items
        $categoryIds = collect();
        if ($cartProductIds->isNotEmpty()) {
            $categoryIds = Product::whereIn('id', $cartProductIds)
                ->pluck('category_id')
                ->filter()
                ->unique();
        }

        $excludeRelatedIds = $cartProductIds->merge($recentlyViewedProducts->pluck('id'))->unique();

        $relatedProducts = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
            ->where('is_active', true)
            ->whereNotIn('id', $excludeRelatedIds)
            ->when($categoryIds->isNotEmpty(), fn($q) => $q->whereIn('category_id', $categoryIds))
            ->inRandomOrder()
            ->limit(10)
            ->get();

        if ($relatedProducts->count() < 10) {
            $fallbackExclude = $excludeRelatedIds->merge($relatedProducts->pluck('id'))->unique();
            $fallback = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
                ->where('is_active', true)
                ->whereNotIn('id', $fallbackExclude)
                ->inRandomOrder()
                ->limit(10 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->merge($fallback)->values();
        }

        return view('froentend.cart.index', compact(
            'cart',
            'subtotal',
            'shipping',
            'total',
            'couponDiscount',
            'recentlyViewedProducts',
            'relatedProducts'
        ));
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

        $designSide = $this->resolveDesignSide($request, $product);
        if (!$designSide) {
            return response()->json([
                'success' => false,
                'message' => 'Please select Front Side or Back Side print.',
            ], 422);
        }

        $qty = $this->clampQuantity($request->input('quantity', 1));
        $size = $request->input('size', '');
        $color = $request->input('color', '');
        $cart = session()->get('cart', []);

        $setQuantity = $request->boolean('set_quantity') || $request->boolean('buy_now') || $request->input('mode') === 'buy_now';

        // Same product + same size + same color + same design_side = same cart item
        $key = implode('_', array_filter([$product->id, $size, $color, $designSide], fn ($value) => $value !== null && $value !== ''));

        $chosenImage = $product->image_url ?? asset('images/placeholder-product.jpg');
        if ($designSide === 'back' && !empty($product->back_image)) {
            $chosenImage = $product->back_image;
        } elseif (($designSide === 'front' || $designSide === 'both') && !empty($product->front_image)) {
            $chosenImage = $product->front_image;
        }

        $pricing = $this->cartPricingForSelection($product, $size, $color);
        $cartPrice = $pricing['price'];
        $cartOriginalPrice = $pricing['original_price'];

        if (isset($cart[$key])) {
            if ($setQuantity) {
                $cart[$key]['quantity'] = $qty;
            } else {
                $cart[$key]['quantity'] = $this->clampQuantity(((int) $cart[$key]['quantity']) + $qty);
            }
            $cart[$key]['price'] = $cartPrice;
            $cart[$key]['original_price'] = $cartOriginalPrice;
            $cart[$key]['image'] = $chosenImage;
            $cart[$key]['size'] = $size;
            $cart[$key]['color'] = $color;
            $cart[$key]['design_side'] = $designSide;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $cartPrice,
                'original_price' => $cartOriginalPrice,
                'image' => $chosenImage,
                'size' => $size,
                'color' => $color,
                'design_side' => $designSide,
                'quantity' => $qty,
            ];
        }

        if (!$request->boolean('buy_now')) {
            session()->put('cart', $cart);
        }

        $cartCount = collect(session()->get('cart', []))->sum('quantity');

        // Log user activity
        \App\Helpers\ActivityLogger::log('cart_added', "Added to Bag: {$product->name}" . ($size ? " (Size: {$size})" : ''), [
            'product_id'    => $product->id,
            'product_name'  => $product->name,
            'price'         => $cartPrice,
            'size'          => $size,
            'color'         => $color,
            'quantity'      => $qty,
            'cart_count'    => $cartCount,
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->boolean('buy_now') ? 'Proceeding to checkout...' : ($product->name . ' added to cart!'),
            'cart_count' => $cartCount,
            'cart' => $this->cartSummary($request->boolean('buy_now') ? [$key => ($cart[$key] ?? [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $cartPrice,
                'original_price' => $cartOriginalPrice,
                'image' => $chosenImage,
                'size' => $size,
                'color' => $color,
                'design_side' => $designSide,
                'quantity' => $qty,
            ])] : $cart),
        ]);
    }

    private function resolveDesignSide(Request $request, Product $product): ?string
    {
        $mode = $product->available_print_sides ?: 'both';

        if ($mode === 'front_only') {
            return 'front';
        }

        if ($mode === 'back_only') {
            return 'back';
        }

        $side = $request->input('design_side');

        if (in_array($side, ['front', 'back'], true)) {
            return $side;
        }

        return null;
    }

    private function shippingAddressWithLandmark(?string $address, ?string $landmark): string
    {
        $address = trim((string) $address);
        $landmark = trim((string) $landmark);

        if ($landmark === '') {
            return $address;
        }

        return $address . "\nLandmark: " . $landmark;
    }

    private function cartPricingForSelection(Product $product, ?string $size = '', ?string $color = ''): array
    {
        $size = trim((string) $size);
        $color = trim((string) $color);
        $variant = null;

        if ($product->has_variants || $product->variants()->exists()) {
            $query = $product->variants()->where('is_active', true);

            if ($size !== '') {
                $query->where('size', $size);
            }

            if ($color !== '') {
                $query->where('color', $color);
            }

            $variant = $query->orderBy('price')->first();

            if (!$variant && $size !== '') {
                $variant = $product->variants()
                    ->where('is_active', true)
                    ->where('size', $size)
                    ->orderBy('price')
                    ->first();
            }

            if (!$variant && $color !== '') {
                $variant = $product->variants()
                    ->where('is_active', true)
                    ->where('color', $color)
                    ->orderBy('price')
                    ->first();
            }

            if (!$variant) {
                $variant = $product->variants()
                    ->where('is_active', true)
                    ->orderBy('price')
                    ->first();
            }
        }

        $price = (float) ($variant?->price ?? $product->price ?? 0);
        $originalPrice = (float) ($variant?->original_price ?? 0);

        if ($originalPrice <= $price) {
            $productMrp = (float) ($product->original_price ?? 0);
            $originalPrice = $productMrp > $price ? $productMrp : $price;
        }

        return [
            'price' => $price,
            'original_price' => $originalPrice,
        ];
    }

    private function clampQuantity($quantity): int
    {
        return max(1, min(10, (int) $quantity));
    }

    private function cartSummary(array $cart): array
    {
        $cart = $this->normalizeCartPricing($cart);
        $subtotal = collect($cart)->sum(fn ($item) => ((float) $item['price']) * ((int) $item['quantity']));
        $shipping = $subtotal >= 999 ? 0 : 50;
        $discount = 0;
        $total = max(0, $subtotal + $shipping - $discount);

        return [
            'items' => collect($cart)->map(function ($item, $key) {
                $productId = $item['id'] ?? $item['product_id'] ?? $key;
                $item['id'] = $item['id'] ?? $productId;
                $item['key'] = $key;
                return $item;
            })->values()->all(),
            'count' => collect($cart)->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'savings' => $discount,
        ];
    }

    private function normalizeCartPricing(array $cart): array
    {
        $productIds = collect($cart)
            ->map(fn ($item, $key) => (int) ($item['id'] ?? $item['product_id'] ?? $key))
            ->filter(fn ($id) => $id > 0)
            ->unique();

        if ($productIds->isEmpty()) {
            return $cart;
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cart as $key => $item) {
            $productId = (int) ($item['id'] ?? $item['product_id'] ?? $key);
            $product = $products->get($productId);

            if (!$product) {
                $price = (float) ($item['price'] ?? 0);
                $cart[$key]['price'] = $price;
                $cart[$key]['original_price'] = (float) ($item['original_price'] ?? $price);
                continue;
            }

            $pricing = $this->cartPricingForSelection($product, $item['size'] ?? '', $item['color'] ?? '');
            $cart[$key]['id'] = $cart[$key]['id'] ?? $productId;
            $cart[$key]['price'] = $pricing['price'];
            $cart[$key]['original_price'] = $pricing['original_price'];
        }

        return $cart;
    }

    // ── PATCH /cart/update/{key} ───────────────────────────
    public function update(Request $request, $key = null)
    {
        $cart = session()->get('cart', []);
        $qty = $this->clampQuantity($request->input('quantity', 1));
        $rawKey = $key !== null ? (string)$key : (string)$request->input('key');
        $key = urldecode($rawKey);

        if ($key && isset($cart[$key])) {
            $cart[$key]['quantity'] = $qty;
            session()->put('cart', $cart);
        } elseif ($rawKey && isset($cart[$rawKey])) {
            $cart[$rawKey]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return (float)$item['price'] * (int)$item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $couponDiscount = (float) session()->get('coupon_discount', 0);
        $total = max(0, $subtotal + $shipping - $couponDiscount);

        return response()->json([
            'success' => true,
            'cart_count' => collect($cart)->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $couponDiscount,
            'total' => $total,
            'cart' => $this->cartSummary($cart),
        ]);
    }

    // ── DELETE /cart/remove/{key} ──────────────────────────
    public function remove(Request $request, $key = null)
    {
        $cart = session()->get('cart', []);
        $rawKey = $key !== null ? (string)$key : (string)$request->input('key');
        $key = urldecode($rawKey);

        if ($key && isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        } elseif ($rawKey && isset($cart[$rawKey])) {
            unset($cart[$rawKey]);
            session()->put('cart', $cart);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return (float)$item['price'] * (int)$item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $couponDiscount = (float) session()->get('coupon_discount', 0);
        $total = max(0, $subtotal + $shipping - $couponDiscount);

        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()->back()->with('success', 'Item removed from cart successfully.');
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.',
            'cart_count' => collect($cart)->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $couponDiscount,
            'total' => $total,
            'cart' => $this->cartSummary($cart),
        ]);
    }

    // ── GET /checkout ──────────────────────────────────────
    public function checkout(Request $request)
    {
        $isBuyNow = $request->boolean('buy_now') && $request->filled('product_id');
        $buyNowItem = null;

        if ($isBuyNow) {
            $product = \App\Models\Product::where('is_active', true)->find($request->product_id);
            if (!$product || $product->stock <= 0) {
                return redirect()->back()->with('error', 'Selected product is out of stock or unavailable.');
            }

            $size = $request->input('size', '');
            $color = $request->input('color', '');
            $qty = $this->clampQuantity($request->input('qty', 1));
            $designSide = $this->resolveDesignSide($request, $product);

            if (!$designSide) {
                return redirect()->back()->with('error', 'Please select Front Side or Back Side print.');
            }

            $chosenImage = $product->image_url ?? asset('images/placeholder-product.jpg');
            if ($designSide === 'back' && !empty($product->back_image)) {
                $chosenImage = $product->back_image;
            } elseif (($designSide === 'front' || $designSide === 'both') && !empty($product->front_image)) {
                $chosenImage = $product->front_image;
            }

            $pricing = $this->cartPricingForSelection($product, $size, $color);
            $cartPrice = $pricing['price'];
            $cartOriginalPrice = $pricing['original_price'];

            $key = implode('_', array_filter([$product->id, $size, $color, $designSide], fn ($value) => $value !== null && $value !== ''));

            $buyNowItem = [
                'id'             => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'price'          => $cartPrice,
                'original_price' => $cartOriginalPrice,
                'image'          => $chosenImage,
                'size'           => $size,
                'color'          => $color,
                'design_side'    => $designSide,
                'quantity'       => $qty,
            ];

            // Temporary cart for checkout display only — regular session('cart') is untouched
            $cart = [$key => $buyNowItem];
        } else {
            $cart = session()->get('cart', []);
            if (!empty($cart)) {
                $cart = $this->normalizeCartPricing($cart);
                session()->put('cart', $cart);
            }

            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
        }

        $user = auth()->user();
        $addresses = \App\Models\Address::where('user_id', auth()->id())->get();
        $subtotal = collect($cart)->sum(function ($item) {
            return (float) $item['price'] * (int) $item['quantity'];
        });
        $shipping = $subtotal >= 999 ? 0 : 50;
        $couponDiscount = (float) session()->get('coupon_discount', 0);
        $total = max(1, $subtotal + $shipping - $couponDiscount);
        $itemCount = collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 1));

        // Log user activity
        \App\Helpers\ActivityLogger::log('checkout_started', "Initiated Checkout (" . $itemCount . " items, ₹" . number_format($total) . ")" . ($isBuyNow ? " [BUY NOW]" : ""), [
            'item_count' => $itemCount,
            'subtotal'   => $subtotal,
            'total'      => $total,
            'items'      => array_values($cart),
            'is_buy_now' => $isBuyNow,
        ]);

        return view('froentend.cart.checkout', compact('cart', 'user', 'addresses', 'subtotal', 'shipping', 'couponDiscount', 'total', 'isBuyNow', 'buyNowItem'));
    }

    // ── POST /checkout/place ───────────────────────────────
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'landmark'   => 'nullable|string|max:120',
            'city'       => 'required|string|max:100',
            'state'      => 'required|string|max:100',
            'pincode'    => 'required|string|max:10',
            'payment'    => 'nullable|string',
            'product_id' => 'nullable|integer',
            'qty'        => 'nullable|integer|min:1',
            'size'       => 'nullable|string',
            'color'      => 'nullable|string',
            'design_side'=> 'nullable|string',
            'buy_now'    => 'nullable|boolean',
        ]);

        $cart = session()->get('cart', []);
        $isBuyNow = $request->boolean('buy_now') && $request->filled('product_id');

        if ($isBuyNow) {
            $product = \App\Models\Product::find($request->product_id);
            if (!$product || $product->stock <= 0) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Product is out of stock.'], 400);
                }
                return back()->with('error', 'Selected product is out of stock.');
            }
            $qty = $this->clampQuantity($request->input('qty', 1));
            $size = $request->input('size', '');
            $color = $request->input('color', '');
            $pricing = $this->cartPricingForSelection($product, $size, $color);
            $cartPrice = $pricing['price'];
            $cartOriginalPrice = $pricing['original_price'];

            $subtotal = (float) $cartPrice * $qty;
            $shipping = $subtotal >= 999 ? 0 : 50;
            $couponDiscount = (float) session()->get('coupon_discount', 0);
            $total = max(1, $subtotal + $shipping - $couponDiscount);
            $designSide = $this->resolveDesignSide($request, $product);
            if (!$designSide) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Please select Front Side or Back Side print.'], 422);
                }

                return back()->withInput()->with('error', 'Please select Front Side or Back Side print.');
            }
            $chosenImage = $product->image_url ?? ($product->main_image ?? '');
            if ($designSide === 'back' && !empty($product->back_image)) {
                $chosenImage = $product->back_image;
            } elseif (($designSide === 'front' || $designSide === 'both') && !empty($product->front_image)) {
                $chosenImage = $product->front_image;
            }
            $itemsList = [[
                'id'             => $product->id,
                'name'           => $product->name,
                'price'          => (float) $cartPrice,
                'original_price' => (float) $cartOriginalPrice,
                'quantity'       => $qty,
                'size'           => $size,
                'color'          => $color,
                'design_side'    => $designSide,
                'image'          => $chosenImage,
            ]];
        } else {
            if (empty($cart)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
                }
                return redirect()->route('cart.index');
            }
            $subtotal = collect($cart)->sum(fn($i) => (float)$i['price'] * (int)$i['quantity']);
            $shipping = $subtotal >= 999 ? 0 : 50;
            $couponDiscount = (float) session()->get('coupon_discount', 0);
            $total = max(1, $subtotal + $shipping - $couponDiscount);
            $itemsList = array_values($cart);
        }

        $paymentMethod = strtolower($request->input('payment') ?: 'cod');

        // Verify COD eligibility for destination pincode
        if ($paymentMethod === 'cod') {
            $pincodeService = app(\App\Services\PincodeService::class);
            $pincodeCheck = $pincodeService->checkPincode((string)$request->pincode);
            if (!($pincodeCheck['is_cod_allowed'] ?? true)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cash on Delivery is unavailable for pincode {$request->pincode}. Please choose Prepaid / UPI.",
                    ], 422);
                }
                return back()->with('error', "Cash on Delivery is unavailable for pincode {$request->pincode}. Please choose Prepaid / UPI.")->withInput();
            }
        }

        // Create order
        $order = \App\Models\Order::create([
            'user_id'          => auth()->id(),
            'subtotal'         => $subtotal,
            'shipping_charge'  => $shipping,
            'discount_amount'  => $couponDiscount ?? 0,
            'total_amount'     => $total,
            'payment_method'   => $paymentMethod,
            'payment_status'   => 'pending',
            'status'           => 'confirmed',
            'shipping_name'    => $request->name,
            'shipping_phone'   => $request->phone,
            'shipping_address' => $this->shippingAddressWithLandmark($request->address, $request->landmark),
            'shipping_city'    => $request->city,
            'shipping_state'   => $request->state,
            'shipping_pincode' => $request->pincode,
        ]);

        // Order items save
        foreach ($itemsList as $item) {
            \App\Models\OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['id'],
                'product_name'  => $item['name'],
                'product_image' => $item['image'] ?? '',
                'size'          => $item['size'] ?? '',
                'color'         => $item['color'] ?? '',
                'design_side'   => $item['design_side'] ?? 'front',
                'quantity'      => (int) $item['quantity'],
                'unit_price'    => (float) $item['price'],
                'subtotal'      => (float) $item['price'] * (int) $item['quantity'],
            ]);

            // Stock update
            \App\Models\Product::where('id', $item['id'])->decrement('stock', (int) $item['quantity']);
            \App\Models\Product::where('id', $item['id'])->increment('total_sold', (int) $item['quantity']);
        }

        // Log user activity
        \App\Helpers\ActivityLogger::log('order_placed', "Placed Order #{$order->order_number} via " . strtoupper($paymentMethod) . " (₹" . number_format($total) . ")", [
            'order_id'       => $order->id,
            'order_number'   => $order->order_number,
            'total_amount'   => (float) $total,
            'payment_method' => $paymentMethod,
            'items_count'    => count($itemsList),
        ]);

        // Send Invoice to Customer via Email & WhatsApp simultaneously
        try {
            app(\App\Services\NotificationService::class)->sendOrderInvoiceBoth($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Order placement invoice dispatch error: ' . $e->getMessage());
        }

        // Cart clear
        if (!$isBuyNow) {
            session()->forget('cart');
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'order_number' => $order->order_number,
                'redirect'     => route('order.success', $order->order_number),
            ]);
        }

        return redirect()->route('order.success', $order->order_number);
    }

    // ── GET /order/success (Direct URL / latest order redirect) ────
    public function latestSuccess()
    {
        $lastOrderNumber = session('last_order_number');
        if ($lastOrderNumber) {
            return redirect()->route('order.success', $lastOrderNumber);
        }

        if (auth()->check()) {
            $order = \App\Models\Order::where('user_id', auth()->id())->latest()->first();
            if ($order) {
                return redirect()->route('order.success', $order->order_number);
            }
        }

        $latestOrder = \App\Models\Order::latest()->first();
        if ($latestOrder) {
            return redirect()->route('order.success', $latestOrder->order_number);
        }

        return redirect()->route('shop.index')->with('info', 'No recent orders found.');
    }

    // ── GET /order/success/{order} ─────────────────────────
    public function success($orderNumber)
    {
        $order = \App\Models\Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->orWhere('id', $orderNumber)
            ->firstOrFail();

        // Security: Prevent IDOR URL tampering (customers can only view their own order)
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id() && (!auth()->user()->isStaff())) {
            abort(403, 'Unauthorized access to this order.');
        }

        // 1. Recently Viewed Products from session
        $recentlyViewedIds = collect(session()->get('recently_viewed_products', []))
            ->filter()
            ->unique()
            ->values();

        $orderedProductIds = $order->items->pluck('product_id')->filter()->unique()->values();

        $recentlyViewedProducts = collect();
        if ($recentlyViewedIds->isNotEmpty()) {
            $recentlyViewedProducts = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
                ->whereIn('id', $recentlyViewedIds)
                ->where('is_active', true)
                ->get()
                ->sortBy(fn($item) => $recentlyViewedIds->search($item->id))
                ->values();
        }

        // 2. Fallback / Recommended products if recently viewed is small
        $recommendedProducts = collect();
        if ($recentlyViewedProducts->count() < 4) {
            $categoryIds = $order->items->pluck('product.category_id')->filter()->unique();
            $excludeIds = $recentlyViewedProducts->pluck('id')->merge($orderedProductIds)->unique();

            $recommendedQuery = Product::with(['images', 'media', 'variants', 'productImages', 'category'])
                ->where('is_active', true)
                ->whereNotIn('id', $excludeIds);

            if ($categoryIds->isNotEmpty()) {
                $recommendedQuery->whereIn('category_id', $categoryIds);
            }

            $recommendedProducts = $recommendedQuery->inRandomOrder()->take(8 - $recentlyViewedProducts->count())->get();
        }

        return view('froentend.cart.success', compact('order', 'recentlyViewedProducts', 'recommendedProducts'));
    }

    // ── Razorpay: Create Order ─────────────────────────────
    public function razorpayCreate(Request $request)
    {
        $request->validate([
            'amount'     => 'required|numeric|min:1',
            'product_id' => 'nullable|integer',
        ]);

        $keyId     = config('services.razorpay.key_id') ?: env('RAZORPAY_KEY_ID', 'rzp_test_TRBFsbQ4XURTge');
        $keySecret = config('services.razorpay.key_secret') ?: env('RAZORPAY_KEY_SECRET', 'BtT5h3mgE57NChkD45u2xHPI');

        $amountPaise = (int) round($request->amount * 100); // Razorpay expects paise

        $payload = json_encode([
            'amount'          => $amountPaise,
            'currency'        => 'INR',
            'receipt'         => 'tt_' . uniqid(),
            'payment_capture' => 1,
        ]);

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_USERPWD        => $keyId . ':' . $keySecret,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || empty($data['id'])) {
            $errorDescription = $data['error']['description'] ?? ($curlError ?: 'Razorpay order creation failed.');
            \Illuminate\Support\Facades\Log::error('Razorpay Order Creation Failed', [
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'response' => $data,
                'key_id' => $keyId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Razorpay Error: ' . $errorDescription . '. Please verify your RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in .env.',
            ], 400);
        }

        return response()->json([
            'success'          => true,
            'razorpay_order_id'=> $data['id'],
            'amount'           => $amountPaise,
            'currency'         => 'INR',
            'key'              => $keyId,
        ]);
    }

    // ── Razorpay: Verify Payment ───────────────────────────
    public function razorpayVerify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'order_data'          => 'required|array',
        ]);

        $keySecret = config('services.razorpay.key_secret') ?: env('RAZORPAY_KEY_SECRET', 'BtT5h3mgE57NChkD45u2xHPI');

        // Verify signature
        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            $keySecret
        );

        if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
            return response()->json(['success' => false, 'message' => 'Invalid payment signature.'], 400);
        }

        // Build order from order_data passed by JS
        $orderData = $request->order_data;
        $cart      = session()->get('cart', []);
        $isBuyNow  = !empty($orderData['buy_now']) && !empty($orderData['product_id']);

        $couponDiscount = (float) session()->get('coupon_discount', 0);

        if ($isBuyNow || empty($cart)) {
            // Single product buy-now — use passed data
            $subtotal = (float) ($orderData['subtotal'] ?? 0);
            $shipping = (float) ($orderData['shipping'] ?? 0);
            $discount = (float) ($orderData['discount'] ?? $couponDiscount);
            $total    = (float) ($orderData['total'] ?? max(1, $subtotal + $shipping - $discount));
        } else {
            $subtotal = collect($cart)->sum(fn($i) => (float)$i['price'] * (int)$i['quantity']);
            $shipping = $subtotal >= 999 ? 0 : 50;
            $discount = (float) ($orderData['discount'] ?? $couponDiscount);
            $total    = (float) ($orderData['total'] ?? max(1, $subtotal + $shipping - $discount));
        }

        $order = \App\Models\Order::create([
            'user_id'          => auth()->id(),
            'subtotal'         => $subtotal,
            'shipping_charge'  => $shipping,
            'discount_amount'  => $discount ?? 0,
            'total_amount'     => $total,
            'payment_method'   => 'razorpay',
            'payment_status'   => 'paid',
            'payment_id'       => $request->razorpay_payment_id,
            'razorpay_order_id'=> $request->razorpay_order_id,
            'status'           => 'confirmed',
            'shipping_name'    => $orderData['name']    ?? '',
            'shipping_phone'   => $orderData['phone']   ?? '',
            'shipping_address' => $this->shippingAddressWithLandmark($orderData['address'] ?? '', $orderData['landmark'] ?? ''),
            'shipping_city'    => $orderData['city']    ?? '',
            'shipping_state'   => $orderData['state']   ?? '',
            'shipping_pincode' => $orderData['pincode'] ?? '',
        ]);

        // Save order items from cart (if cart mode)
        if ($isBuyNow) {
            $product = \App\Models\Product::find($orderData['product_id']);
            if ($product) {
                $qty = $this->clampQuantity($orderData['qty'] ?? 1);
                \App\Models\OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'product_image' => $product->image_url ?? ($product->main_image ?? ''),
                    'size'          => $orderData['size'] ?? '',
                    'color'         => $orderData['color'] ?? '',
                    'design_side'   => $orderData['design_side'] ?? ($orderData['designSide'] ?? 'front'),
                    'quantity'      => $qty,
                    'unit_price'    => (float) $product->price,
                    'subtotal'      => $product->price * $qty,
                ]);
                $product->decrement('stock', $qty);
                $product->increment('total_sold', $qty);
            }
        } elseif (!empty($cart)) {
            foreach ($cart as $item) {
                \App\Models\OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['id'],
                    'product_name'  => $item['name'],
                    'product_image' => $item['image'],
                    'size'          => $item['size'] ?? '',
                    'color'         => $item['color'] ?? '',
                    'design_side'   => $item['design_side'] ?? 'front',
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['price'],
                    'subtotal'      => $item['price'] * $item['quantity'],
                ]);
                \App\Models\Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
                \App\Models\Product::where('id', $item['id'])->increment('total_sold', $item['quantity']);
            }
            session()->forget('cart');
        } elseif (!empty($orderData['product_id'])) {
            // Buy-now single product
            $product = \App\Models\Product::find($orderData['product_id']);
            if ($product) {
                \App\Models\OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'product_image' => $product->main_image ?? '',
                    'size'          => $orderData['size'] ?? '',
                    'color'         => $orderData['color'] ?? '',
                    'design_side'   => $orderData['design_side'] ?? ($orderData['designSide'] ?? 'front'),
                    'quantity'      => (int) ($orderData['qty'] ?? 1),
                    'unit_price'    => (float) $product->price,
                    'subtotal'      => $product->price * ($orderData['qty'] ?? 1),
                ]);
                $product->decrement('stock', $orderData['qty'] ?? 1);
                $product->increment('total_sold', $orderData['qty'] ?? 1);
            }
        }

        // Send Invoice to Customer via Email & WhatsApp simultaneously
        try {
            app(\App\Services\NotificationService::class)->sendOrderInvoiceBoth($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Razorpay order invoice dispatch error: ' . $e->getMessage());
        }

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
            'redirect'     => route('order.success', $order->order_number),
        ]);
    }

    // ── Razorpay: Handle Failure ───────────────────────────
    public function razorpayFailure(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => $request->input('error', 'Payment failed or cancelled.'),
        ]);
    }

    // ── PhonePe: Initiate Payment ───────────────────────────
    public function phonepeInitiate(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'landmark'   => 'nullable|string|max:120',
            'city'       => 'required|string|max:100',
            'state'      => 'required|string|max:100',
            'pincode'    => 'required|string|max:10',
            'product_id' => 'nullable|integer',
            'qty'        => 'nullable|integer|min:1',
            'size'       => 'nullable|string',
            'buy_now'    => 'nullable|boolean',
        ]);

        $cart = session()->get('cart', []);
        $isBuyNow = $request->boolean('buy_now') && $request->filled('product_id');

        if ($isBuyNow) {
            $product = \App\Models\Product::find($request->product_id);
            if (!$product || $product->stock <= 0) {
                return response()->json(['success' => false, 'message' => 'Selected product is out of stock.'], 400);
            }
            $qty = $this->clampQuantity($request->input('qty', 1));
            $subtotal = (float) $product->price * $qty;
            $shipping = $subtotal >= 999 ? 0 : 50;
            $couponDiscount = 0;
            $total = max(1, $subtotal + $shipping - $couponDiscount);
            $designSide = $this->resolveDesignSide($request, $product);
            if (!$designSide) {
                return response()->json(['success' => false, 'message' => 'Please select Front Side or Back Side print.'], 422);
            }
            $chosenImage = $product->image_url ?? ($product->main_image ?? '');
            if ($designSide === 'back' && !empty($product->back_image)) {
                $chosenImage = $product->back_image;
            } elseif (($designSide === 'front' || $designSide === 'both') && !empty($product->front_image)) {
                $chosenImage = $product->front_image;
            }
            $itemsPayload = [[
                'id'          => $product->id,
                'name'        => $product->name,
                'price'       => (float) $product->price,
                'quantity'    => $qty,
                'size'        => $request->input('size', ''),
                'color'       => $request->input('color', ''),
                'design_side' => $designSide,
                'image'       => $chosenImage,
            ]];
        } else {
            if (empty($cart)) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
            }
            $subtotal = collect($cart)->sum(fn($i) => (float)$i['price'] * (int)$i['quantity']);
            $shipping = $subtotal >= 999 ? 0 : 50;
            $couponDiscount = (float) session()->get('coupon_discount', 0);
            $total = max(1, $subtotal + $shipping - $couponDiscount);
            $itemsPayload = array_values($cart);
        }

        $merchantTxnId = 'TT_PP_' . date('YmdHis') . '_' . rand(100, 999);
        $redirectUrl = route('payment.phonepe.callback') . '?txn=' . $merchantTxnId;

        // Store pending checkout order payload in session/cache
        $pendingOrderData = [
            'merchant_txn_id' => $merchantTxnId,
            'user_id'         => auth()->id(),
            'name'            => $request->name,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'landmark'        => $request->landmark,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,
            'subtotal'        => $subtotal,
            'shipping'        => $shipping,
            'discount'        => $couponDiscount ?? 0,
            'total'           => $total,
            'items'           => $itemsPayload,
            'is_buy_now'      => $isBuyNow,
        ];

        session()->put('phonepe_pending_' . $merchantTxnId, $pendingOrderData);
        session()->put('phonepe_last_txn_id', $merchantTxnId);

        // Initiate with PhonePe PG Service
        $phonepeService = app(\App\Services\PhonePeService::class);
        $result = $phonepeService->initiatePayment(
            $merchantTxnId,
            $total,
            $redirectUrl,
            $request->phone,
            $request->name
        );

        if ($result['success'] && !empty($result['redirect_url'])) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $result['redirect_url'],
                'txn_id'       => $merchantTxnId,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to connect to PhonePe Gateway. Please try again.',
        ], 400);
    }

    // ── PhonePe: Handle Callback & Verification ────────────
    public function phonepeCallback(Request $request)
    {
        $merchantTxnId = $request->input('txn') 
            ?: $request->input('transactionId') 
            ?: $request->input('merchantTransactionId')
            ?: session()->get('phonepe_last_txn_id');

        if (!$merchantTxnId) {
            return redirect()->route('cart.index')->with('error', 'Invalid PhonePe transaction session.');
        }

        $pendingData = session()->get('phonepe_pending_' . $merchantTxnId);

        // Verify status with PhonePe PG API
        $phonepeService = app(\App\Services\PhonePeService::class);
        $statusCheck = $phonepeService->checkStatus($merchantTxnId);

        if ($statusCheck['success'] || $statusCheck['state'] === 'COMPLETED' || $request->input('code') === 'PAYMENT_SUCCESS') {
            if (!$pendingData) {
                // If session expired, check if order was already recorded
                $existingOrder = \App\Models\Order::where('payment_id', $merchantTxnId)->first();
                if ($existingOrder) {
                    return redirect()->route('order.success', $existingOrder->order_number);
                }
                return redirect()->route('cart.index')->with('error', 'Payment processed. Please check your orders.');
            }

            // Create Order
            $order = \App\Models\Order::create([
                'user_id'          => $pendingData['user_id'] ?? auth()->id(),
                'subtotal'         => $pendingData['subtotal'],
                'shipping_charge'  => $pendingData['shipping'],
                'discount_amount'  => $pendingData['discount'] ?? 0,
                'total_amount'     => $pendingData['total'],
                'payment_method'   => 'phonepe',
                'payment_status'   => 'paid',
                'payment_id'       => $merchantTxnId,
                'status'           => 'confirmed',
                'shipping_name'    => $pendingData['name'],
                'shipping_phone'   => $pendingData['phone'],
                'shipping_address' => $this->shippingAddressWithLandmark($pendingData['address'], $pendingData['landmark'] ?? ''),
                'shipping_city'    => $pendingData['city'],
                'shipping_state'   => $pendingData['state'],
                'shipping_pincode' => $pendingData['pincode'],
            ]);

            // Save Order Items & Stock Decrement
            foreach ($pendingData['items'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['id'],
                    'product_name'  => $item['name'],
                    'product_image' => $item['image'] ?? '',
                    'size'          => $item['size'] ?? '',
                    'color'         => $item['color'] ?? '',
                    'design_side'   => $item['design_side'] ?? 'front',
                    'quantity'      => (int) $item['quantity'],
                    'unit_price'    => (float) $item['price'],
                    'subtotal'      => (float) $item['price'] * (int) $item['quantity'],
                ]);

                \App\Models\Product::where('id', $item['id'])->decrement('stock', (int) $item['quantity']);
                \App\Models\Product::where('id', $item['id'])->increment('total_sold', (int) $item['quantity']);
            }

            // Log activity
            \App\Helpers\ActivityLogger::log('order_placed', "Placed Order #{$order->order_number} via PhonePe (₹" . number_format($order->total_amount) . ")", [
                'order_id'       => $order->id,
                'order_number'   => $order->order_number,
                'total_amount'   => (float) $order->total_amount,
                'payment_method' => 'phonepe',
            ]);

            // Send Invoice simultaneously via Email & WhatsApp (with PDF attached!)
            try {
                app(\App\Services\NotificationService::class)->sendOrderInvoiceBoth($order);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('PhonePe invoice dispatch error: ' . $e->getMessage());
            }

            // Clear cart & session
            session()->forget('cart');
            session()->forget('phonepe_pending_' . $merchantTxnId);
            session()->forget('phonepe_last_txn_id');

            return redirect()->route('order.success', $order->order_number)->with('success', 'Payment successful! Your order has been placed.');
        }

        return redirect()->route('cart.index')->with('error', 'PhonePe payment was cancelled or failed. Please try again.');
    }

    // ── PhonePe: Simulator View for Development / Sandbox ──
    public function phonepeSimulator(Request $request)
    {
        $txnId = $request->input('txn') ?: session()->get('phonepe_last_txn_id');
        $pendingData = session()->get('phonepe_pending_' . $txnId);

        if (!$pendingData) {
            return redirect()->route('cart.index')->with('error', 'Transaction session expired. Please try again.');
        }

        return view('froentend.cart.phonepe_simulator', compact('txnId', 'pendingData'));
    }
}
