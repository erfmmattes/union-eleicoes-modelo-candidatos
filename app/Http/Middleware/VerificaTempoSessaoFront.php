<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\EleitorLogado;

class VerificaTempoSessaoFront
{
    // Tempo limite absoluto em segundos (3600 segundos = 1 hora)
    private int $tempoLimite = 3600; // 1 hora
    private int $alertaAntes = 300;  // 5 minutos antes para aviso (opcional)

    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        // [MANTIDO] Ignora assets e AJAX (para não prolongar desnecessariamente o processo)
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico)$/i', $path) || $request->ajax() || $request->wantsJson()) {
            return $next($request);
        }

        // Se não estiver logado, segue normalmente
        if (!Session::has('front_logado') || !Session::has('ultima_atividade_front')) {
             // Garante que a sessão só tenha o timestamp de início se 'front_logado' existir
            return $next($request);
        }

        // Agora, 'ultima_atividade_front' representa o TEMPO DE INÍCIO DA SESSÃO (Timestamp do Login)
        $tempoInicioSessao = Session::get('ultima_atividade_front');
        
        // Calcula a duração total da sessão em segundos
        $tempoDecorrido = now()->timestamp - $tempoInicioSessao;

        Log::info("[Sessão Absoluta] Início: " . date('H:i:s', $tempoInicioSessao) . ", Decorrido: {$tempoDecorrido}s / Limite: {$this->tempoLimite}s");

        // Se o TEMPO DECORRIDO for maior que o TEMPO LIMITE (1 hora) → desloga
        if ($tempoDecorrido > $this->tempoLimite) {
            Log::warning("[Sessão Absoluta] Usuário deslogado por expiração. Decorrido: {$tempoDecorrido}s");
            
            $eleitorId = session('eleitor_id');
            $apaga = EleitorLogado::where('eleitor_id', $eleitorId)->delete();
            Log::warning("Apaga {$apaga}s");
            session()->forget([
                'eleitor_id',
                'eleitor_nome',
                'front_logado',
                'eleitor_temp_id',
                'ultima_atividade_front',
            ]);

            return redirect()->route('loginEleicao.index')
                ->with('error', 'Sua sessão expirou devido ao tempo limite. Faça login novamente.');
        }

        // [REMOVIDO] A linha Session::put('ultima_atividade_front', now()->timestamp);
        // O timestamp NUNCA é atualizado, garantindo o tempo absoluto de 1 hora.

        return $next($request);
    }
}