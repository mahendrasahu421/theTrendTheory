<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'color_name')) {
                $table->string('color_name')->nullable()->after('product_type');
            }

            if (!Schema::hasColumn('products', 'color_hex')) {
                $table->string('color_hex', 20)->nullable()->after('color_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'color_hex')) {
                $table->dropColumn('color_hex');
            }

            if (Schema::hasColumn('products', 'color_name')) {
                $table->dropColumn('color_name');
            }
        });
    }
};
