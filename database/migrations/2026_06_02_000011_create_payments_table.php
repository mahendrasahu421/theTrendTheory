<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            return;
        }

        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('method')->nullable();

            // Matches App\Models\Payment::isPaid(): status === 'paid'
            $table->string('status')->default('pending')->index();

            $table->decimal('amount', 12, 2)->default(0);

            $table->string('payment_id')->nullable();
            $table->string('gateway_order_id')->nullable();
            $table->text('gateway_response')->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->timestamps();

            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

