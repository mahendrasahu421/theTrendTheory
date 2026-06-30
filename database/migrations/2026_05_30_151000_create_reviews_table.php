<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_image_url')->nullable();

            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('title')->nullable();
            $table->text('comment')->nullable();

            $table->string('product_tag')->nullable();
            $table->unsignedBigInteger('likes')->default(0);

            $table->boolean('is_verified')->default(false)->index();
            $table->boolean('is_approved')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();

            // Media stored as JSON array of URLs/identifiers or a single URL
            $table->json('review_media')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

