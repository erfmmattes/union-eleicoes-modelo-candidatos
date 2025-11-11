<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\LogsService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    protected LogsService $logsService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        LogsService $logsService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->logsService = $logsService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['logsDeErro']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $search = $request->query('search');
        $logs = $this->logsService->listarTodos($search);
        return view('adminLogs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = $this->logsService->buscarPorId($id);

        if (!$log) {
            return redirect()
                ->route('admin.adminLogs.index')
                ->with('error', 'Log não encontrado.');
        }

        return view('adminLogs.show', compact('log'));
    }

    public function destroy($id)
    {
        $this->logsService->excluir($id);
        return redirect()->route('admin.adminLogs.index')->with('success', 'Log excluído com sucesso.');
    }
}