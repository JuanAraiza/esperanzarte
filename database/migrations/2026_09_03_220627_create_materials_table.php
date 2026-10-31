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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->text('material')->nullable();
            $table->text('foto')->nullable();
            $table->bigInteger('stock')->nullable();
            $table->bigInteger('stock_minimo')->nullable();
            $table->bigInteger('stock_maximo')->nullable(); 
            $table->text('detalles')->nullable();
            $table->text('marca')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('unidad_medida')->nullable();
            $table->text('detalles_producto')->nullable();
            $table->integer('deshabilitado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
