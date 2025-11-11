<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEleitorAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Se não houver sessão de eleitor, redireciona para login
        if (!session()->has('eleitor_id')) {
            return redirect()->route('loginEleicao.index')->with('error', 'Faça login para acessar a votação.');
        }

        return $next($request);
    }
}