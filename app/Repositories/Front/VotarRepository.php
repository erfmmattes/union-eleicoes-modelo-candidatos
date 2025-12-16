<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Comprovante;
use App\Models\EtapaCandidato;
use App\Models\Eleitor;
use App\Models\Voto;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class VotarRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function listaEtapasAtivas()
    {
        $eleitorId = Session::get('eleitor_id');
        $listaEleitor = Eleitor::find($eleitorId);

        $etapas = EtapaCandidato::with(['escolhas' => function ($q) {
            $q->where('status', 1)->orderBy('sequencia', 'asc');
        }])
        ->where('status', 1)
        ->where('setores_id', $listaEleitor->setor)
        ->orderBy('sequencia', 'asc')
        ->get();

        return $etapas;
    }

    public function create(array $data): Voto
    {
        return Voto::create($data);
    }

    public function eleitorJaVotouEtapa(int $eleitorId, string $etapa): bool
    {
        return Voto::where('eleitor_id', $eleitorId)
            ->where('etapa', $etapa)
            ->exists();
    }

    public function buscarProximaEtapa(int $etapaAtualSequencia)
    {
        return EtapaCandidato::where('status', 1)
            ->where('sequencia', '>', $etapaAtualSequencia)
            ->orderBy('sequencia', 'asc')
            ->first();
    }

    public function dadosEleitorLogado()
    {
        $eleitorId = Session::get('eleitor_id');
        return Eleitor::find($eleitorId);
    }

    public function finalizarVotacao(array $dados)
    {
        return DB::select("
            CALL finalizar_votacao(?, ?, ?, ?, ?, ?)
        ", [
            $dados['eleitor_id'],
            $dados['etapa_id'],
            $dados['nome_votacao'],
            $dados['nome_eleitor'],
            $dados['cpf_cnpj'],
            $dados['ip']
        ])[0];
    }
}