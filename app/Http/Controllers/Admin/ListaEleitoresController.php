<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ListaEleitoresService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class ListaEleitoresController extends Controller
{
    protected ListaEleitoresService $listaEleitoresService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        ListaEleitoresService $listaEleitoresService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->listaEleitoresService = $listaEleitoresService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['listaDeEleitores']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $eleitores = $this->listaEleitoresService->listarTodos($request->only(['q', 'status']));
        return view('adminListaEleitores.index', compact('eleitores'));
    }

    public function show(int $id)
    {
        $listaDeEleitor = $this->listaEleitoresService->obterDetalhes($id);

        if (!$listaDeEleitor) {
            return redirect()->route('admin.adminListaEleitores.index')
                            ->with('error', 'Votante não encontrado.');
        }

        return view('adminListaEleitores.show', compact('listaDeEleitor'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->listaEleitoresService->gerarPdf($request->all());
    }

    public function exportarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $busca = $request->busca;
        $campos = $request->campos ?? ['id','nome','cpf_cnpj','email','celular', 'passou_por_ajuste', 'recuperacao_senha', 'troca_senha', 'status', 'created_at', 'updated_at'];

        return $this->listaEleitoresService->exportarExcel($busca, $campos, $nomeArquivo);
    }
}