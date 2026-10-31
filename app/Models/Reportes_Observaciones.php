<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportes_Observaciones extends Model
{
    //
    protected $table = 'reportes__observaciones';
    protected $fillable = [
        'docente',
        'fecha',
        'hora',
        'fecha_ini',
        'fecha_fin'
    ];
}
