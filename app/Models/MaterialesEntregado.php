<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialesEntregado extends Model
{
    //
    protected $table = 'materiales_entregados';
    protected $fillable = [
        'entrega_material',
        'material',
        'cantidad',
    ];
}
