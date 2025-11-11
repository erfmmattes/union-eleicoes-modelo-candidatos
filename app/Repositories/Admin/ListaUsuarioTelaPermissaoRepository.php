<?php

namespace App\Repositories\Admin;

use App\Models\UsuarioTelaPermissao;
use Illuminate\Support\Facades\Auth;

class ListaUsuarioTelaPermissaoRepository
{
    protected UsuarioTelaPermissao $model;

    public function __construct(UsuarioTelaPermissao $model)
    {
        $this->model = $model;
    }

    /**
     * Verifica se o usuário autenticado possui permissão em uma tela específica.
     *
     * @param  string  $telaSlug
     * @param  string|null  $acao  (ex: 'ver', 'criar', 'editar', 'excluir')
     * @param  int|null  $usuarioId  (opcional, usa o logado se não informado)
     * @return bool
     */
    public function verificarPermissao(string $telaSlug, ?string $acao = null, ?int $usuarioId = null): bool
    {
        $usuarioId = $usuarioId ?? Auth::id();

        if (!$usuarioId) {
            return false;
        }

        $permissao = $this->model
            ->where('usuario_id', $usuarioId)
            ->where('tela_slug', $telaSlug)
            ->first();

        if (!$permissao) {
            return false;
        }

        if (!$acao) {
            return true;
        }

        return (bool) $permissao->{$acao};
    }

    public function getPermissoesPorUsuario(int $usuarioId)
    {
        return $this->model
            ->where('usuario_id', $usuarioId)
            ->get()
            ->keyBy('tela_slug');
    }
}