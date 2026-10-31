<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class obs_observaciones_docentes extends Model
{
    //
    protected $table = 'obs_observaciones_docentes';
    protected $fillable = [
        'docente_id',
        'reporte',
        'taller',
        'centro',
        'total_horas',
        'observaciones',
        'fecha_ini',
        'fecha_fin',
    ];
}
