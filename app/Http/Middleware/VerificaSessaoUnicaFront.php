<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Eleitor;

class VerificaSessaoUnicaFront
{
    public function handle(Request $request, Closure $next)
    {
        // Só executa a verificação se o usuário estiver logado
        if (session()->has('eleitor_id') && session()->has('session_token_front')) {
            $eleitor = Eleitor::find(session('eleitor_id'));

            // Se não existir ou o token não bater, derruba a sessão
            if (!$eleitor || $eleitor->session_token_front !== session('session_token_front')) {
                session()->flush();
                return redirect()->route('loginEleicao.index')
                    ->with('error', 'Você foi desconectado porque sua conta foi acessada em outro dispositivo.');
            }
        }

        return $next($request);
    }
}