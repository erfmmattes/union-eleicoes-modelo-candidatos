<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TipoUsuario extends Model
{
    protected $table = 'tipos_usuarios';

    protected $fillable = [
        'nome',
        'slug',
        'ativo',
    ];
}