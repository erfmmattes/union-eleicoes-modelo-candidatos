<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\AjudaAdminService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class AjudaAdminController extends Controller
{
    protected AjudaAdminService $ajudaAdminService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        AjudaAdminService $ajudaAdminService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->ajudaAdminService = $ajudaAdminService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['ajuda']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $ajudas = $this->ajudaAdminService->listarTodosComFiltro($request->only(['q', 'ativo']));
        return view('adminAjuda.index', compact('todasPermissoes', 'ajudas'));
    }

    public function show(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['ajuda']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $ajuda = $this->ajudaAdminService->buscarPorId($id);
        return view('adminAjuda.show', compact('ajuda'));
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['ajuda']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminAjuda.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'ativo' => $request->has('ativo'),
        ]);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
            'sequencia' => 'required|string|max:255',
        ]);

        $this->ajudaAdminService->criar($validated);

        return redirect()->route('admin.adminAjuda.index')
                         ->with('success', 'Ajuda criada com sucesso!');
    }

    public function edit(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['ajuda']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $ajuda = $this->ajudaAdminService->buscarPorId($id);
        return view('adminAjuda.edit', compact('ajuda'));
    }

    public function update(Request $request, int $id)
    {
        $request->merge([
            'ativo' => $request->has('ativo'),
        ]);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
            'sequencia' => 'required|string|max:255',
        ]);

        $this->ajudaAdminService->atualizar($id, $validated);

        return redirect()->route('admin.adminAjuda.index')
                         ->with('success', 'Ajuda atualizada com sucesso!');
    }

    public function status(int $id)
    {
        $ativo = $this->ajudaAdminService->alternarAtivo($id);

        return response()->json([
            'success' => true,
            'ativo' => $ativo,
        ]);
    }

    public function destroy(int $id)
    {
        $this->ajudaAdminService->excluir($id);

        return redirect()->route('admin.adminAjuda.index')
                         ->with('success', 'Ajuda removida com sucesso!');
    }
}