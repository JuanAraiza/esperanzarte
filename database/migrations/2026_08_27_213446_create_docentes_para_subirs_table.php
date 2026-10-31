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
        Schema::create('docentes_para_subirs', function (Blueprint $table) {
            $table->id();
            $table->text('no')->nullable();
            $table->text('nombre')->nullable();
            $table->text('apellidos')->nullable();
            $table->text('area')->nullable();
            $table->text('taller')->nullable();
            $table->text('centro')->nullable();
            $table->text('fecha_inicio')->nullable();
            $table->text('horario')->nullable();
            $table->text('correo')->nullable();
            $table->text('telefono')->nullable();
            $table->text('curp')->nullable();
            $table->text('rfc')->nullable();
            $table->text('regimen')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes_para_subirs');
    }
};
