<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Returns ──────────────────────────────────────────────
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('return_number', 40)->unique();
            $table->enum('type', ['return', 'exchange'])->default('return');
            $table->enum('status', ['pending', 'approved', 'rejected', 'picked_up', 'received', 'completed'])->default('pending');
            $table->string('reason', 80)->nullable(); // wrong_item, damaged, size_issue, changed_mind, other
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            // Exchange-specific
            $table->string('exchange_size', 20)->nullable();
            $table->string('exchange_color', 40)->nullable();
            // Admin notes
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // ── Return Items ─────────────────────────────────────────
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('reason', 80)->nullable();
            $table->timestamps();
        });

        // ── Refunds ──────────────────────────────────────────────
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->nullable()->constrained('returns')->nullOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['original_payment', 'bank_transfer', 'store_credit', 'upi'])->default('original_payment');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('refund_id', 80)->nullable();   // Gateway refund ID
            $table->string('upi_id', 120)->nullable();
            $table->string('bank_name', 80)->nullable();
            $table->string('account_number', 40)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('account_holder', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
    }
};
