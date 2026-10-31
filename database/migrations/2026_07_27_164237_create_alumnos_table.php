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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->nullable();
            $table->string('nombre')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('curp')->nullable();
            $table->string('clave_electoral')->nullable();
            $table->string('numero_emision')->nullable();
            $table->string('vigencia')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('num_ext')->nullable();
            $table->string('num_int')->nullable();
            $table->string('colonia')->nullable();
            $table->string('localidad')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();
            $table->string('cod_postal')->nullable();
            $table->string('seccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('clave')->nullable();
            $table->string('foto')->nullable();
            $table->string('inef')->nullable();
            $table->string('inea')->nullable();
            $table->integer('estatus')->default(1);
            $table->integer('deshabilitado')->default(0);
            $table->integer('id_user')->nullable();
            $table->text('txt_regreso')->nullable();
            $table->string('ur')->nullable();
            $table->integer('edad')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
