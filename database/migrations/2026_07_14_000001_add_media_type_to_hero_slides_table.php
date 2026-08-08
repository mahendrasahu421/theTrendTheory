<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_slides', 'media_type')) {
                $table->string('media_type', 20)->default('image')->after('subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
