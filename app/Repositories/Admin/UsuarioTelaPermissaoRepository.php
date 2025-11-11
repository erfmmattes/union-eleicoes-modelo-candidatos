<?php

namespace App\Repositories\Admin;

use App\Models\UsuarioTelaPermissao;

class UsuarioTelaPermissaoRepository
{
    public function updateOrCreate(int $usuarioId, string $telaSlug, array $dados): UsuarioTelaPermissao
    {
        return UsuarioTelaPermissao::updateOrCreate(
            [
                'usuario_id' => $usuarioId,
                'tela_slug' => $telaSlug,
            ],
            $dados
        );
    }

    public function getPermissoesPorUsuario(int $usuarioId)
    {
        return UsuarioTelaPermissao::where('usuario_id', $usuarioId)->get()->keyBy('tela_slug');
    }

    public function getPermissaoPorTela(int $usuarioId, string $telaSlug): ?UsuarioTelaPermissao
    {
        return UsuarioTelaPermissao::where('usuario_id', $usuarioId)
                                   ->where('tela_slug', $telaSlug)
                                   ->first();
    }

    public function deletePermissoesPorUsuario(int $usuarioId): int
    {
        return UsuarioTelaPermissao::where('usuario_id', $usuarioId)->delete();
    }

    public function buscarPorUsuario(int $usuarioId)
    {
        return UsuarioTelaPermissao::where('usuario_id', $usuarioId)->get();
    }

    public function atualizarPermissoes(int $usuarioId, array $permissoes)
    {
        foreach ($permissoes as $telaSlug => $acoes) {
            $this->updateOrCreate(
                $usuarioId,
                $telaSlug,
                [
                    'criar' => in_array('criar', $acoes),
                    'importar_eleitores' => in_array('importar_eleitores', $acoes),
                    'enviar_senha' => in_array('enviar_senha', $acoes),
                    'ver' => in_array('ver', $acoes),
                    'editar' => in_array('editar', $acoes),
                    'deletar' => in_array('deletar', $acoes),
                ]
            );
        }

        UsuarioTelaPermissao::where('usuario_id', $usuarioId)
            ->whereNotIn('tela_slug', array_keys($permissoes))
            ->delete();
    }
}