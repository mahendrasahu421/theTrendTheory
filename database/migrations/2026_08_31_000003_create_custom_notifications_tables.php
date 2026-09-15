<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->string('title');
                $table->text('message');
                $table->string('type', 50)->default('manual_broadcast')->index(); 
                // types: manual_broadcast, product_launched, offer_created, cart_abandoned, inactive_welcome, order_status, price_drop
                $table->string('action_url')->nullable();
                $table->string('action_label')->nullable();
                $table->string('image_url')->nullable();
                $table->string('icon')->default('bi-bell-fill');
                $table->boolean('is_read')->default(false)->index();
                $table->timestamp('read_at')->nullable();
                $table->json('channels')->nullable(); // ['in_app', 'email', 'whatsapp']
                $table->string('target_audience', 50)->default('all'); // all, inactive, cart_abandoned, buyers, single_user
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('notification_settings')) {
            Schema::create('notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_settings');
    }
};
