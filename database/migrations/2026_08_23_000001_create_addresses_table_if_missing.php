<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('type', 30)->default('Home');
                $table->string('name')->nullable();
                $table->string('phone', 20)->nullable();
                $table->text('address_line');
                $table->string('city', 100)->nullable();
                $table->string('state', 100)->nullable();
                $table->string('pincode', 10)->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('location_source', 30)->nullable();
                $table->boolean('is_default')->default(false)->index();
                $table->timestamps();
            });

            return;
        }

        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'type')) {
                $table->string('type', 30)->default('Home')->after('user_id');
            }
            if (!Schema::hasColumn('addresses', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('pincode');
            }
            if (!Schema::hasColumn('addresses', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('addresses', 'location_source')) {
                $table->string('location_source', 30)->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('addresses')) {
            return;
        }

        Schema::table('addresses', function (Blueprint $table) {
            foreach (['location_source', 'longitude', 'latitude', 'type'] as $column) {
                if (Schema::hasColumn('addresses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
