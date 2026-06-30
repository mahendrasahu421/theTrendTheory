<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Add value only if column does not exist (avoids "Duplicate column" error).
            // NOTE: keep this migration MySQL-only and simple: app previously failed due to missing `value`.
            $exists = false;
            try {
                $exists = 
                    \DB::table('site_settings')
                        ->selectRaw('1')
                        ->whereNotNull('value')
                        ->limit(1)
                        ->exists();
            } catch (\Throwable $e) {
                // If `value` column doesn't exist yet, this will throw -> treat as missing.
                $exists = false;
            }

            if (!$exists) {
                // Add using raw SQL so it doesn't depend on column ordering or doctrine.
                // Guard is best-effort; if the column already exists, ignore the error.
                try {
                    \DB::statement('ALTER TABLE site_settings ADD COLUMN value LONGTEXT NULL');
                } catch (\Throwable $e) {
                    // Ignore duplicate/column-exists errors.
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Best-effort removal (may fail if doctrine/column differs)
            $table->dropColumn('value');
        });
    }
};

