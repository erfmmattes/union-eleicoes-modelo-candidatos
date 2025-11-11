<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcarTrocaSenha
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->trocar_senha === 0) {
            if (
                !$request->routeIs('admin.forcarTrocaSenha') &&
                !$request->routeIs('admin.forcarTrocaSenha.update')
            ) {
                return redirect()->route('admin.forcarTrocaSenha');
            }
        }

        return $next($request);
    }
}