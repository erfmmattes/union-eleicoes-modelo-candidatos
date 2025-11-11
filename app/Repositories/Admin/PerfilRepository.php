<?php

namespace App\Repositories\Admin;

use App\Models\User;

class PerfilRepository
{
    public function buscarPorId(int $id): ?User
    {
        return User::find($id);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return false;
        }

        $usuario->update([
            'name' => $dados['name'] ?? $usuario->name,
            'email' => $dados['email'] ?? $usuario->email,
        ]);

        return true;
    }
}