<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('ip_address', 45)->index();
                
                // Location / Geo data
                $table->string('country')->nullable();
                $table->string('country_code', 10)->nullable();
                $table->string('state')->nullable()->index();
                $table->string('city')->nullable()->index();
                $table->string('postal_code')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                
                // Traffic Source & Referral
                $table->string('source')->nullable()->index(); // Instagram, Facebook, Google, Direct, Referral, etc.
                $table->text('referrer_url')->nullable();
                $table->text('landing_page')->nullable();
                $table->text('current_url')->nullable();
                
                // UTM Parameters
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->string('utm_term')->nullable();
                $table->string('utm_content')->nullable();
                
                // Device, Hardware & Browser
                $table->string('device_type', 20)->default('mobile')->index(); // mobile, tablet, desktop, bot
                $table->string('device_brand')->nullable()->index(); // Apple, Samsung, OnePlus, Xiaomi, Vivo, Oppo, Realme, etc.
                $table->string('device_model')->nullable(); // iPhone 15, Galaxy S23, etc.
                $table->string('os')->nullable()->index(); // iOS, Android, Windows, macOS, Linux
                $table->string('os_version')->nullable();
                $table->string('browser')->nullable()->index(); // Instagram In-App, Facebook In-App, Chrome, Safari, Edge, etc.
                $table->string('browser_version')->nullable();
                $table->text('user_agent')->nullable();
                
                // Engagement & Time
                $table->unsignedInteger('page_views_count')->default(1);
                $table->timestamp('last_activity_at')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
