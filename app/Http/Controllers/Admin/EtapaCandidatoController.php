<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EtapaCandidatoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;
use Illuminate\Http\Request;

class EtapaCandidatoController extends Controller
{
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
        $listaSetores = $this->etapaCandidatoService->listarTodosSetores();

        return view('adminEtapa.create', compact('listaSetores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'setores_id' => 'nullable|string|max:255',
            'multipla_escolha' => 'nullable|boolean',
            'quantidade_minima_escolhas' => 'nullable|string|max:255',
            'quantidade_maxima_escolhas' => 'nullable|string|max:255',
            'sequencia' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $data['multipla_escolha'] = $request->has('multipla_escolha') ? 1 : 0;
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
        $escolhasRelacionadasEtapas = $this->etapaCandidatoService->buscarEtapasRelacionadas($id);
        return view('adminEtapa.show', compact('etapa','escolhasRelacionadasEtapas'));
    }

    public function edit($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['etapas']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $etapa = $this->etapaCandidatoService->buscar($id);
        $listaSetores = $this->etapaCandidatoService->listarTodosSetores();
        return view('adminEtapa.edit', compact('etapa','listaSetores'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'setores_id' => 'nullable|string|max:255',
            'multipla_escolha' => 'nullable|boolean',
            'quantidade_minima_escolhas' => 'nullable|string|max:255',
            'quantidade_maxima_escolhas' => 'nullable|string|max:255',
            'sequencia' => 'nullable|string|max:20',
            'status' => 'nullable|boolean',
        ]);

        $data['multipla_escolha'] = $request->has('multipla_escolha') ? 1 : 0;
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
        $resultado = $this->etapaCandidatoService->deletar($id);
        if (!$resultado['deleted']) {
            return redirect()->back()->with('error', 'Não é possível deletar esta etapa pois existem escolhas relacionadas.');
        }
        return redirect()->route('admin.adminEtapa.index')->with('success', 'Etapa removida com sucesso!');
    }

    public function toggleStatus($id)
    {
        $retorno = $this->etapaCandidatoService->toggleStatus($id);
        if (!$retorno['allowed'] && empty($retorno['error'])) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível alterar o status. Esta etapa possui escolhas vinculadas.'
                ], 400);
            }

            return redirect()
                ->route('admin.adminEtapa.index')
                ->with('error', 'Não é possível alterar o status. Esta etapa possui escolhas vinculadas.');
        }

        if (!empty($retorno['error'])) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao tentar atualizar o status. Tente novamente.'
                ], 500);
            }

            return redirect()
                ->route('admin.adminEtapa.index')
                ->with('error', 'Erro ao tentar atualizar o status. Tente novamente.');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => (bool)$retorno['etapa']->status
            ]);
        }

        return redirect()
            ->route('admin.adminEtapa.index')
            ->with('success', 'Status atualizado com sucesso!');
    }
}