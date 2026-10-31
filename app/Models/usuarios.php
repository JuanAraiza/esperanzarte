<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuarios extends Model
{
    //
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'tipo',
        'clave',
        'deshabilitado',
    ];
}
