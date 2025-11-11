<?php

namespace App\Repositories\Front;

use App\Models\Ajuda;
use App\Models\Configuracao;

class AjudaRepository
{
    public function obterConfiguracoes()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function findById(int $id): ?Ajuda
    {
        return Ajuda::find($id);
    }

    public function getAll()
    {
        return Ajuda::where('ativo', '=', '1')->orderBy('sequencia')->get();
    }
}