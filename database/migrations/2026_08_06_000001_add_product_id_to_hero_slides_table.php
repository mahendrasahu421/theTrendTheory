<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_slides', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('button_link')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'product_id')) {
                $table->dropIndex(['product_id']);
                $table->dropColumn('product_id');
            }
        });
    }
};
