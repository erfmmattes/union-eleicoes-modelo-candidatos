<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Eleitor;
use Illuminate\Support\Facades\Hash;

class RecuperarSenhaRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function buscarPorCpf($cpf)
    {
        return Eleitor::where('cpf_cnpj', $cpf)->first();
    }

    public function buscarPorId($id)
    {
        return Eleitor::find($id);
    }

    public function atualizarSenha($eleitor, $novaSenha)
    {
        $eleitor->senha = Hash::make($novaSenha);
        $eleitor->quantidade_recuperacao_senha = $eleitor->quantidade_recuperacao_senha + 1;
        $eleitor->save();
    }
}