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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('alumno_id')->unsigned();
            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->bigInteger('docente_id')->unsigned();
            $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');
            $table->bigInteger('clase_asig_id')->unsigned();
            $table->foreign('clase_asig_id')->references('id')->on('clase_asignadas')->onDelete('cascade');
            $table->date('dia')->nullable();
            $table->time('hora')->nullable();
            $table->bigInteger('horario')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
