<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Configuracao;
use App\Models\Eleitor;

class VerificaTrocaSenha
{
    public function handle(Request $request, Closure $next)
    {
        $eleitorId = session('eleitor_id');
        $eleitor = Eleitor::find($eleitorId);
        $configuracao = Configuracao::find(1);
        if (!$eleitor) {
            return $next($request);
        }

        if (is_array($eleitor)) {
            $eleitor = (object) $eleitor;
        }

        $rotaTroca = 'loginEleicao.trocarSenhaAposLogin';
        $rotaHome  = 'loginEleicao.homeLogadoFront';

        if ($configuracao && $configuracao->trocar_de_senha_depois_login == 1) {

            if ((int) $eleitor->senha_trocada_depois_do_login === 1 && $request->routeIs($rotaTroca)) {
                return redirect()->route($rotaHome);
            }
        }

        $response = $next($request);
        return $response
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
    }
}