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
        Schema::create('obs_observaciones_docentes', function (Blueprint $table) {
            $table->id();
            $table->integer('docente_id');
            $table->text('reporte')->nullable();
            $table->integer('taller')->nullable();
            $table->integer('centro')->nullable();
            $table->integer('total_horas')->nullable();
            $table->text('observaciones')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obs_observaciones_docentes');
    }
};
