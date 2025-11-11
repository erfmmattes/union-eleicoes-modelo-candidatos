<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'forcar_troca_senha' => \App\Http\Middleware\ForcarTrocaSenha::class,
            'bloquear_troca_senha' => \App\Http\Middleware\BloquearTrocaSenha::class,
            'eleitor.auth' => \App\Http\Middleware\CheckEleitorAuth::class,
            'verificar.aceite.termos' => \App\Http\Middleware\VerificarAceiteTermos::class,
            'verifica.sessao.front' => \App\Http\Middleware\VerificaTempoSessaoFront::class,
            'sessao.unica.front' => \App\Http\Middleware\VerificaSessaoUnicaFront::class,
            'periodo.eleicao' => \App\Http\Middleware\VerificaPeriodoEleicao::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
