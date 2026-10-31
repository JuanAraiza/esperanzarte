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
        Schema::create('entrega_materials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('docente')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->bigInteger('estatus')->nullable();
            $table->text('folio')->nullable();
            $table->text('observaciones')->nullable();
            $table->integer('deshabilitado')->default(0);
            $table->text('evidencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_materials');
    }
};
