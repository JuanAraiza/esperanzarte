<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class localidad extends Model
{
    //
    protected $table = 'localidads';
    protected $fillable = [
        'nombre',
        'seccion',
        'deshabilitado',
    ];
}
