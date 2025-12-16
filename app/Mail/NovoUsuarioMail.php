<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NovoUsuarioMail extends Mailable
{
    use SerializesModels;

    public User $usuario;
    public string $senha;

    public function __construct(User $usuario, string $senha)
    {
        $this->usuario = $usuario;
        $this->senha = $senha;
    }

    public function build()
    {
        return $this->subject('Unir Votações - Acesso ao Sistema Admin')
            ->view('emails.novoUsuario')
            ->with([
                'usuario' => $this->usuario,
                'senha' => $this->senha,
            ]);
    }
}