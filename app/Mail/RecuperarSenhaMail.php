<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarSenhaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $novaSenha;
    public $nome;

    public function __construct($novaSenha, $nome)
    {
        $this->novaSenha = $novaSenha;
        $this->nome = $nome;
    }

    public function build()
    {
        return $this->subject('Unir Votações - Recuperação de Senha')
                    ->view('emails.recuperarSenha');
    }
}