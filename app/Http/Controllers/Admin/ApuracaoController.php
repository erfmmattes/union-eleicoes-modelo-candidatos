<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ApuracaoService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class ApuracaoController extends Controller
{
    protected ApuracaoService $apuracaoService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        ApuracaoService $apuracaoService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        // $this->middleware(['auth', 'forcar_troca_senha']);
        $this->middleware('auth');
        $this->apuracaoService = $apuracaoService;
        $this->permissaoService = $permissaoService;
    }

    public function index()
    {
        $etapas = $this->apuracaoService->listarEtapas();

        $todasPermissoes = app(\App\Services\Admin\ListaUsuarioTelaPermissaoService::class)
            ->getTodasPermissoes();

        return view('adminApuracao.index', compact('etapas', 'todasPermissoes'));
    }

    public function show($etapaId)
    {
        $etapa = $this->apuracaoService->resgataEtapa($etapaId);
        $apuracao = $this->apuracaoService->apuracao($etapaId);
        $seqEtapa = 'etapa_' . $etapa->sequencia;

        return view('adminApuracao.show', compact('etapa', 'apuracao'));
    }

    public function apuracaoTotalPdf($etapaId)
    {
        return $this->apuracaoService->gerarPdfApuracao($etapaId);
    }

    public function votantesApuracaoPdf(Request $request, $etapaSequencia)
    {
        return $this->apuracaoService->votantesApuracaoPdfgerarPdf($request->all(), $etapaSequencia);
    }
}