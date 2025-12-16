<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarSenhaEleitorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eleitor;
    public $senha;
    public $configuracao;

    public function __construct($eleitor, $senha, $configuracao = null)
    {
        $this->eleitor = $eleitor;
        $this->senha = $senha;
        $this->configuracao = $configuracao;
    }

    public function build()
    {
        $assunto = 'Unir Votações - Sua senha de acesso ao sistema de eleição';
        $remetenteEmail = 'no-reply@unirvotacoes.com.br';
        $remetenteNome = 'Unir Votações';

        return $this->from($remetenteEmail, $remetenteNome)
                    ->subject($assunto)
                    ->view('emails.enviarSenhaIndividual')
                    ->with([
                        'eleitor' => $this->eleitor,
                        'senha' => $this->senha,
                        'configuracao' => $this->configuracao,
                    ]);
    }
}