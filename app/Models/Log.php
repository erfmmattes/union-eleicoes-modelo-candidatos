<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'nome_log',
        'mensagem',
    ];
}