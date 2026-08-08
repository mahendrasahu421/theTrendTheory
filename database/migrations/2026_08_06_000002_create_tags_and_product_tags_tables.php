<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_tags')) {
            Schema::create('product_tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps();

                $table->unique(['product_id', 'tag_id']);
                $table->index('product_id');
                $table->index('tag_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('tags');
    }
};
