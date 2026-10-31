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
        Schema::create('reportes__observaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('docente');
            $table->date('fecha');
            $table->time('hora');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes__observaciones');
    }
};
