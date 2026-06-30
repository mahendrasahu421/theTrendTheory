<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trending_stories', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->string('caption_highlight')->nullable();

            // Media identifiers/paths or full URLs
            $table->string('image')->nullable();

            // Author info
            $table->string('user_name')->nullable();
            $table->string('user_avatar')->nullable();

            // Badge info
            $table->string('badge')->nullable();
            $table->string('badge_type')->nullable();

            // Metrics
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trending_stories');
    }
};

