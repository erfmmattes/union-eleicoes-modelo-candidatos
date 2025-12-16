<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprovante extends Model
{
    use HasFactory;

    protected $table = 'comprovantes';

    protected $fillable = [
        'eleitor_id',
        'etapa_id',
        'nome_eleitor',
        'nome_votacao',
        'cpf_cnpj',
        'ip',
        'data_hora',
        'chave_autenticacao',
    ];
}