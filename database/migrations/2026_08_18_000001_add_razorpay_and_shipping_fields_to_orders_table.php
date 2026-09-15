<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Payment method & IDs
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('payment_id');
            }

            // Shipping / address fields
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->nullable()->after('shipping_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_phone');
            }
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'shipping_state')) {
                $table->string('shipping_state')->nullable()->after('shipping_city');
            }
            if (!Schema::hasColumn('orders', 'shipping_pincode')) {
                $table->string('shipping_pincode', 20)->nullable()->after('shipping_state');
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('shipping_pincode');
            }
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('tracking_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_id', 'razorpay_order_id',
                'shipping_name', 'shipping_phone', 'shipping_address',
                'shipping_city', 'shipping_state', 'shipping_pincode',
                'tracking_number', 'courier_name',
            ]);
        });
    }
};
