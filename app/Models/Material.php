<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    protected $table = 'materials';
    protected $fillable = [
        'material',
        'foto',
        'stock',
        'stock_minimo',
        'stock_maximo',
        'detalles',
        'marca',
        'costo',
        'unidad_medida',
        'detalles_producto',
        'deshabilitado',
    ];

}
