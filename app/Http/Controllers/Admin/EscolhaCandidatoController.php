<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EscolhaCandidatoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;
use Illuminate\Http\Request;

class EscolhaCandidatoController extends Controller
{
    protected EscolhaCandidatoService $escolhaService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        EscolhaCandidatoService $escolhaService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->escolhaService = $escolhaService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['escolhas']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $perPage = 10;
        $escolhas = $this->escolhaService->listar($perPage);

        if ($request->filled('q') || $request->filled('status')) {
            $query = \App\Models\EscolhaCandidato::query();

            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nome', 'like', "%{$request->q}%")
                      ->orWhere('titulo', 'like', "%{$request->q}%")
                      ->orWhere('cargo', 'like', "%{$request->q}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', (int)$request->status);
            }

            $escolhas = $query->orderBy('sequencia')
                              ->paginate($perPage)
                              ->withQueryString();
        }

        return view('adminEscolhas.index', compact('escolhas', 'todasPermissoes'));
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['escolhas']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $listaEtapas = $this->escolhaService->listarTodasEtapas();

        return view('adminEscolhas.create', compact('listaEtapas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'etapas_candidatos_id' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'sequencia' => 'nullable|string|max:50',
            'branco_nulo_abstencao' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'foto_upload' => 'nullable|image|max:4096'
        ]);

        $data['branco_nulo_abstencao'] = $request->has('branco_nulo_abstencao');
        $data['status'] = $request->has('status');

        $this->escolhaService->criar($data);

        return redirect()->route('admin.adminEscolhas.index')
            ->with('success', 'Escolha criada com sucesso!');
    }

    public function show($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['escolhas']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $escolha = $this->escolhaService->buscar($id);

        return view('adminEscolhas.show', compact('escolha'));
    }

    public function edit($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['escolhas']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $escolha = $this->escolhaService->buscar($id);
        $listaEtapas = $this->escolhaService->listarTodasEtapas();

        return view('adminEscolhas.edit', compact('escolha','listaEtapas'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'etapas_candidatos_id' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'sequencia' => 'nullable|string|max:50',
            'branco_nulo_abstencao' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'foto_upload' => 'nullable|image|max:4096'
        ]);

        $data['branco_nulo_abstencao'] = $request->has('branco_nulo_abstencao');
        $data['status'] = $request->has('status');

        $this->escolhaService->atualizar($id, $data);

        return redirect()->route('admin.adminEscolhas.index')
            ->with('success', 'Escolha atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['escolhas']['deletar'] ?? false)) {
            abort(403, 'Você não tem permissão para excluir esta escolha.');
        }

        $this->escolhaService->deletar($id);

        return redirect()->route('admin.adminEscolhas.index')
            ->with('success', 'Escolha removida com sucesso!');
    }

    public function toggleStatus($id)
    {
        try {
            $item = $this->escolhaService->toggleStatus($id);
            return response()->json(['success' => true, 'status' => (bool)$item->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}