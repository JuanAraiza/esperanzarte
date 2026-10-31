<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class actualizarMaterial extends Model
{
    //
    protected $table = 'actualizar_materials';
    
    protected $fillable = [
        'material',
        'stockviejo',
        'agregar',
        'stocknuevo',
        'fecha',
        'usuario',
    ];
    
}
