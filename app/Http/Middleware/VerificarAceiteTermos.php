<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Eleitor;

class VerificarAceiteTermos
{
    public function handle(Request $request, Closure $next)
    {
        $eleitorId = session('eleitor_id');

        if (!$eleitorId) {
            return redirect()->route('loginEleicao.index')->with('error', 'Faça login primeiro.');
        }

        $eleitor = Eleitor::find($eleitorId);
        if ($eleitor && $eleitor->aceitou_os_termos) {
            return redirect()->route('home.index')->with('info', 'Você já aceitou os termos.');
        }

        return $next($request);
    }
}