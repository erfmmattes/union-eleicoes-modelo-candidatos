<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ZeresimaService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class ZeresimaController extends Controller
{
    protected ZeresimaService $zeresimaService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        ZeresimaService $zeresimaService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->zeresimaService = $zeresimaService;
        $this->permissaoService = $permissaoService;
    }

    public function index()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['zeresimaDeVotos']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $dados = $this->zeresimaService->verificarStatus();
        return view('adminZeresima.index', compact('dados'));
    }

    public function gerarPdf(Request $request)
    {
        return $this->zeresimaService->gerarPdf($request->all());
    }
}