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
        Schema::create('quirofano_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained()->cascadeOnDelete();

            $table->integer('programadas')->nullable();
            $table->integer('realizadas')->nullable();
            $table->integer('suspendidas')->nullable();
            $table->integer('urgencias')->nullable();
            $table->integer('pendientes')->nullable();
            $table->integer('contaminadas')->nullable();
            $table->integer('salas_trabajando')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quirofano_stats');
    }
};
