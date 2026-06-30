<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_id')->nullable()->index();

            $table->string('name');
            $table->string('slug');

            $table->text('description')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Images (stored paths/identifiers; app builds final URL)
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();

            // Ordering/visibility
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('show_in_nav')->default(true)->index();
            $table->boolean('show_in_home')->default(true)->index();

            $table->timestamps();

            $table->unique('slug');

            // NOTE: No FK constraint added intentionally.
            // Some existing databases may have mismatched charset/engine.
            // Eloquent relationships still work without FK.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

