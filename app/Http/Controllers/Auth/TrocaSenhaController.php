<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TrocaSenhaController extends Controller
{
    public function __construct()
    {
        $this->middleware('bloquear_troca_senha');
    }

    public function showForm()
    {
        return view('auth.forcarTrocaSenha');
    }

    public function update(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ], [
            'senha_atual.required' => 'Informe sua senha atual.',
            'nova_senha.required' => 'Informe a nova senha.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->senha_atual, $user->password)) {
            return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->nova_senha),
            'trocar_senha' => 1,
            'conta_ativa' => 1,
            'status' => 1,
            'forcar_troca_senha' => false,
        ]);

        return redirect()->route('admin.home')->with('success', 'Senha alterada com sucesso!');
    }
}