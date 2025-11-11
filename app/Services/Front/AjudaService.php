<?php

namespace App\Services\Front;

use App\Repositories\Front\AjudaRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Session;
use Exception;

class AjudaService
{
    protected $ajudaRepository;
    protected $logRepository;

    public function __construct(
        AjudaRepository $ajudaRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->ajudaRepository = $ajudaRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getConfiguracoesAjuda()
    {
        try {
            $dados = $this->ajudaRepository->obterConfiguracoes();
            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getConfiguracoesAjuda - AjudaService', $e);
            return null;
        }
    }

    public function getAjudaPrincipal()
    {
        try {
            $ajudas = $this->ajudaRepository->getAll();

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor acessou a ajuda na eleição',
                'Eleitor acessou a ajuda na eleição pela ação - getAjudaPrincipal',
                request()->ip(),
                '/ajuda'
            );

            return $ajudas;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getAjudaPrincipal - AjudaService', $e);
            return null;
        }
    }
}