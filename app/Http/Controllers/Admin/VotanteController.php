<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\VotanteService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class VotanteController extends Controller
{
    protected VotanteService $votanteService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        VotanteService $votanteService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->votanteService = $votanteService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['votantes']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $filtros = $request->only(['busca', 'perPage', 'etapa']);
        $votantes = $this->votanteService->listarTodosComFiltro($filtros);
        return view('adminVotantes.index', compact('votantes'));
    }

    public function show(int $id)
    {
        $votante = $this->votanteService->obterDetalhes($id);

        if (!$votante) {
            return redirect()->route('admin.votantes.index')
                            ->with('error', 'Votante não encontrado.');
        }

        return view('adminVotantes.show', compact('votante'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->votanteService->gerarPdf($request->all());
    }

    public function exportarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $etapa = $request->etapa;
        $busca = $request->busca;
        $campos = $request->campos ?? ['id','nome','cpf_cnpj','votado_em','votou', 'etapa', 'ip'];

        return $this->votanteService->exportarExcel($etapa, $busca, $campos, $nomeArquivo);
    }
}