<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('ip_address', 45)->nullable()->index();
                
                // Event Data
                $table->string('event_type', 50)->index(); // product_viewed, cart_added, cart_removed, checkout_started, order_placed, wishlist_added, coupon_applied, search_performed, page_viewed
                $table->string('event_title');
                $table->json('event_details')->nullable();
                $table->text('url')->nullable();
                
                // Context & Metadata
                $table->string('source')->nullable()->index(); // Instagram, Facebook, Google, Direct
                $table->string('city')->nullable()->index();
                $table->string('state')->nullable()->index();
                $table->string('country')->nullable();
                $table->string('device_type', 20)->nullable();
                $table->string('device_brand')->nullable();
                $table->string('device_model')->nullable();
                $table->string('browser')->nullable();
                
                // Outreach status
                $table->timestamp('contacted_at')->nullable();
                $table->string('contacted_channel')->nullable(); // whatsapp, email, sms
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
