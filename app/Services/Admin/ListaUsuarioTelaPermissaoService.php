<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ListaUsuarioTelaPermissaoRepository;
use Illuminate\Support\Facades\Auth;

class ListaUsuarioTelaPermissaoService
{
    protected ListaUsuarioTelaPermissaoRepository $permissaoRepository;

    public function __construct(ListaUsuarioTelaPermissaoRepository $permissaoRepository)
    {
        $this->permissaoRepository = $permissaoRepository;
    }

    /**
     * Verifica se o usuário logado tem permissão em uma determinada tela.
     *
     * @param  string  $telaSlug
     * @param  string|null  $acao
     * @return bool
     */
    public function verificarPermissao(string $telaSlug, ?string $acao = null): bool
    {
        $usuarioId = Auth::id();

        if (!$usuarioId) {
            return false;
        }

        return $this->permissaoRepository->verificarPermissao($telaSlug, $acao, $usuarioId);
    }

    /**
     * Retorna todas as permissões do usuário logado, agrupadas pelo slug da tela.
     *
     * @return \Illuminate\Support\Collection
     */
    public function listarPermissoesUsuario()
    {
        $usuarioId = Auth::id();

        if (!$usuarioId) {
            return collect();
        }

        return $this->permissaoRepository->getPermissoesPorUsuario($usuarioId);
    }

    public function getTodasPermissoes(): array
    {
        $usuarioId = auth()->id();

        $permissoes = $this->permissaoRepository->getPermissoesPorUsuario($usuarioId);

        // Transforma para um array simples: ['tela_slug' => ['ver'=>true, 'criar'=>false, ...]]
        $resultado = [];
        foreach ($permissoes as $tela => $permissao) {
            $resultado[$tela] = [
                'ver' => $permissao->ver,
                'importar_eleitores' => $permissao->importar_eleitores,
                'enviar_senha' => $permissao->enviar_senha,
                'criar' => $permissao->criar,
                'editar' => $permissao->editar,
                'deletar' => $permissao->deletar,
            ];
        }

        return $resultado;
    }
}