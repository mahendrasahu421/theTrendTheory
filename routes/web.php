<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/images/{filename}', function (string $filename) {
    $label = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
    $label = htmlspecialchars($label ?: 'Image Placeholder', ENT_QUOTES, 'UTF-8');

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="700" viewBox="0 0 1200 700" role="img" aria-label="{$label}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#f7f2ef"/>
      <stop offset="0.55" stop-color="#d9e7e2"/>
      <stop offset="1" stop-color="#efe1ea"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="700" fill="url(#bg)"/>
  <rect x="48" y="48" width="1104" height="604" rx="18" fill="none" stroke="#ffffff" stroke-width="6" opacity="0.85"/>
  <text x="600" y="326" text-anchor="middle" font-family="Arial, sans-serif" font-size="54" font-weight="700" fill="#222222">THE TREND THEORY</text>
  <text x="600" y="388" text-anchor="middle" font-family="Arial, sans-serif" font-size="28" fill="#555555">{$label}</text>
</svg>
SVG;

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=86400');
})->where('filename', '[A-Za-z0-9._-]+')->name('images.placeholder');
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [App\Http\Controllers\ShopController::class, 'search'])->name('search');
Route::get('/shop/new-arrivals', [App\Http\Controllers\ShopController::class, 'newArrivals'])->name('shop.new-arrivals');
Route::get('/shop/search', [App\Http\Controllers\ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/{slug}', [App\Http\Controllers\ShopController::class, 'category'])->name('shop.category');
Route::get('/collection/{slug}', [App\Http\Controllers\ShopController::class, 'collection'])->name('collection.show');
Route::get('/collections', [App\Http\Controllers\ShopController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [App\Http\Controllers\ShopController::class, 'collection'])->name('collections.show');
Route::get('/api/v1/products/{product}', [App\Http\Controllers\ProductController::class, 'quickView'])->name('api.products.quick-view');
Route::get('/product/{slug}/color/{colorSlug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show.color');
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
// ═══════════════════════════════════════════════════
// LEGAL & POLICY PAGES (Terms & Privacy Policy)
// ═══════════════════════════════════════════════════
Route::get('/terms', function () { return view('froentend.pages.terms'); })->name('terms');
Route::get('/terms-of-use', function () { return view('froentend.pages.terms'); })->name('terms.use');
Route::get('/terms-and-conditions', function () { return view('froentend.pages.terms'); })->name('terms.conditions');
Route::get('/privacy-policy', function () { return view('froentend.pages.privacy'); })->name('privacy.policy');
Route::get('/privacy', function () { return view('froentend.pages.privacy'); })->name('privacy');

Route::get('/pages/terms-of-use', function () { return view('froentend.pages.terms'); });
Route::get('/pages/terms-and-conditions', function () { return view('froentend.pages.terms'); });
Route::get('/pages/privacy-policy', function () { return view('froentend.pages.privacy'); });

Route::get('/page/terms-of-use', function () { return view('froentend.pages.terms'); });
Route::get('/page/terms-and-conditions', function () { return view('froentend.pages.terms'); });
Route::get('/page/privacy-policy', function () { return view('froentend.pages.privacy'); });

Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');
Route::get('/pages/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Blogs & News (Public)
Route::get('/blogs', [App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('/news', [App\Http\Controllers\BlogController::class, 'index'])->name('news.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');
Route::get('/news/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('news.show');

// ── Live Order Tracking (Public)
Route::get('/track-order', [App\Http\Controllers\OrderTrackingController::class, 'index'])->name('order.track');
Route::get('/track-order/{orderNumber}', [App\Http\Controllers\OrderTrackingController::class, 'track'])->name('order.track.detail');
Route::post('/track-order/search', [App\Http\Controllers\OrderTrackingController::class, 'search'])->name('order.track.search');
Route::get('/api/track-order/{orderNumber}', [App\Http\Controllers\OrderTrackingController::class, 'apiTrack'])->name('api.order.track');

// ── Pincode Delivery & Risk Checker (Public API)
Route::get('/api/pincode/check', [App\Http\Controllers\PincodeController::class, 'check'])->name('pincode.check');

// ═══════════════════════════════════════════════════
// FRONTEND API ROUTES (Public - No Auth)
// ═══════════════════════════════════════════════════
Route::prefix('api')->group(function () {
    // Gallery API for frontend display
    Route::get('gallery/media', [App\Http\Controllers\Admin\MediaController::class, 'getGalleryMedia'])->name('api.gallery.media');
    Route::get('gallery/hero-video', [App\Http\Controllers\Admin\MediaController::class, 'getHeroVideo'])->name('api.gallery.hero-video');

    // Web Push Notification Subscription Endpoints
    Route::post('push/subscribe', [App\Http\Controllers\PushNotificationController::class, 'subscribe'])->name('api.push.subscribe');
    Route::post('push/unsubscribe', [App\Http\Controllers\PushNotificationController::class, 'unsubscribe'])->name('api.push.unsubscribe');
    Route::post('push/test', [App\Http\Controllers\PushNotificationController::class, 'sendTest'])->name('api.push.test');

    // Checkout Address Save API (Supports both Authenticated & Guest checkouts)
    Route::post('checkout/address/save', [App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('api.checkout.address.save');
});

// ═══════════════════════════════════════════════════
// NEWSLETTER SUBSCRIPTION (Public)
// ═══════════════════════════════════════════════════
Route::match(['get', 'post'], '/newsletter/subscribe', [App\Http\Controllers\HomeController::class, 'subscribe'])->name('newsletter.subscribe');

// ═══════════════════════════════════════════════════
// AUTH REQUIRED — FRONTEND
// ═══════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    // Reviews Routes
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('coupon.remove');
    Route::post('/coupon/check', [App\Http\Controllers\CouponController::class, 'check'])->name('coupon.check');
    Route::get('/coupon/available', [App\Http\Controllers\CouponController::class, 'available'])->name('coupon.available');

    // Cart
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/add/{id}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add.item');
    Route::match(['patch', 'post'], '/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::match(['patch', 'post'], '/cart/update/{key}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update.item');
    Route::match(['delete', 'post'], '/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::match(['delete', 'post'], '/cart/remove/{key}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove.item');
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/place', [App\Http\Controllers\CartController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/order/success', [App\Http\Controllers\CartController::class, 'latestSuccess'])->name('order.success.latest');
    Route::get('/order/success/{order}', [App\Http\Controllers\CartController::class, 'success'])->name('order.success');

    // Razorpay payment
    Route::post('/payment/razorpay/create', [App\Http\Controllers\CartController::class, 'razorpayCreate'])->name('payment.razorpay.create');
    Route::post('/payment/razorpay/verify', [App\Http\Controllers\CartController::class, 'razorpayVerify'])->name('payment.razorpay.verify');
    Route::post('/payment/razorpay/failure', [App\Http\Controllers\CartController::class, 'razorpayFailure'])->name('payment.razorpay.failure');

    // PhonePe payment gateway
    Route::post('/payment/phonepe/initiate', [App\Http\Controllers\CartController::class, 'phonepeInitiate'])->name('payment.phonepe.initiate');
    Route::match(['get', 'post'], '/payment/phonepe/callback', [App\Http\Controllers\CartController::class, 'phonepeCallback'])->name('payment.phonepe.callback');
    Route::get('/payment/phonepe/simulator', [App\Http\Controllers\CartController::class, 'phonepeSimulator'])->name('payment.phonepe.simulator');

    // Wishlist
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{id}', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/addresses', [App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::patch('/profile/addresses/{address}/default', [App\Http\Controllers\ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
    Route::delete('/profile/addresses/{address}', [App\Http\Controllers\ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Order Cancel (Customer)
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('order.cancel');

    // Return / Exchange / Refund (Customer)
    Route::get('/orders/{order}/return', [App\Http\Controllers\ReturnController::class, 'create'])->name('order.return.create');
    Route::post('/orders/{order}/return', [App\Http\Controllers\ReturnController::class, 'store'])->name('order.return.store');
    Route::get('/my-returns', [App\Http\Controllers\ReturnController::class, 'index'])->name('order.returns');
    Route::get('/my-returns/{return}', [App\Http\Controllers\ReturnController::class, 'show'])->name('order.return.show');
});

// ═══════════════════════════════════════════════════
// ADMIN AUTHENTICATION
// ═══════════════════════════════════════════════════
Route::get('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

// ═══════════════════════════════════════════════════
// ADMIN PANEL — All staff roles
// ═══════════════════════════════════════════════════
Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ── Dashboard Routes
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/super', [App\Http\Controllers\Admin\SuperAdminDashboardController::class, 'index'])
            ->middleware('role:super_admin')->name('dashboard.super');
        Route::get('/dashboard/admin', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
            ->middleware('role:super_admin,admin')->name('dashboard.admin');
        Route::get('/dashboard/hr', [App\Http\Controllers\Admin\HRDashboardController::class, 'index'])
            ->middleware('role:super_admin,admin,hr')->name('dashboard.hr');
        Route::get('/dashboard/products', [App\Http\Controllers\Admin\ProductEditorDashboardController::class, 'index'])
            ->middleware('role:super_admin,admin,product_manager,product_editor')->name('dashboard.product_editor');

        // ── Visitor Tracking & Traffic Analytics (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('investor-dashboard', [App\Http\Controllers\Admin\InvestorDashboardController::class, 'index'])->name('investor.dashboard');
            Route::get('sales-analytics', [App\Http\Controllers\Admin\SalesAnalyticsController::class, 'index'])->name('sales.analytics');
            Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('analytics/activities', [App\Http\Controllers\Admin\AnalyticsController::class, 'activities'])->name('analytics.activities');
            Route::post('analytics/send-notification', [App\Http\Controllers\Admin\AnalyticsController::class, 'sendNotification'])->name('analytics.notify');
            Route::get('analytics/export', [App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
            Route::post('analytics/clear', [App\Http\Controllers\Admin\AnalyticsController::class, 'clearOldLogs'])->name('analytics.clear');
        });

        // ── Automated & Broadcast Notifications (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
            Route::post('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
            Route::post('notifications/settings', [App\Http\Controllers\Admin\NotificationController::class, 'updateSettings'])->name('notifications.settings');
            Route::post('notifications/trigger', [App\Http\Controllers\Admin\NotificationController::class, 'triggerAutomation'])->name('notifications.trigger');
            Route::delete('notifications/{notification}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
        });

        // ── Shipping (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('shipping', [App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
            Route::post('shipping', [App\Http\Controllers\Admin\ShippingController::class, 'update'])->name('shipping.update');
        });

        // ── Announcements (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
            Route::post('announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
            Route::put('announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
            Route::delete('announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
            Route::post('announcements/{announcement}/toggle', [App\Http\Controllers\Admin\AnnouncementController::class, 'toggle'])->name('announcements.toggle');
        });

        // ── Newsletter Subscribers (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('newsletter-subscribers', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
            Route::post('newsletter-subscribers', [App\Http\Controllers\Admin\NewsletterController::class, 'store'])->name('newsletter.store');
            Route::post('newsletter-subscribers/bulk', [App\Http\Controllers\Admin\NewsletterController::class, 'bulkAction'])->name('newsletter.bulk');
            Route::get('newsletter-subscribers/export', [App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');
            Route::post('newsletter-subscribers/{subscriber}/toggle', [App\Http\Controllers\Admin\NewsletterController::class, 'toggle'])->name('newsletter.toggle');
            Route::delete('newsletter-subscribers/{subscriber}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');
        });

        // ── Blogs (super_admin, admin, product_manager, product_editor)
        Route::middleware('role:super_admin,admin,product_manager,product_editor')->group(function () {
            Route::post('blogs/{blog}/toggle-status', [App\Http\Controllers\Admin\BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
            Route::post('blogs/{blog}/toggle-featured', [App\Http\Controllers\Admin\BlogController::class, 'toggleFeatured'])->name('blogs.toggle-featured');
            Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
        });

        // ── News & Press (super_admin, admin, product_manager, product_editor)
        Route::middleware('role:super_admin,admin,product_manager,product_editor')->group(function () {
            Route::post('news/{news}/toggle-status', [App\Http\Controllers\Admin\NewsController::class, 'toggleStatus'])->name('news.toggle-status');
            Route::post('news/{news}/toggle-featured', [App\Http\Controllers\Admin\NewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
            Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
        });

        // ── Pages
        Route::get('/page/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');

        // ── FAQs (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('faqs/ajax', [App\Http\Controllers\Admin\FaqController::class, 'ajax'])->name('faqs.ajax');
            Route::post('faqs/{faq}/toggle', [App\Http\Controllers\Admin\FaqController::class, 'toggle'])->name('faqs.toggle');
            Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class)->except('show', 'edit', 'create');
        });

        // ── Masters (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('masters/{type}/ajax', [App\Http\Controllers\Admin\MasterController::class, 'ajax'])->name('masters.ajax');
            Route::get('masters/{type}', [App\Http\Controllers\Admin\MasterController::class, 'index'])->name('masters.index');
            Route::post('masters/{type}', [App\Http\Controllers\Admin\MasterController::class, 'store'])->name('masters.store');
            Route::put('masters/{type}/{id}', [App\Http\Controllers\Admin\MasterController::class, 'update'])->name('masters.update');
            Route::delete('masters/{type}/{id}', [App\Http\Controllers\Admin\MasterController::class, 'destroy'])->name('masters.destroy');
            Route::post('masters/{type}/{id}/toggle', [App\Http\Controllers\Admin\MasterController::class, 'toggle'])->name('masters.toggle');
        });

        // ── Products & Media (super_admin, admin, product_manager)
        Route::middleware('role:super_admin,admin,product_manager,product_editor')->group(function () {
            // Products & Inventory
            Route::get('inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
            Route::post('inventory/{product}/stock', [App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
            Route::post('inventory/{product}/threshold', [App\Http\Controllers\Admin\InventoryController::class, 'updateThreshold'])->name('inventory.update-threshold');
            Route::get('products/ajax', [App\Http\Controllers\Admin\ProductController::class, 'ajax'])->name('products.ajax');
            Route::get('products/generate-sku', [App\Http\Controllers\Admin\ProductController::class, 'generateSku'])->name('products.generate-sku');
            Route::get('products/check-sku', [App\Http\Controllers\Admin\ProductController::class, 'checkSku'])->name('products.check-sku');
            Route::post('products/{product}/toggle', [App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');
            Route::post('products/{product}/set-main-image', [App\Http\Controllers\Admin\ProductController::class, 'setMainImage'])->name('products.set-main-image');
            Route::post('products/{product}/delete-image', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.delete-image');
            Route::delete('products/{product}/images/{image?}', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.destroy');
            Route::get('products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
            Route::post('products/bulk-action', [App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])->name('products.bulk-action');
            Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

            // Product Variants
            Route::post('product-variants', [App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('product-variants.store');
            Route::put('product-variants/{variant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'update'])->name('product-variants.update');
            Route::delete('product-variants/{variant}', [App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('product-variants.destroy');

            // Product Images (color-wise)
            Route::post('product-images', [App\Http\Controllers\Admin\ProductImageController::class, 'store'])->name('product-images.store');
            Route::post('product-images/{image}/primary', [App\Http\Controllers\Admin\ProductImageController::class, 'setPrimary'])->name('product-images.primary');
            Route::delete('product-images/{image}', [App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('product-images.destroy');

            // Product Media (existing)
            Route::post('media/upload', [App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('media.upload');
            Route::delete('media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');
            Route::post('media/{media}/primary', [App\Http\Controllers\Admin\MediaController::class, 'setPrimary'])->name('media.primary');

            // Colors Master
            Route::get('colors/ajax', [App\Http\Controllers\Admin\ColorController::class, 'ajax'])->name('colors.ajax');
            Route::post('colors/{color}/toggle', [App\Http\Controllers\Admin\ColorController::class, 'toggle'])->name('colors.toggle');
            Route::resource('colors', App\Http\Controllers\Admin\ColorController::class);

            // Sizes Master
            Route::get('sizes/ajax', [App\Http\Controllers\Admin\SizeController::class, 'ajax'])->name('sizes.ajax');
            Route::post('sizes/{size}/toggle', [App\Http\Controllers\Admin\SizeController::class, 'toggle'])->name('sizes.toggle');
            Route::resource('sizes', App\Http\Controllers\Admin\SizeController::class);
        });

        // ── Categories (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('categories/ajax', [App\Http\Controllers\Admin\CategoryController::class, 'ajax'])->name('categories.ajax');
            Route::post('categories/{category}/toggle', [App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('categories.toggle');
            Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        });

        // ── Reviews (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
            Route::patch('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
            Route::delete('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
        });

        // ── Orders (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('orders/ajax', [App\Http\Controllers\Admin\OrderController::class, 'ajax'])->name('orders.ajax');
            Route::post('orders/bulk-status', [App\Http\Controllers\Admin\OrderController::class, 'bulkStatus'])->name('orders.bulk-status');
            Route::get('orders/shipping-labels', [App\Http\Controllers\Admin\OrderController::class, 'bulkShippingLabels'])->name('orders.shipping-labels');
            Route::post('orders/{order}/quick-status', [App\Http\Controllers\Admin\OrderController::class, 'quickStatus'])->name('orders.quick-status');
            Route::get('orders/{order}/invoice', function (\App\Models\Order $order) {
                return app(\App\Services\InvoicePdfService::class)->streamPdf($order);
            })->name('orders.invoice');
            Route::get('orders/{order}/shipping-label', function (\App\Models\Order $order) {
                $order->loadMissing(['items.product', 'user']);
                return view('invoices.shipping_label', compact('order'));
            })->name('orders.shipping-label');
            Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        });

        // ── Returns & Refunds (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('returns', [App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('returns.index');
            Route::get('returns/{return}', [App\Http\Controllers\Admin\ReturnController::class, 'show'])->name('returns.show');
            Route::patch('returns/{return}/status', [App\Http\Controllers\Admin\ReturnController::class, 'updateStatus'])->name('returns.update-status');
            Route::patch('returns/{return}/refund', [App\Http\Controllers\Admin\ReturnController::class, 'updateRefund'])->name('returns.update-refund');
        });

        // ── Pincodes & Risk Rules (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('pincodes', [App\Http\Controllers\Admin\PincodeController::class, 'index'])->name('pincodes.index');
            Route::post('pincodes', [App\Http\Controllers\Admin\PincodeController::class, 'store'])->name('pincodes.store');
            Route::post('pincodes/auto-analyze', [App\Http\Controllers\Admin\PincodeController::class, 'autoAnalyze'])->name('pincodes.auto-analyze');
            Route::patch('pincodes/{pincodeRule}/toggle-cod', [App\Http\Controllers\Admin\PincodeController::class, 'toggleCod'])->name('pincodes.toggle-cod');
            Route::patch('pincodes/{pincodeRule}/toggle-exchange', [App\Http\Controllers\Admin\PincodeController::class, 'toggleExchange'])->name('pincodes.toggle-exchange');
            Route::delete('pincodes/{pincodeRule}', [App\Http\Controllers\Admin\PincodeController::class, 'destroy'])->name('pincodes.destroy');
        });

        // ── Customers (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
            Route::get('customers/ajax', [App\Http\Controllers\Admin\CustomerController::class, 'ajax'])->name('customers.ajax');
            Route::get('customers/{user}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
            Route::patch('customers/{user}/toggle', [App\Http\Controllers\Admin\CustomerController::class, 'toggle'])->name('customers.toggle');
        });

        // ── Employees (super_admin, admin, hr)
        Route::middleware('role:super_admin,admin,hr')->group(function () {
            Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
            Route::get('attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
        });

        // ── Staff & Role Permissions Management (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::post('staff/{staff}/toggle-status', [App\Http\Controllers\Admin\StaffPermissionController::class, 'toggleStatus'])->name('staff.toggle-status');
            Route::resource('staff', App\Http\Controllers\Admin\StaffPermissionController::class);
        });

        // ── Coupons (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('coupons/ajax', [App\Http\Controllers\Admin\CouponController::class, 'ajax'])->name('coupons.ajax');
            Route::post('coupons/{coupon}/toggle', [App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');
            Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
        });

        // ── Settings & Hero Slides (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

            // Payment Gateways Settings
            Route::get('settings/payment', [App\Http\Controllers\Admin\SettingController::class, 'paymentSettings'])->name('settings.payment');
            Route::post('settings/payment', [App\Http\Controllers\Admin\SettingController::class, 'updatePaymentSettings'])->name('settings.payment.update');

            // Shipping & Courier Settings
            Route::get('settings/shipping', [App\Http\Controllers\Admin\SettingController::class, 'shippingSettings'])->name('settings.shipping');
            Route::post('settings/shipping', [App\Http\Controllers\Admin\SettingController::class, 'updateShippingSettings'])->name('settings.shipping.update');

            // Email Templates & SMTP Settings
            Route::get('settings/email-templates', [App\Http\Controllers\Admin\SettingController::class, 'emailTemplates'])->name('settings.email-templates');
            Route::post('settings/email-templates', [App\Http\Controllers\Admin\SettingController::class, 'updateEmailTemplates'])->name('settings.email-templates.update');
            Route::post('settings/email-templates/test', [App\Http\Controllers\Admin\SettingController::class, 'sendTestEmail'])->name('settings.email-templates.test');

            // SMS & WhatsApp Gateway Settings
            Route::get('settings/sms', [App\Http\Controllers\Admin\SettingController::class, 'smsSettings'])->name('settings.sms');
            Route::post('settings/sms', [App\Http\Controllers\Admin\SettingController::class, 'updateSmsSettings'])->name('settings.sms.update');
            Route::post('settings/sms/test', [App\Http\Controllers\Admin\SettingController::class, 'sendTestSms'])->name('settings.sms.test');

            // Hero Slides Routes
            Route::post('settings/slides', [App\Http\Controllers\Admin\SettingController::class, 'storeSlide'])->name('settings.storeSlide');
            Route::put('settings/slides/{slide}', [App\Http\Controllers\Admin\SettingController::class, 'updateSlide'])->name('settings.updateSlide');
            Route::delete('settings/slides/{slide}', [App\Http\Controllers\Admin\SettingController::class, 'destroySlide'])->name('settings.destroySlide');
            Route::post('settings/slides/{slide}/toggle', [App\Http\Controllers\Admin\SettingController::class, 'toggleSlide'])->name('settings.toggleSlide');
            Route::post('settings/slides/reorder', [App\Http\Controllers\Admin\SettingController::class, 'reorderSlides'])->name('settings.reorderSlides');

            // Backup Routes
            Route::post('backup/create', [App\Http\Controllers\Admin\SettingController::class, 'createBackup'])->name('backup.create');
            Route::get('backups', [App\Http\Controllers\Admin\SettingController::class, 'listBackups'])->name('backups.index');
            Route::get('backup/download/{filename}', [App\Http\Controllers\Admin\SettingController::class, 'downloadBackup'])->name('backup.download');
            Route::delete('backup/delete/{filename}', [App\Http\Controllers\Admin\SettingController::class, 'deleteBackup'])->name('backup.delete');

            // System Routes
            Route::post('cache/clear', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('cache.clear');
            Route::get('system/info', [App\Http\Controllers\Admin\SettingController::class, 'systemInfo'])->name('system.info');
        });

        // ========== NEW: GALLERY MEDIA ROUTES (super_admin, admin) ==========
        Route::middleware('role:super_admin,admin')->prefix('media')->group(function () {
            // Upload gallery media (images/videos)
            Route::post('upload-gallery', [App\Http\Controllers\Admin\MediaController::class, 'uploadGallery'])->name('media.upload-gallery');

            // Get all gallery media
            Route::get('gallery', [App\Http\Controllers\Admin\MediaController::class, 'getGalleryMedia'])->name('media.gallery');

            // Get single gallery item
            Route::get('gallery/{id}', [App\Http\Controllers\Admin\MediaController::class, 'getGalleryMediaItem'])->name('media.gallery-item');

            // Update gallery media
            Route::match(['put', 'post'], 'gallery/{id}', [App\Http\Controllers\Admin\MediaController::class, 'updateGallery'])->name('media.update-gallery');

            // Delete gallery media
            Route::delete('gallery/{id}', [App\Http\Controllers\Admin\MediaController::class, 'destroyGallery'])->name('media.destroy-gallery');

            // Reorder gallery media
            Route::post('gallery/reorder', [App\Http\Controllers\Admin\MediaController::class, 'reorderGallery'])->name('media.reorder-gallery');
        });

        // ── Media Reorder (existing - keep for backward compatibility)
        Route::post('media/reorder', [App\Http\Controllers\Admin\MediaController::class, 'reorder'])->name('media.reorder');
    });

// ═══════════════════════════════════════════════════
// IN-APP NOTIFICATIONS (FRONTEND)
// ═══════════════════════════════════════════════════
Route::get('/notifications/feed', [App\Http\Controllers\NotificationApiController::class, 'feed'])->name('notifications.feed');
Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationApiController::class, 'markRead'])->name('notifications.read');
Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationApiController::class, 'markAllRead'])->name('notifications.markAllRead');

// ═══════════════════════════════════════════════════
// PUBLIC / CUSTOMER TAX INVOICE DOWNLOAD (FLIPKART / AMAZON PDF STYLE)
// ═══════════════════════════════════════════════════
Route::get('/invoice/{orderNumber}', function ($orderNumber) {
    $order = \App\Models\Order::where('order_number', $orderNumber)->orWhere('id', $orderNumber)->firstOrFail();
    return app(\App\Services\InvoicePdfService::class)->streamPdf($order);
})->name('invoice.download');

// ═══════════════════════════════════════════════════
// SEO
// ═══════════════════════════════════════════════════
Route::get('/sitemap.xml', [App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SeoController::class, 'robots'])->name('robots');

// ═══════════════════════════════════════════════════
// AUTH ROUTES
// ═══════════════════════════════════════════════════
require __DIR__ . '/auth.php';
