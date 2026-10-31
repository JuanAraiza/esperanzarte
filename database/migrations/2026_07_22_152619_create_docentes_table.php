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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->nullable();
            $table->string('nombre')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('rfc')->nullable();
            $table->string('regimen')->nullable();
            $table->string('curp')->nullable();
            $table->integer('estatus')->default(1);
            $table->integer('deshabilitado')->default(0);
            $table->string('clave')->nullable();
            $table->string('foto')->nullable();
            $table->string('clabe')->nullable();
            $table->string('banco')->nullable();
            $table->text('direccion_fiscal')->nullable();
            $table->text('folio_ine')->nullable();
            $table->text('taller')->nullable();
            $table->date('inicio_c')->nullable();
            $table->date('termino_c')->nullable();
            $table->text('horas_t')->nullable();
            $table->text('genero')->nullable();
            $table->text('dias_asueto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
