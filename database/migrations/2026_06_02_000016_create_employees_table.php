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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('employee_id')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();

            $table->string('role')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();

            $table->decimal('salary', 12, 2)->default(0);
            $table->date('joining_date')->nullable();

            $table->string('status')->nullable();

            $table->string('profile_image')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

