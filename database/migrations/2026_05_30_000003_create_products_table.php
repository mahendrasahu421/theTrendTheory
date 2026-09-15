<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Main product pricing (Product model expects these)
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            // Media / images (some code reads these fields)
            $table->string('image')->nullable();

            // Inventory
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(0);
            $table->integer('total_sold')->default(0);

            // Extra attributes
            $table->string('fabric')->nullable();
            $table->decimal('weight', 12, 2)->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();

            // Flags used across query scopes
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_on_sale')->default(false);

            // Optional self-relations for variant structures used in Product model
            $table->unsignedBigInteger('parent_product_id')->nullable();
            $table->string('product_type')->nullable();

            $table->timestamps();

            $table->index('category_id');
            $table->index('total_sold');
            $table->index('is_active');
            $table->index('is_trending');
            $table->index('is_new');
            $table->index('is_featured');
            $table->index('is_on_sale');

            // Avoid adding FK constraint here because the `categories` table may not exist
            // (or may have mismatched engine/charset) at migration time.
            // Relationship can still work via Eloquent.

        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
};

