<?php

namespace App\Repositories\Admin;

use App\Models\Configuracao;
use App\Models\Eleitor;

class DeclaracaoEleicaoRepository
{
    public function obterDadosDeclaracao(): array
    {
        $config = Configuracao::first();
        $eleicao = Eleitor::all();

        return [
            'razao_social' => $config->razao_social ?? 'Não definido',
            'cnpj' => $config->cnpj ?? 'Não informado',
            'nome_eleicao' => $config->nome_eleicao ?? 'Não informado',
            'data_hora_inicio_eleicao' => $config->data_hora_inicio_eleicao ?? 'Não informado',
            'data_hora_final_eleicao' => $config->data_hora_final_eleicao ?? 'Não informado',
            'caminho' => $config->caminho ?? 'Não definido',
            'total_votantes' => Eleitor::where('votou', 1)->count(),
            'data_geracao' => now()->format('d/m/Y H:i'),
        ];
    }
}