<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleitor extends Model
{
    use HasFactory;

    protected $table = 'eleitores';

    protected $fillable = [
        'nome',
        'razao_social',
        'campo_opcional',
        'celular',
        'setor',
        'peso_do_voto',
        'email',
        'cpf_cnpj',
        'senha',
        'data_nascimento',
        'votou',
        'status',
        'enviou_senha_email',
        'enviou_senha_sms',
        'passou_por_ajuste',
        'aceitou_os_termos',
        'nome_do_representante',
        'ip',
        'session_token_front',
        'quantidade_recuperacao_senha',
        'quantidade_troca_senha',
        'senha_trocada_depois_do_login',
    ];

    protected $casts = [
        'votou' => 'boolean',
        'data_nascimento' => 'date',
    ];

    public function votos()
    {
        return $this->hasMany(Voto::class, 'eleitor_id');
    }

    public function camadas()
    {
        return $this->hasMany(ListaChamadaEleitor::class, 'eleitor_id');
    }
}