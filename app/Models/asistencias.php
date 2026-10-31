<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asistencias extends Model
{
    //
    protected $table = 'asistencias';
    protected $fillable = [
        'alumno_id',
        'docente_id',
        'clase_asig_id',
        'dia',
        'hora',
        'horario',
        'latitud',
        'longitud',
    ];
}
