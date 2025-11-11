<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\RelatorioLogsEleitorService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class RelatorioLogsEleitorController extends Controller
{
    protected RelatorioLogsEleitorService $relatorioLogsEleitorService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        RelatorioLogsEleitorService $relatorioLogsEleitorService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->relatorioLogsEleitorService = $relatorioLogsEleitorService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['logsDoEleitor']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $search = $request->input('search');
        $logs = $this->relatorioLogsEleitorService->listar($search);
        return view('adminRelatorioDeLogsDoEleitor.index', compact('logs'));
    }

    public function show(int $id)
    {
        $log = $this->relatorioLogsEleitorService->buscarPorId($id);

        if (!$log) {
            return redirect()->route('admin.adminRelatorioDeLogsDoEleitor.index')->with('error', 'Log não encontrado.');
        }

        return view('adminRelatorioDeLogsDoEleitor.show', compact('log'));
    }

    public function destroy(int $id)
    {
        $resultado = $this->relatorioLogsEleitorService->excluir($id);

        return redirect()
            ->route('admin.adminRelatorioDeLogsDoEleitor.index')
            ->with($resultado['status'], $resultado['message']);
    }

    public function gerarPdf(Request $request)
    {
        return $this->relatorioLogsEleitorService->gerarPdf($request->all());
    }

    public function gerarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $busca = $request->busca;
        $campos = $request->campos ?? ['eleitor_id','eleitor_nome','acao','mensagem','ip','pagina','created_at'];

        return $this->relatorioLogsEleitorService->gerarExcel($busca, $campos, $nomeArquivo);
    }
}