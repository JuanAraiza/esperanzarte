<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clase_asignada extends Model
{
    //
    protected $table = 'clase_asignadas';
    protected $fillable = [
        'docente',
        'centro',
        'taller',
        'costo',
        'deshabilitado',
    ];
}
