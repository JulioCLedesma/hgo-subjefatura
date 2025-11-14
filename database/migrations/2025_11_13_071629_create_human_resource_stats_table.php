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
        Schema::create('human_resource_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained()->cascadeOnDelete();

            // ASISTENCIA
            $table->integer('subjefatura')->nullable();
            $table->integer('supervision')->nullable();
            $table->integer('jefes_servicio')->nullable();
            $table->integer('enfermeria_general')->nullable();
            $table->integer('enfermeria_auxiliar')->nullable();
            $table->integer('pasantes')->nullable();

            // INCIDENCIAS
            $table->integer('descansos_obligatorios')->nullable();
            $table->integer('incapacidades')->nullable();
            $table->integer('faltas')->nullable();
            $table->integer('vacaciones')->nullable();
            $table->integer('becas')->nullable();
            $table->integer('permisos_sindicales')->nullable();
            $table->integer('permiso_tiempo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_resource_stats');
    }
};
