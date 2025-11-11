<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Models\TipoUsuario;
use App\Models\Tela;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository
{
    public function listarTodosExceto(int $usuarioLogadoId)
    {
        return User::where('id', '!=', $usuarioLogadoId)->orderBy('id', 'desc')->paginate(10);
    }

    public function buscarPorId(int $id): ?User
    {
        return User::find($id);
    }

    public function criar(array $dados): User
    {
        $dados['password'] = Hash::make($dados['password']);
        return User::create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $usuario = User::findOrFail($id);

        if (!empty($dados['password'])) {
            $dados['password'] = Hash::make($dados['password']);
        } else {
            unset($dados['password']);
        }

        return $usuario->update($dados);
    }

    public function excluir(int $id): bool
    {
        $usuario = User::findOrFail($id);
        return $usuario->delete();
    }

    public function listarTodosTiposDeUsuarioExcetoAdmin()
    {
        return TipoUsuario::where('slug', '!=', 'admin-master')->where('ativo', '=', 1)->get();
    }

    public function listarTodasAsTelas()
    {
        return Tela::where('ativo', '=', 1)->get();
    }

    public function statusAtualizar(int $id, array $dados): bool
    {
        $usuario = User::findOrFail($id);
        return $usuario->update($dados);
    }
}