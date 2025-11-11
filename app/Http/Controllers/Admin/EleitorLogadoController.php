<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\EleitorLogadoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class EleitorLogadoController extends Controller
{
    protected EleitorLogadoService $eleitorLogadoService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        EleitorLogadoService $eleitorLogadoService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->eleitorLogadoService = $eleitorLogadoService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitoresLogados']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $filtros = $request->only(['q', 'perPage']);
        $listaEleitoresLogados = $this->eleitorLogadoService->listarTodos($filtros);
        // dd($listaEleitoresLogados);
        return view('adminEleitorLogado.index', compact('listaEleitoresLogados'));
    }

    public function show(int $id)
    {
        $listaDeEleitor = $this->eleitorLogadoService->obterDetalhes($id);
        // dd($listaDeEleitor);

        if (!$listaDeEleitor) {
            return redirect()->route('admin.adminEleitorLogado.index')
                            ->with('error', 'Eleitor não encontrado.');
        }

        return view('adminEleitorLogado.show', compact('listaDeEleitor'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->eleitorLogadoService->gerarPdf($request->all());
    }

    public function exportarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $busca = $request->busca;
        $campos = $request->campos ?? ['id','nome','cpf_cnpj','email','celular', 'ip', 'created_at'];

        return $this->eleitorLogadoService->exportarExcel($busca, $campos, $nomeArquivo);
    }
}