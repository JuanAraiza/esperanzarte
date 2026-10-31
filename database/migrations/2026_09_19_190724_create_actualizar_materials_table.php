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
        Schema::create('actualizar_materials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('material');
            $table->integer('stockviejo');
            $table->integer('agregar');
            $table->integer('stocknuevo');
            $table->date('fecha');
            $table->bigInteger('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actualizar_materials');
    }
};
