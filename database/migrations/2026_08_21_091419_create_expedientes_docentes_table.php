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
        Schema::create('expedientes_docentes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('docente')->nullable();
            $table->string('titulo')->nullable();
            $table->string('archivo')->nullable();
            $table->integer('deshabilitado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes_docentes');
    }
};
