<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('text', 200);
                $table->string('link', 500)->nullable();
                $table->string('link_text')->nullable();
                $table->string('bg_color', 20)->default('#00285a');
                $table->string('text_color', 20)->default('#ffffff');
                $table->text('image_url')->nullable();
                $table->string('image_public_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->index(['is_active', 'sort_order']);
            });

            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            if (! Schema::hasColumn('announcements', 'image_url')) {
                $table->string('image_url')->nullable()->after('text_color');
            }

            if (! Schema::hasColumn('announcements', 'image_public_id')) {
                $table->string('image_public_id')->nullable()->after('image_url');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
