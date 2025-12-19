<?php

namespace App\Repositories\Admin;

use App\Models\EscolhaCandidato;
use App\Models\EtapaCandidato;
use App\Models\Voto;
use App\Models\Eleitor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class ApuracaoRepository
{
    public function etapaResgatada(int $etapaId)
    {
        $etapa = EtapaCandidato::find($etapaId);
        return $etapa;
    }

    public function apuracaoVotosPorEtapaId(int $etapaId): Collection
    {
        $votos = Voto::all();

        $etapa = EtapaCandidato::findOrFail($etapaId);

        $candidatos = EscolhaCandidato::where('etapas_candidatos_id', $etapaId)
            ->orderBy('sequencia', 'asc')
            ->get();

        $seqEtapa = 'etapa_' . $etapa->sequencia;

        $totalVotosEtapa = $votos->filter(function ($voto) use ($seqEtapa) {
            return $voto->etapa === $seqEtapa;
        })->count();

        return $candidatos->map(function ($candidato) use ($votos, $seqEtapa, $totalVotosEtapa) {

            $quantidade = $votos->filter(function ($voto) use ($candidato, $seqEtapa) {
                return $voto->etapa === $seqEtapa
                    && $voto->votouEm($candidato);
            })->count();

            $percentual = $totalVotosEtapa > 0
                ? round(($quantidade / $totalVotosEtapa) * 100, 2)
                : 0;

            return [
                'candidato'  => $candidato->nome,
                'quantidade' => $quantidade,
                'percentual' => $percentual,
            ];
        })
        ->sortByDesc('quantidade')
        ->values();
    }

    public function listarEleitoresEVotantesParaGerarPdf($etapa)
    {
        return Eleitor::
            select('eleitores.*', 'votos.eleitor_id', 'votos.votado_em', 'votos.etapa')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('votos.etapa', '=', $etapa)
            ->orderBy('votos.votado_em', 'desc')->get();
    }
}