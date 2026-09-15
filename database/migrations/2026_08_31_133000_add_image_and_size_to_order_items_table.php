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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->text('product_image')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('order_items', 'size')) {
                $table->string('size', 50)->nullable()->after('product_image');
            }
            if (!Schema::hasColumn('order_items', 'color')) {
                $table->string('color', 50)->nullable()->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_image')) {
                $table->dropColumn('product_image');
            }
            if (Schema::hasColumn('order_items', 'size')) {
                $table->dropColumn('size');
            }
            if (Schema::hasColumn('order_items', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
