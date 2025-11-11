<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Documento;

class DocumentosRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function getAll()
    {
        return Documento::where('ativo', '=', '1')->orderBy('sequencia')->get();
    }

    public function findById(int $id)
    {
        return Documento::find($id);
    }
}