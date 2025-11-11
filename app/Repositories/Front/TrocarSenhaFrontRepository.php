<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Eleitor;
use Illuminate\Support\Facades\Hash;

class TrocarSenhaFrontRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function buscarPorId(int $id): ?Eleitor
    {
        return Eleitor::find($id);
    }

    public function atualizarSenha(int $id, string $senha): bool
    {
        $eleitor = $this->buscarPorId($id);
        if (!$eleitor) {
            return false;
        }

        $eleitor->senha = $senha;
        $eleitor->quantidade_troca_senha = $eleitor->quantidade_troca_senha + 1;
        return $eleitor->save();
    }
}