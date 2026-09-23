<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('push_subscriptions')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('push_subscriptions', 'fcm_token')) {
                    $table->text('fcm_token')->nullable()->after('auth_token');
                }
                if (!Schema::hasColumn('push_subscriptions', 'device_type')) {
                    $table->string('device_type', 32)->default('web')->after('content_encoding');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('push_subscriptions')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                if (Schema::hasColumn('push_subscriptions', 'fcm_token')) {
                    $table->dropColumn('fcm_token');
                }
                if (Schema::hasColumn('push_subscriptions', 'device_type')) {
                    $table->dropColumn('device_type');
                }
            });
        }
    }
};
