<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class centros_para_subir extends Model
{
    //
    protected $table = 'centros_para_subirs';
    protected $fillable = [
        'no',
        'nombre',
        'zona',
        'tipo_territorio',
        'territorio',
        'direccion',
        'google_maps',
        'responsable',
        'telefono',
        'supervisor',
        'telefono2',
        'estatus',
    ];
}
