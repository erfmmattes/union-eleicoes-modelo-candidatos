<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\HomeAdminService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class HomeAdminController extends Controller
{
    protected HomeAdminService $homeAdminService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        HomeAdminService $homeAdminService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware(['auth', 'forcar_troca_senha']);
        $this->homeAdminService = $homeAdminService;
        $this->permissaoService = $permissaoService;
    }

    public function index()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['dashboard']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $totalUsuariosAtivos = $this->homeAdminService->usuariosAtivos();
        $totalVotantes = $this->homeAdminService->votantesTotal();
        $totalNaoVotantes = $this->homeAdminService->votantesNaoTotal();
        $percentualVotantes = $this->homeAdminService->votantesPercentual();
        $configuracaoData = $this->homeAdminService->dataConfiguracao();
        $totalVotantesPorDia = $this->homeAdminService->votantesTotalPorDia();
        return view('adminHome.home', compact('totalUsuariosAtivos','totalVotantes','totalNaoVotantes','percentualVotantes','configuracaoData','totalVotantesPorDia'));
    }
}