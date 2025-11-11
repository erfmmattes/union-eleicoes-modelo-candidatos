<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\NaoVotanteService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class NaoVotanteController extends Controller
{
    protected NaoVotanteService $naoVotanteService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        NaoVotanteService $naoVotanteService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->naoVotanteService = $naoVotanteService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['naoVotantes']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $filtros = $request->only(['busca', 'perPage']);
        $naoVotantes = $this->naoVotanteService->listarTodosComFiltro($filtros);
        return view('adminNaoVotantes.index', compact('naoVotantes'));
    }

    public function show(int $id)
    {
        $naoVotante = $this->naoVotanteService->obterDetalhes($id);

        if (!$naoVotante) {
            return redirect()->route('admin.adminNaoVotantes.index')
                            ->with('error', 'Votante não encontrado.');
        }

        return view('adminNaoVotantes.show', compact('naoVotante'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->naoVotanteService->gerarPdf($request->all());
    }

    public function exportarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $busca = $request->busca;
        $campos = $request->campos ?? ['id','nome','cpf_cnpj','celular','email','votou'];

        return $this->naoVotanteService->exportarExcel($busca, $campos, $nomeArquivo);
    }
}