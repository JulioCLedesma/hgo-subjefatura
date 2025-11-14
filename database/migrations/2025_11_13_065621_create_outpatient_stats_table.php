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
        Schema::create('outpatient_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained()->cascadeOnDelete();

            // Clínica de catéteres
            $table->integer('cat_medial')->nullable();
            $table->integer('cat_picc')->nullable();
            $table->integer('cat_umbilical')->nullable();
            $table->integer('cat_asepsia')->nullable();
            $table->integer('cat_periferico_corto')->nullable();
            $table->integer('cat_cvc')->nullable();

            // Heridas
            $table->integer('her_curaciones')->nullable();
            $table->integer('her_interconsultas')->nullable();
            $table->integer('her_valoraciones')->nullable();
            $table->integer('her_cuidados_especiales')->nullable();
            $table->integer('her_vac')->nullable();

            // Lactancia
            $table->integer('lac_asesorias')->nullable();
            $table->integer('lac_autoclaves')->nullable();
            $table->integer('lac_fracciones')->nullable();

            // Endoscopías
            $table->integer('end_endoscopias')->nullable();
            $table->integer('end_colonoscopias')->nullable();
            $table->integer('end_biopsias')->nullable();
            $table->integer('end_cepres')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outpatient_stats');
    }
};
