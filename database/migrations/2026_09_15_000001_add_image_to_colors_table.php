<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('colors') && !Schema::hasColumn('colors', 'image')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->string('image')->nullable()->after('hex');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('colors') && Schema::hasColumn('colors', 'image')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
