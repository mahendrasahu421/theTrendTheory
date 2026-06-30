<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            return;
        }

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('collection')->default('default');

            $table->string('file_name')->nullable();
            $table->string('file_id')->nullable();
            $table->string('url', 1000)->nullable();
            $table->string('thumb_url', 1000)->nullable();

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('mime_type')->nullable();

            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            $table->index(['model_type', 'model_id', 'collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

