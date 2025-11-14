<?php

namespace App\Services\Admin;

use App\Repositories\Admin\HomeAdminRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Front\LogRepository;

class HomeAdminService
{
    protected HomeAdminRepository $homeAdminRepository;
    protected ConfiguracoesRepository $configuracoesRepository;
    protected LogRepository $logRepository;

    public function __construct(
        HomeAdminRepository $homeAdminRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository
    ) {
        $this->homeAdminRepository = $homeAdminRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
    }

    public function usuariosAtivos()
    {
        try {
            return $this->homeAdminRepository->obterUsuariosAtivos();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - usuariosAtivos - HomeAdminService', $e);
            return null;
        }
    }

    public function votantesTotal()
    {
        try {
            return $this->homeAdminRepository->totalVotantes();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - votantesTotal - HomeAdminService', $e);
            return null;
        }
    }

    public function votantesNaoTotal()
    {
        try {
            return $this->homeAdminRepository->totalNaoVotantes();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - votantesNaoTotal - HomeAdminService', $e);
            return null;
        }
    }

    public function votantesPercentual()
    {
        try {
            $totalUsuariosAtivos = $this->homeAdminRepository->obterUsuariosAtivos();
            $totalVotantesAtivos = $this->homeAdminRepository->totalVotantes();

            if ($totalUsuariosAtivos === 0) {
                return 0.0;
            }

            $percentual = ($totalVotantesAtivos / $totalUsuariosAtivos) * 100;
            return round($percentual, 2);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - votantesPercentual - HomeAdminService', $e);
            return null;
        }
    }

    public function dataConfiguracao()
    {
        try {
            return $this->configuracoesRepository->getFirstOrCreate();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - dataConfiguracao - HomeAdminService', $e);
            return null;
        }
    }

    public function votantesTotalPorDia()
    {
        try {
            return $this->homeAdminRepository->totalVotantesPorDia();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - votantesTotalPorDia - HomeAdminService', $e);
            return null;
        }
    }
}