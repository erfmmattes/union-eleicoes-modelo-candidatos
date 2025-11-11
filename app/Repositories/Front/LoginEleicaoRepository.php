<?php

namespace App\Repositories\Front;

use App\Models\Configuracao;
use App\Models\Eleitor;
use App\Models\EleitorLogado;

class LoginEleicaoRepository
{
    public function buscarDados()
    {
        $configuracao = Configuracao::find(1);

        return [
            'configuracoes' => $configuracao
        ];
    }

    public function buscarPorCpf(string $cpf): ?Eleitor
    {
        $cpf = preg_replace('/\D/', '', $cpf); 
        return Eleitor::where('cpf_cnpj', $cpf)->first();
    }

    public function logaEleitorTabela(string $eleitorId): ?EleitorLogado
    {
        return EleitorLogado::create([
            'eleitor_id' => $eleitorId,
            'ip' => request()->ip(),
        ]);
    }

    public function buscarPorId(int $id): ?Eleitor
    {
        return Eleitor::find($id);
    }

    public function updateTermosDeUso(array $parametros): ?Eleitor
    {
        $eleitor = Eleitor::find($parametros['eleitor_id']);

        if (!$eleitor) {
            return null;
        }

        $eleitor->update([
            'aceitou_os_termos' => true,
        ]);

        return $eleitor;
    }

    public function getEleitorById($id)
    {
        $eleitor = Eleitor::where('id', $id)->first();
        return $eleitor;
    }

    public function atualizarSenhaAposLogin(int $id, string $senha): bool
    {
        $eleitor = $this->buscarPorId($id);
        if (!$eleitor) {
            return false;
        }

        $eleitor->senha = $senha;
        $eleitor->quantidade_troca_senha = $eleitor->quantidade_troca_senha + 1;
        return $eleitor->save();
    }

    public function atualizarStatusTrocaSenhaPosLogin($eleitorId)
    {
        $eleitor = Eleitor::find($eleitorId);

        if (!$eleitor) {
            return null;
        }

        $eleitor->update([
            'senha_trocada_depois_do_login' => true,
        ]);

        return $eleitor;
    }

    public function removeEleitorTabela(string $eleitorId): void
    {
        EleitorLogado::where('eleitor_id', $eleitorId)->delete();
    }
}