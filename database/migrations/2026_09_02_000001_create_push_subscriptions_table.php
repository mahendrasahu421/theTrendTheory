<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->text('endpoint');
                $table->string('endpoint_hash', 64)->unique()->index();
                $table->text('public_key')->nullable(); // p256dh
                $table->text('auth_token')->nullable(); // auth secret
                $table->string('content_encoding')->default('aesgcm');
                $table->string('user_agent')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
