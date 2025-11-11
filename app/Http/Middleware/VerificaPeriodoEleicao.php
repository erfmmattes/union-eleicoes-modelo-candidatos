<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Configuracao; // ajuste o nome do seu model se for diferente

class VerificaPeriodoEleicao
{
    public function handle(Request $request, Closure $next)
    {
        // 🔹 Busca a configuração atual da eleição (ajuste se for por ID fixo)
        $config = Configuracao::find(1);

        if ($config && $config->data_hora_inicio_eleicao && $config->data_hora_final_eleicao) {
            $agora = Carbon::now();

            // 🔒 Se ainda não começou OU já terminou
            if ($agora->lt($config->data_hora_inicio_eleicao) || $agora->gt($config->data_hora_final_eleicao)) {
                return redirect()->route('home.index')
                    ->with('error', 'A votação não está disponível neste momento.');
            }
        }

        return $next($request);
    }
}