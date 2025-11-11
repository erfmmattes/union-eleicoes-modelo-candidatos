<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\DocumentosService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class DocumentosAdminController extends Controller
{
    protected DocumentosService $documentosService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        DocumentosService $documentosService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->documentosService = $documentosService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['documentos']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $documentos = $this->documentosService->listarTodosComFiltro($request->only(['q', 'tipo', 'ativo']));
        return view('adminDocumentos.index', compact('todasPermissoes', 'documentos'));
    }

    public function show(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['documentos']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $documento = $this->documentosService->buscarPorId($id);
        return view('adminDocumentos.show', compact('documento'));
    }

    public function status(int $id)
    {
        $ativo = $this->documentosService->alternarAtivo($id);

        return response()->json([
            'success' => true,
            'ativo' => $ativo,
        ]);
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['documentos']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminDocumentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
            'tipo' => 'nullable|string|max:50',
            'sequencia' => 'required|string|max:50',
            'ativo' => 'boolean',
        ]);

        $this->documentosService->criar($validated, $request->file('arquivo'));

        return redirect()->route('admin.adminDocumentos.index')
            ->with('success', 'Documento criado com sucesso!');
    }

    public function edit(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['documentos']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $documento = $this->documentosService->buscarPorId($id);
        return view('adminDocumentos.edit', compact('documento'));
    }

    public function update(Request $request, int $id)
    {
        $request->merge([
            'ativo' => $request->has('ativo'),
        ]);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
            'tipo' => 'nullable|string|max:50',
            'sequencia' => 'required|string|max:50',
            'ativo' => 'boolean',
        ]);

        $this->documentosService->atualizar($id, $validated, $request->file('arquivo'));

        return redirect()->route('admin.adminDocumentos.index')
            ->with('success', 'Documento atualizado com sucesso!');
    }

    public function destroy(int $id)
    {
        $this->documentosService->excluir($id);

        return redirect()->route('admin.adminDocumentos.index')
            ->with('success', 'Documento removido com sucesso!');
    }
}