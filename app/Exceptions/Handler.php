<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        // ✅ Se for erro de autenticação (sessão expirada, etc)
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'unauthenticated' => true,
                    'message' => 'Sessão expirada. Faça login novamente.',
                    'redirect' => route('login'),
                ], 401);
            }
        }

        // ✅ Para qualquer redirect indevido (tipo 302 HTML)
        if ($request->expectsJson() && $this->isRedirectResponse($e)) {
            return response()->json([
                'error' => 'Redirecionamento bloqueado',
            ], 400);
        }

        return parent::render($request, $e);
    }

    protected function isRedirectResponse(Throwable $e): bool
    {
        return method_exists($e, 'getStatusCode') && $e->getStatusCode() === 302;
    }
}