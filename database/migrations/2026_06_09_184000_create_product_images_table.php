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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('color_id')->nullable()->index(); // For color-specific images

            $table->string('file_id')->nullable(); // Cloudinary/ImageKit public ID
            $table->string('url', 1000)->nullable(); // Full URL of the image
            $table->string('alt_text')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};