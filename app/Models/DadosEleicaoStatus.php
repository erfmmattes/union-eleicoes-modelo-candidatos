<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DadosEleicaoStatus extends Model
{
    use HasFactory;

    protected $table = 'dados_eleicao_status';

    protected $fillable = [
        'total_eleitores',
        'senhas_geradas',
        'emails_enviados',
        'telefones',
        'sms_enviados',
    ];
}