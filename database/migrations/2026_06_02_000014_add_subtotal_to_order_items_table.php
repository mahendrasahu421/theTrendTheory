<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_items')) {
            return;
        }

        if (!Schema::hasColumn('order_items', 'subtotal')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('unit_price');
            });
        }

        // Keep backward compatibility with any existing application logic:
        // if line_total exists, mirror it into subtotal.
        Schema::table('order_items', function (Blueprint $table) {
            // no-op schema; data sync via query below
        });

        // Data sync: subtotal = line_total (only when line_total column exists)
        if (Schema::hasColumn('order_items', 'line_total')) {
            DB::statement('UPDATE order_items SET subtotal = COALESCE(line_total, 0) WHERE subtotal = 0');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'subtotal')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('subtotal');
            });
        }
    }
};

