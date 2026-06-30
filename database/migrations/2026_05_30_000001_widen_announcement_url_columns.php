<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('announcements')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE announcements MODIFY link VARCHAR(500) NULL');
            DB::statement('ALTER TABLE announcements MODIFY image_url TEXT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('announcements') || DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE announcements MODIFY link VARCHAR(255) NULL');
        DB::statement('ALTER TABLE announcements MODIFY image_url VARCHAR(255) NULL');
    }
};
