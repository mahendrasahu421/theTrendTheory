<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_variants')) {
                $table->boolean('has_variants')->default(false)->after('stock');
            }
            if (!Schema::hasColumn('products', 'weight_grams')) {
                $table->integer('weight_grams')->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'fit')) {
                $table->string('fit')->nullable()->after('fabric');
            }
            if (!Schema::hasColumn('products', 'care_instructions')) {
                $table->text('care_instructions')->nullable()->after('fit');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_variants', 'weight_grams', 'fit', 'care_instructions', 'meta_keywords']);
        });
    }
};