<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->index()->after('password');
            }

            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('city');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('state');
            }

            if (!Schema::hasColumn('users', 'pincode')) {
                $table->string('pincode', 20)->nullable()->after('address');
            }

            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('pincode');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->index()->after('profile_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['phone', 'role', 'city', 'state', 'address', 'pincode', 'profile_image', 'is_active'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
