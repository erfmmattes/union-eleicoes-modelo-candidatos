<?php

namespace App\Repositories\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Verifica se a senha está correta para o usuário
     *
     * @param User $user
     * @param string $senha
     * @return bool
     */
    public function verificarSenha(User $user, string $senha): bool
    {
        return Hash::check($senha, $user->password);
    }
}