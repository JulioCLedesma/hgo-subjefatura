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
        Schema::create('toco_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_shift_id')->constrained()->cascadeOnDelete();

            // Todos los campos numéricos, opcionales
            $table->integer('partos')->nullable();
            $table->integer('cesareas')->nullable();
            $table->integer('rn_vivos')->nullable();
            $table->integer('piel_a_piel')->nullable();
            $table->integer('obitos')->nullable();
            $table->integer('legrados')->nullable();
            $table->integer('otb')->nullable();
            $table->integer('rev_cavidad')->nullable();
            $table->integer('histerectomia')->nullable();
            $table->integer('plastias')->nullable();
            $table->integer('analgesias')->nullable();
            $table->integer('emergencia_obstetrica')->nullable();
            $table->integer('consulta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toco_stats');
    }
};
