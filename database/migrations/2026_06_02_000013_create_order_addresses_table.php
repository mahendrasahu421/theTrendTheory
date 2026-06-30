<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_addresses')) {
            return;
        }

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('type')->default('shipping')->index(); // shipping/billing

            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};

