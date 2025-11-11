<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ConfiguracoesController;

Route::post('/configuracoes/verificar-senha', [ConfiguracoesController::class, 'verificarSenha']);