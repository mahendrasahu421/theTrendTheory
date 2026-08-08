<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            if (!Schema::hasColumn('media', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('alt_text');
            }

            if (!Schema::hasColumn('media', 'button_link')) {
                $table->string('button_link', 1000)->nullable()->after('subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'button_link')) {
                $table->dropColumn('button_link');
            }

            if (Schema::hasColumn('media', 'subtitle')) {
                $table->dropColumn('subtitle');
            }
        });
    }
};
