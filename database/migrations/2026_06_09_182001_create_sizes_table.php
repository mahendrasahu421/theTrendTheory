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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->string('type')->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
