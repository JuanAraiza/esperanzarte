<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    //
    protected $table = 'supervisors';
    
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
        'zona',
        'foto',
        'estatus',
        'clave',
        'deshabilitado',
        'usuario',
        'password'
    ];
    
}
