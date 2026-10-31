<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clase_horarios extends Model
{
    //
    protected $table = 'clase_horarios';
    protected $fillable = [
        'clase_asignada',
        'dia',
        'hora_inicio',
        'hora_fin',
        'total_horas',
    ];
}
