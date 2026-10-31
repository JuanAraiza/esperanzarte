<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class centro extends Model
{
    //
    protected $table = 'centros';
    protected $fillable = [
        'id_centro',
        'centro',
        'zona',
        'tipo_territorio',
        'territorio',
        'direccion',
        'google_maps',
        'enlace',
        'telefono',
        'supervisor',
        'telefono_1',
        'estatus',
        'latitud',
        'longitud',
        'deshabilitado',
    ];
}
