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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();

            // Support for both ID-based relations (ProductVariantController) and string values (ProductController)
            $table->unsignedBigInteger('size_id')->nullable()->index();
            $table->unsignedBigInteger('color_id')->nullable()->index();

            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('color_hex', 20)->nullable();

            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};