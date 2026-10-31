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
        Schema::create('centros', function (Blueprint $table) {
            $table->id();
            $table->string('id_centro')->nullable();
            $table->string('centro')->nullable();
            $table->string('zona')->nullable();
            $table->string('tipo_territorio')->nullable();
            $table->string('territorio')->nullable();
            $table->string('direccion')->nullable();
            $table->string('google_maps')->nullable();
            $table->string('enlace')->nullable();
            $table->string('telefono')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('telefono_1')->nullable();
            $table->string('estatus')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('deshabilitado')->nullable();    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros');
    }
};
