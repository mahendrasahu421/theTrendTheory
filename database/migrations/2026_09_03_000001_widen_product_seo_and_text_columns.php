<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Using raw SQL for guaranteed compatibility across MySQL/MariaDB without requiring doctrine/dbal
        try {
            DB::statement("ALTER TABLE `products` MODIFY `meta_keywords` TEXT NULL");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` MODIFY `meta_description` TEXT NULL");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` MODIFY `short_description` TEXT NULL");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` MODIFY `care_instructions` TEXT NULL");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` MODIFY `meta_title` VARCHAR(255) NULL");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` MODIFY `og_image` TEXT NULL");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `products` MODIFY `meta_keywords` VARCHAR(255) NULL");
        } catch (\Throwable $e) {}
    }
};
