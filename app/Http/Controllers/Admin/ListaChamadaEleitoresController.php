<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ListaChamadaEleitoresService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class ListaChamadaEleitoresController extends Controller
{
    protected ListaChamadaEleitoresService $listaChamadaEleitoresService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        ListaChamadaEleitoresService $listaChamadaEleitoresService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->listaChamadaEleitoresService = $listaChamadaEleitoresService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['listaDeChamada']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $filtros = $request->only(['q', 'perPage']);
        $listaChamadas = $this->listaChamadaEleitoresService->listarTodos($filtros);
        return view('adminListaChamada.index', compact('listaChamadas'));
    }

    public function show(int $id)
    {
        $listaDaChamada = $this->listaChamadaEleitoresService->obterDetalhes($id);

        if (!$listaDaChamada) {
            return redirect()->route('admin.adminListaChamada.index')
                            ->with('error', 'Eleitor não encontrado.');
        }

        return view('adminListaChamada.show', compact('listaDaChamada'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->listaChamadaEleitoresService->gerarPdf($request->all());
    }

    public function exportarExcel(Request $request)
    {
        $nomeArquivo = $request->nome_arquivo;
        $busca = $request->busca;
        $campos = $request->campos ?? ['lista_chamada_id','nome','cpf_cnpj','email','celular', 'created_at'];

        return $this->listaChamadaEleitoresService->exportarExcel($busca, $campos, $nomeArquivo);
    }
}