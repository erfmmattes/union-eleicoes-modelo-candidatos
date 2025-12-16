<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarComprovanteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $comprovante;

    public function __construct($comprovante)
    {
        $this->comprovante = $comprovante;
    }

    public function build()
    {
        $assunto = 'Unir Votações - Comprovante de Votação';
        $remetenteEmail = 'no-reply@unirvotacoes.com.br';
        $remetenteNome = 'Unir Votações';

        return $this->from($remetenteEmail, $remetenteNome)
                    ->subject($assunto)
                    ->view('emails.comprovante')
                    ->with([
                        'listaComprovantes' => $this->comprovante
                    ]);
    }
}