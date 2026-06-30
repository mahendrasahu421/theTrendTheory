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
        Schema::table('reviews', function (Blueprint $table) {
            // Add product_id column as an unsigned big integer, allowing nulls initially.
            // Placing it after 'id' for logical order.
            if (!Schema::hasColumn('reviews', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('id');
                // Add an index for faster lookups.
                $table->index('product_id');
                // Add a foreign key constraint to the 'products' table.
                // This assumes a 'products' table exists with an 'id' primary key.
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Drop foreign key first
            $table->dropColumn('product_id');
        });
    }
};