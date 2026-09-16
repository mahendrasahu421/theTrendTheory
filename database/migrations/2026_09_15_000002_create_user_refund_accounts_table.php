<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_refund_accounts')) {
            Schema::create('user_refund_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['upi', 'bank_transfer']);
                $table->string('label', 120)->nullable();
                $table->string('upi_id', 120)->nullable();
                $table->string('bank_name', 80)->nullable();
                $table->string('account_number', 40)->nullable();
                $table->string('ifsc_code', 20)->nullable();
                $table->string('account_holder', 120)->nullable();
                $table->boolean('is_primary')->default(false)->index();
                $table->timestamps();

                $table->index(['user_id', 'type']);
            });
        }

        Schema::table('refunds', function (Blueprint $table) {
            if (!Schema::hasColumn('refunds', 'upi_id')) {
                $table->string('upi_id', 120)->nullable()->after('refund_id');
            }
            if (!Schema::hasColumn('refunds', 'account_holder')) {
                $table->string('account_holder', 120)->nullable()->after('ifsc_code');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_refund_accounts');
    }
};
