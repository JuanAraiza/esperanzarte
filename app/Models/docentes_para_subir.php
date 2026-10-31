<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class docentes_para_subir extends Model
{
    //
    protected $table = 'docentes_para_subirs';
    protected $fillable = [
        'no',
        'nombre',
        'apellidos',
        'area',
        'taller',
        'centro',
        'fecha_inicio',
        'horario',
        'correo',
        'telefono',
        'curp',
        'rfc',
        'regimen',
        'direccion_fiscal',
        'folio_ine',
        'taller',
        'inicio_c',
        'termino_c',
        'horas_t',
        'genero',
    ];
}
