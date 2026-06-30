<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/new-arrivals', [App\Http\Controllers\ShopController::class, 'newArrivals'])->name('shop.new-arrivals');
Route::get('/shop/search', [App\Http\Controllers\ShopController::class, 'search'])->name('shop.search');
Route::get('/shop/{slug}', [App\Http\Controllers\ShopController::class, 'category'])->name('shop.category');
Route::get('/collections', [App\Http\Controllers\ShopController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [App\Http\Controllers\ShopController::class, 'collection'])->name('collections.show');
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

// ═══════════════════════════════════════════════════
// FRONTEND API ROUTES (Public - No Auth)
// ═══════════════════════════════════════════════════
Route::prefix('api')->group(function () {
    // Gallery API for frontend display
    Route::get('gallery/media', [App\Http\Controllers\Admin\MediaController::class, 'getGalleryMedia'])->name('api.gallery.media');
    Route::get('gallery/hero-video', [App\Http\Controllers\Admin\MediaController::class, 'getHeroVideo'])->name('api.gallery.hero-video');
});

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
    Route::patch('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/place', [App\Http\Controllers\CartController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/order/success/{order}', [App\Http\Controllers\CartController::class, 'success'])->name('order.success');

    // Wishlist
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{id}', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Newsletter
    Route::post('/newsletter/subscribe', [App\Http\Controllers\HomeController::class, 'subscribe'])->name('newsletter.subscribe');
});

// ═══════════════════════════════════════════════════
// ADMIN PANEL — All staff roles
// ═══════════════════════════════════════════════════
Route::middleware(['auth', 'admin'])
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
            // Products
            Route::get('products/ajax', [App\Http\Controllers\Admin\ProductController::class, 'ajax'])->name('products.ajax');
            Route::post('products/{product}/toggle', [App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');
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
            Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        });

        // ── Customers (super_admin, admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
            Route::get('customers/{user}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
            Route::patch('customers/{user}/toggle', [App\Http\Controllers\Admin\CustomerController::class, 'toggle'])->name('customers.toggle');
        });

        // ── Employees (super_admin, admin, hr)
        Route::middleware('role:super_admin,admin,hr')->group(function () {
            Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
            Route::get('attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
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
            Route::put('gallery/{id}', [App\Http\Controllers\Admin\MediaController::class, 'updateGallery'])->name('media.update-gallery');

            // Delete gallery media
            Route::delete('gallery/{id}', [App\Http\Controllers\Admin\MediaController::class, 'destroyGallery'])->name('media.destroy-gallery');

            // Reorder gallery media
            Route::post('gallery/reorder', [App\Http\Controllers\Admin\MediaController::class, 'reorderGallery'])->name('media.reorder-gallery');
        });

        // ── Media Reorder (existing - keep for backward compatibility)
        Route::post('media/reorder', [App\Http\Controllers\Admin\MediaController::class, 'reorder'])->name('media.reorder');
    });

// ═══════════════════════════════════════════════════
// SEO
// ═══════════════════════════════════════════════════
Route::get('/sitemap.xml', [App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SeoController::class, 'robots'])->name('robots');

// ═══════════════════════════════════════════════════
// AUTH ROUTES
// ═══════════════════════════════════════════════════
require __DIR__ . '/auth.php';
