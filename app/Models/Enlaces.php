<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enlaces extends Model
{
    //
    protected $table = 'enlaces';
    protected $fillable = [
        'folio',
        'nombre',
        'apaterno',
        'amaterno',
        'centro',
        'correo',
        'telefono',
        'regimen',
        'curp',
        'rfc',
        'foto',
        'estatus',
        'clave',
        'deshabilitado',
        'usuario',
        'password'
    ];
}
