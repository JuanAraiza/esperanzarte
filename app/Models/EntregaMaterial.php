<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaMaterial extends Model
{
    //
    protected $table = 'entrega_materials';
    protected $fillable = [
        'docente',
        'fecha',
        'hora',
        'estatus',
        'folio',
        'observaciones',
        'deshabilitado',
        'evidencia',
    ];
}
