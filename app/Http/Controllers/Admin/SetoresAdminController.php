<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SetoresService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;
use Illuminate\Http\Request;

class SetoresAdminController extends Controller
{
    protected SetoresService $setoresService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        SetoresService $setoresService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');

        $this->setoresService = $setoresService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $perPage = 10;

        $setores = $this->setoresService->listar($perPage);

        if ($request->filled('q') || $request->filled('status')) {
            $query = \App\Models\Setor::query();

            if ($request->filled('q')) {
                $query->where('nome', 'like', "%{$request->q}%");
            }

            if ($request->filled('status')) {
                $query->where('status', (int)$request->status);
            }

            $query->orderBy('id', 'DESC');

            $setores = $query->paginate($perPage)->withQueryString();
        }

        return view('adminSetor.index', compact('setores', 'todasPermissoes'));
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para criar setores.');
        }

        return view('adminSetor.create');
    }

    public function store(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para criar setores.');
        }

        $data = $request->validate([
            'nome' => 'nullable|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $data['status'] = $request->has('status');

        $this->setoresService->criar($data);

        return redirect()
            ->route('admin.adminSetor.index')
            ->with('success', 'Setor criado com sucesso!');
    }

    public function edit($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para editar setores.');
        }

        $setor = $this->setoresService->buscar($id);

        if (!$setor) {
            return redirect()
                ->route('admin.adminSetor.index')
                ->with('error', 'Setor não encontrado.');
        }

        return view('adminSetor.edit', compact('setor'));
    }

    public function update(Request $request, $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para editar setores.');
        }

        $data = $request->validate([
            'nome' => 'nullable|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        $data['status'] = $request->has('status');

        $this->setoresService->atualizar($id, $data);

        return redirect()
            ->route('admin.adminSetor.index')
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function show($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }

        $setor = $this->setoresService->buscar($id);

        return view('adminSetor.show', compact('setor'));
    }

    public function destroy($id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();

        if (empty($todasPermissoes['setores']['deletar'] ?? false)) {
            abort(403, 'Você não tem permissão para excluir setores.');
        }

        $resultado = $this->setoresService->deletar($id);

        if (!$resultado['deleted']) {
            return redirect()
                ->route('admin.adminSetor.index')
                ->with('error', 'Não é possível remover este setor, pois existem registros relacionados.');
        }

        return redirect()
            ->route('admin.adminSetor.index')
            ->with('success', 'Setor removido com sucesso!');
    }

    public function toggleStatus($id)
    {
        $retorno = $this->setoresService->alterarStatus($id);
        if (!$retorno['allowed'] && empty($retorno['error'])) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível alterar o status. Este setor possui etapas vinculadas.'
                ], 400);
            }

            return redirect()
                ->route('admin.adminSetor.index')
                ->with('error', 'Não é possível alterar o status. Este setor possui etapas vinculadas.');
        }

        if (!empty($retorno['error'])) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao tentar atualizar o status. Tente novamente.'
                ], 500);
            }

            return redirect()
                ->route('admin.adminSetor.index')
                ->with('error', 'Erro ao tentar atualizar o status. Tente novamente.');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => (bool)$retorno['item']->status
            ]);
        }

        return redirect()
            ->route('admin.adminSetor.index')
            ->with('success', 'Status atualizado com sucesso!');
    }
}