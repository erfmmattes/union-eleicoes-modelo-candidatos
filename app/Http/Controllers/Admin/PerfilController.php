<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PerfilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    protected PerfilService $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->middleware('auth');
        $this->perfilService = $perfilService;
    }

    public function index()
    {
        $userId = Auth::id();
        $perfil = $this->perfilService->obterPerfil($userId);

        if (!$perfil) {
            return redirect()->back()->with('error', 'Não foi possível carregar os dados do perfil.');
        }

        return view('adminPerfil.index', compact('perfil'));
    }

    public function atualizar(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$userId}",
        ]);

        $resultado = $this->perfilService->atualizarPerfil($userId, $request->only('name', 'email', 'tipo_usuario'));

        return redirect()->route('admin.adminPerfil.index')->with($resultado['status'], $resultado['message']);
    }
}