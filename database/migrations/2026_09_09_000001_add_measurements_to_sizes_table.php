<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sizes')) {
            return;
        }

        Schema::table('sizes', function (Blueprint $table) {
            if (!Schema::hasColumn('sizes', 'chest')) {
                $table->string('chest')->nullable()->after('label');
            }

            if (!Schema::hasColumn('sizes', 'waist')) {
                $table->string('waist')->nullable()->after('chest');
            }

            if (!Schema::hasColumn('sizes', 'length')) {
                $table->string('length')->nullable()->after('waist');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sizes')) {
            return;
        }

        Schema::table('sizes', function (Blueprint $table) {
            foreach (['length', 'waist', 'chest'] as $column) {
                if (Schema::hasColumn('sizes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
