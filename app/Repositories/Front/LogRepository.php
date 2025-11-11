<?php

namespace App\Repositories\Front;

use App\Models\Log;

class LogRepository
{
    /**
     * Cria um log no banco.
     *
     * @param string $nomeLog Nome do log: 'erro', 'info', 'aviso', etc.
     * @param string|\Exception $mensagem Mensagem ou Exception
     * @return \App\Models\Log
     */
    public function criarLog(string $nomeLog, $mensagem)
    {
        // Se for Exception, pega a mensagem
        if ($mensagem instanceof \Exception) {
            $mensagem = $mensagem->getMessage();
        }

        return Log::create([
            'nome_log' => $nomeLog,
            'mensagem' => $mensagem,
        ]);
    }
}