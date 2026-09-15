<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique()->index();
                $table->text('description')->nullable();
                $table->string('type', 20)->default('percentage'); // percentage, fixed
                $table->decimal('value', 10, 2)->default(0);
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_rules')) {
            Schema::create('coupon_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
                $table->decimal('min_order_amount', 10, 2)->nullable();
                $table->decimal('max_discount_amount', 10, 2)->nullable();
                $table->integer('usage_limit_total')->nullable();
                $table->integer('usage_limit_per_user')->nullable();
                $table->dateTime('valid_from')->nullable();
                $table->dateTime('valid_until')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->decimal('discount_applied', 10, 2)->default(0);
                $table->dateTime('used_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_products')) {
            Schema::create('coupon_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_categories')) {
            Schema::create('coupon_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_rules');
        Schema::dropIfExists('coupons');
    }
};
