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
        Schema::create('centros_para_subirs', function (Blueprint $table) {
            $table->id();
            $table->text('no')->nullable();
            $table->text('nombre')->nullable();
            $table->text('zona')->nullable();
            $table->text('tipo_territorio')->nullable();
            $table->text('territorio')->nullable();
            $table->text('direccion')->nullable();
            $table->text('google_maps')->nullable();
            $table->text('responsable')->nullable();
            $table->text('telefono')->nullable();
            $table->text('supervisor')->nullable();
            $table->text('telefono2')->nullable();
            $table->text('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros_para_subirs');
    }
};
