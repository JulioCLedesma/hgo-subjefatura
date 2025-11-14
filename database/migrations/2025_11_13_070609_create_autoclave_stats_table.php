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
        Schema::create('autoclave_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained()->cascadeOnDelete();

            // Total de cargas por servicio
            $table->integer('ceye')->nullable();
            $table->integer('subceye')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autoclave_stats');
    }
};
