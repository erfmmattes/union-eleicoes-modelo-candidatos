<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = [
        'nome_eleicao',
        'razao_social',
        'cnpj',
        'suporte_0800',
        'numero_suporte_0800',
        'suporte_whatsapp',
        'numero_suporte_whatsapp',
        'data_hora_inicio_eleicao',
        'data_hora_final_eleicao',
        'remetente_do_email',
        'assunto_do_email',
        'mensagem_eleitor_email',
        'mensagem_eleitor_sms',
        'menu_ajuda',
        'menu_documentos',
        'menu_trocar_senha',
        'menu_recuperar_senha',
        'autenticacao_de_2_etapas',
        'trocar_de_senha_depois_login',
        'dados_da_comissao',
        'cor_principal',
        'cor_hover',
        'logotipo',
        'caminho',
        'termos',
        'nome_presidente',
        'cpf_presidente',
        'email_presidente',
        'celular_presidente',
        'nome_mebro_1',
        'cpf_mebro_1',
        'email_mebro_1',
        'celular_mebro_1',
        'nome_mebro_2',
        'cpf_mebro_2',
        'email_mebro_2',
        'celular_mebro_2',
    ];
}