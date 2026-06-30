<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            // Stored as path/identifier or full URL (see HeroSlide model)
            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();

            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();

            $table->string('alt_text')->nullable();

            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};

