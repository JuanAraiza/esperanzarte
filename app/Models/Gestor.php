<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestor extends Model
{
    //
    protected $table = 'gestors';

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
