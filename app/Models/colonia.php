<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class colonia extends Model
{
    //
    protected $table = 'colonias';
    protected $fillable = [
        'nombre',
        'seccion',
        'deshabilitado',
    ];
}
