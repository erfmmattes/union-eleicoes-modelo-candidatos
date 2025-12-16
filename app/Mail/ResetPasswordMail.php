<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nome;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($nome, $resetUrl)
    {
        $this->nome = $nome;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Unir Votações - Recuperação de Senha')
                    ->view('emails.resetPassword');
    }
}