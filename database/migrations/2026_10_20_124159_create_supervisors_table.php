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
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->text('folio')->nullable();
            $table->text('nombre')->nullable();
            $table->text('apaterno')->nullable();
            $table->text('amaterno')->nullable();
            $table->text('centro')->nullable();
            $table->text('correo')->nullable();
            $table->text('telefono')->nullable();  
            $table->text('regimen')->nullable();
            $table->text('curp')->nullable();
            $table->text('rfc')->nullable();
            $table->text('zona')->nullable();
            $table->text('foto')->nullable();
            $table->text('estatus')->nullable();
            $table->text('clave')->nullable();
            $table->integer('deshabilitado')->default(0)->nullable();
            $table->text('usuario')->nullable();
            $table->text('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
