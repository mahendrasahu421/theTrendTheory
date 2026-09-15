<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'front_image')) {
                $table->text('front_image')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'back_image')) {
                $table->text('back_image')->nullable()->after('front_image');
            }
            if (!Schema::hasColumn('products', 'available_print_sides')) {
                $table->string('available_print_sides', 30)->default('both')->after('back_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'available_print_sides')) {
                $table->dropColumn('available_print_sides');
            }
            if (Schema::hasColumn('products', 'back_image')) {
                $table->dropColumn('back_image');
            }
            if (Schema::hasColumn('products', 'front_image')) {
                $table->dropColumn('front_image');
            }
        });
    }
};