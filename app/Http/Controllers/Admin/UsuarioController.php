<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\UsuarioService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class UsuarioController extends Controller
{
    protected UsuarioService $usuarioService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        UsuarioService $usuarioService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->usuarioService = $usuarioService;
        $this->permissaoService = $permissaoService;
    }

    public function index()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['usuarios']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $usuarios = $this->usuarioService->listarUsuarios(Auth::id());
        return view('adminUsuario.index', compact('usuarios'));
    }

    public function create()
    {
        $listTiposUsuarios = $this->usuarioService->listarTodosOsTiposDeUsuarios();
        $listTelas = $this->usuarioService->listarTodasTelas();
        return view('adminUsuario.create', compact('listTiposUsuarios', 'listTelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tipo_usuario' => 'required|string|max:255',
        ]);

        $this->usuarioService->criarUsuarioComPermissoes($request->all());

        return redirect()->route('admin.adminUsuario.index')
                        ->with('success', 'Usuário criado e permissões salvas com sucesso!');
    }

    public function edit(int $id)
    {
        $usuario = $this->usuarioService->buscarUsuarioPorId($id);
        $listTiposUsuarios = $this->usuarioService->listarTodosOsTiposDeUsuarios();
        $listTelas = $this->usuarioService->listarTodasTelas();
        $usuarioPermissoes = $this->usuarioService->obterPermissoesPorUsuario($id);

        return view(
            'adminUsuario.edit',
            compact('usuario', 'listTiposUsuarios', 'listTelas', 'usuarioPermissoes')
        );
    }

    public function update(Request $request, int $id)
    {
        $request->merge([
            'status' => $request->has('status'),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'tipo_usuario' => 'required|string|max:50',
            'status' => 'nullable|boolean',
        ]);

        $this->usuarioService->atualizarUsuarioComPermissoes($id, $request->all());

        return redirect()->route('admin.adminUsuario.index')
                        ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(int $id)
    {
        $this->usuarioService->excluirUsuario($id);
        return redirect()->route('admin.adminUsuario.index')->with('success', 'Usuário excluído com sucesso!');
    }

    public function show(int $id)
    {
        $result = $this->usuarioService->buscarUsuarioPorId($id);
        $usuario = $this->usuarioService->buscarUsuario($id);
        $usuarioPermissoes = $this->usuarioService->obterPermissoesPorUsuario($id);
        $listTelas = $this->usuarioService->listarTodasTelas();

        return view('adminUsuario.show', compact('usuario', 'usuarioPermissoes', 'listTelas'));
    }

    public function toggleStatus(int $id)
    {
        $usuario = $this->usuarioService->alternarStatus($id);

        return response()->json([
            'success' => true,
            'status' => $usuario->status,
        ]);
    }
}