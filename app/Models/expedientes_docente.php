<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class expedientes_docente extends Model
{
    //
    protected $table = 'expedientes_docentes';

    protected $fillable = [
        'docente',
        'titulo',
        'archivo',
        'deshabilitado'
    ];
}
