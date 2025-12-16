<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Comprovante;
use App\Models\Eleitor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ComprovanteRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function listaComprovante()
    {
        $eleitorId = Session::get('eleitor_id');

        return DB::table('comprovantes')
            ->join('etapas_candidatos', 'etapas_candidatos.id', '=', 'comprovantes.etapa_id')
            ->select(
                'comprovantes.id as comprovante_id',
                'comprovantes.chave_autenticacao',
                'comprovantes.nome_eleitor',
                'comprovantes.nome_votacao',
                'comprovantes.cpf_cnpj',
                'comprovantes.ip',
                'comprovantes.data_hora',

                'etapas_candidatos.id as etapa_id',
                'etapas_candidatos.nome as etapa_nome',
                'etapas_candidatos.sequencia',
                'etapas_candidatos.multipla_escolha',
                'etapas_candidatos.quantidade_minima_escolhas',
                'etapas_candidatos.quantidade_maxima_escolhas',
                'etapas_candidatos.status'
            )
            ->where('comprovantes.eleitor_id', $eleitorId)
            ->orderBy('etapas_candidatos.sequencia')
            ->get();
    }

    public function listaDadosEleitor()
    {
        $eleitorId = Session::get('eleitor_id');
        return Eleitor::find($eleitorId);
    }

    public function getComprovanteById()
    {
        $eleitorId = Session::get('eleitor_id');

        return DB::table('comprovantes')
            ->join('etapas_candidatos', 'etapas_candidatos.id', '=', 'comprovantes.etapa_id')
            ->select(
                'comprovantes.id as comprovante_id',
                'comprovantes.chave_autenticacao',
                'comprovantes.nome_eleitor',
                'comprovantes.nome_votacao',
                'comprovantes.cpf_cnpj',
                'comprovantes.ip',
                'comprovantes.data_hora',

                'etapas_candidatos.id as etapa_id',
                'etapas_candidatos.nome as etapa_nome',
                'etapas_candidatos.sequencia',
                'etapas_candidatos.multipla_escolha',
                'etapas_candidatos.quantidade_minima_escolhas',
                'etapas_candidatos.quantidade_maxima_escolhas',
                'etapas_candidatos.status'
            )
            ->where('comprovantes.eleitor_id', $eleitorId)
            ->orderBy('etapas_candidatos.sequencia')
            ->get();
    }
}