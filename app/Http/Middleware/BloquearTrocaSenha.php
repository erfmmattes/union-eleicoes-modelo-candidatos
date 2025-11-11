<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloquearTrocaSenha
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->trocar_senha === 1) {
            return redirect()->route('admin.home')
                ->with('info', 'Você já alterou sua senha.');
        }

        return $next($request);
    }
}