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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['blog', 'news'])->default('blog');
            $table->string('category')->default('Fashion Trends');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('image_url')->nullable();
            $table->string('image_public_id')->nullable();
            $table->string('author_name')->default('Vayu Editorial');
            $table->string('read_time')->default('4 min read');
            $table->string('tags')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_published']);
            $table->index('category');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
