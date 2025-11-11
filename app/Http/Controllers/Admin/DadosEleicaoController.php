<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\DadosEleicaoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class DadosEleicaoController extends Controller
{
    protected DadosEleicaoService $dadosEleicaoService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        DadosEleicaoService $dadosEleicaoService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->dadosEleicaoService = $dadosEleicaoService;
        $this->permissaoService = $permissaoService;
    }

    public function index()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['dadosDaEleicao']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $dados = $this->dadosEleicaoService->listarResumo();
        return view('adminDadosEleicao.index', compact('dados'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->dadosEleicaoService->gerarPdf($request->all());
    }
}