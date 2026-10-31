<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class docente extends Model
{
    //
    protected $table = 'docentes';
    protected $fillable = [
        'folio',
        'nombre',
        'paterno',
        'materno',
        'correo',
        'telefono',
        'rfc',
        'regimen',
        'curp',
        'estatus',
        'deshabilitado',
        'clave',
        'foto',
        'clabe',
        'banco',
        'direccion_fiscal',
        'folio_ine',
        'taller',
        'inicio_c',
        'termino_c',
        'horas_t',
        'genero',
        'dias_asueto',

    ];
}
