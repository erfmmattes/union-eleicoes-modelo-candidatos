<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\Front\TrocarSenhaFrontService;

class TrocarSenhaFrontController extends Controller
{
    protected $trocarSenhaFrontService;

    public function __construct(TrocarSenhaFrontService $trocarSenhaFrontService)
    {
        $this->trocarSenhaFrontService = $trocarSenhaFrontService;
    }

    public function index()
    {
        $dados = $this->trocarSenhaFrontService->getDadosTrocarSenhaFront();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        return view('trocarSenha.index', compact('dados'));
    }

    public function senhaTrocar(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|string|min:6',
            'nova_senha' => 'required|string|min:8|confirmed',
        ], [
            'senha_atual.required' => 'Informe sua senha atual.',
            'senha_atual.min' => 'A senha atual deve ter pelo menos 6 caracteres.',
            'nova_senha.required' => 'Informe a nova senha.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $resultado = $this->trocarSenhaFrontService->trocarSenha($request->all());

        if ($resultado['status'] === 'success') {
            return redirect()->back()->with('success', $resultado['mensagem']);
        }

        return redirect()->back()->with('error', $resultado['mensagem']);

        return redirect()->route('trocarSenha.index')->with('success', 'Senha alterada com sucesso!');
    }
}