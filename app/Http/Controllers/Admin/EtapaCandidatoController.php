<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EtapaCandidatoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;
use Illuminate\Http\Request;

class EtapaCandidatoController extends Controller
{
    // protected EtapaCandidatoService $etapaCandidatoService;

    // public function __construct(EtapaCandidatoService $etapaCandidatoService)
    // {
    //     $this->etapaCandidatoService = $etapaCandidatoService;
    // }
    protected EtapaCandidatoService $etapaCandidatoService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        EtapaCandidatoService $etapaCandidatoService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->etapaCandidatoService = $etapaCandidatoService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $perPage = 10;
        $etapas = $this->etapaCandidatoService->listar($perPage);

        if ($request->filled('q') || $request->filled('status')) {
            $query = \App\Models\EtapaCandidato::query();
            if ($request->filled('q')) {
                $query->where('nome', 'like', '%'.$request->q.'%');
            }
            if ($request->filled('status')) {
                $query->where('status', (int)$request->status);
            }
            $etapas = $query->orderBy('sequencia')->paginate($perPage)->withQueryString();
        }

        return view('adminEtapa.index', compact('todasPermissoes','etapas'));
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEtapa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'sequencia' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        $this->etapaCandidatoService->criar($data);

        return redirect()->route('admin.adminEtapa.index')->with('success', 'Etapa criada com sucesso!');
    }

    public function show($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $etapa = $this->etapaCandidatoService->buscar($id);
        return view('adminEtapa.show', compact('etapa'));
    }

    public function edit($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $etapa = $this->etapaCandidatoService->buscar($id);
        return view('adminEtapa.edit', compact('etapa'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'sequencia' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        $this->etapaCandidatoService->atualizar($id, $data);

        return redirect()->route('admin.adminEtapa.index')->with('success', 'Etapa atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['deletar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $this->etapaCandidatoService->deletar($id);
        return redirect()->route('admin.adminEtapa.index')->with('success', 'Etapa removida com sucesso!');
    }

    public function toggleStatus($id)
    {
        try {
            $etapa = $this->etapaCandidatoService->toggleStatus($id);
            return response()->json(['success' => true, 'status' => (bool)$etapa->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}