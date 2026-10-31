<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alumno extends Model
{
    //
    protected $table = 'alumnos';
    protected $fillable = [
        'folio',
        'nombre',
        'paterno',
        'materno',
        'curp',
        'clave_electoral',
        'numero_emision',
        'vigencia',
        'fecha_nacimiento',
        'sexo',
        'domicilio',
        'num_ext',
        'num_int',
        'colonia',
        'localidad',
        'municipio',
        'estado',
        'cod_postal',
        'seccion',
        'telefono',
        'correo',
        'clave',
        'foto',
        'inef',
        'inea',
        'estatus',
        'deshabilitado',
        'id_user',
        'txt_regreso',
        'ur',
        'edad',
    ];
}
