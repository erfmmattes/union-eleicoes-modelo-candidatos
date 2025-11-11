<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Front\RecuperarSenhaService;

class RecuperarSenhaController extends Controller
{
    protected $recuperarSenhaService;

    public function __construct(RecuperarSenhaService $recuperarSenhaService)
    {
        $this->recuperarSenhaService = $recuperarSenhaService;
    }

    public function index()
    {
        $dados = $this->recuperarSenhaService->getDadosRecuperarSenha();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        return view('recuperarSenha.index', compact('dados'));
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string',
        ]);

        return $this->recuperarSenhaService->buscarPorCpf($request);
    }

    public function enviarSenha(Request $request)
    {
        return $this->recuperarSenhaService->enviarSenha($request);
    }
}