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
        Schema::create('ward_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->integer('pacientes')->nullable();
            $table->integer('caidas')->nullable();
            $table->integer('tiras')->nullable();
            $table->integer('graves')->nullable();
            $table->integer('tubos')->nullable();
            $table->timestamps();

            $table->unique(['daily_shift_id', 'service_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_stats');
    }
};
