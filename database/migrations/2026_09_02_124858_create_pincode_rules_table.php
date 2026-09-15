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
        Schema::create('pincode_rules', function (Blueprint $table) {
            $table->id();
            $table->string('pincode', 10)->unique()->index();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zone', 50)->default('Standard'); // Metro, Tier-1, Tier-2, Remote, Special
            $table->unsignedInteger('delivery_days_min')->default(3);
            $table->unsignedInteger('delivery_days_max')->default(5);
            $table->boolean('is_serviceable')->default(true);
            $table->boolean('is_cod_allowed')->default(true);
            $table->boolean('is_return_allowed')->default(true);
            $table->boolean('is_exchange_only')->default(false);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('cod_orders')->default(0);
            $table->unsignedInteger('returned_orders')->default(0);
            $table->unsignedInteger('rto_orders')->default(0);
            $table->decimal('return_rate', 5, 2)->default(0.00); // percentage
            $table->decimal('rto_rate', 5, 2)->default(0.00); // percentage
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pincode_rules');
    }
};
