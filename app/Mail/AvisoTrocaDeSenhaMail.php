<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvisoTrocaDeSenhaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nome;

    public function __construct($nome)
    {
        $this->nome = $nome;
    }

    public function build()
    {
        return $this->subject('Union Eleições - Aviso de Troca de Senha')
                    ->view('emails.avisoTrocaDeSenha');
    }
}