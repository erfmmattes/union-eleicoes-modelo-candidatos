<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;

class HomeRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }
}