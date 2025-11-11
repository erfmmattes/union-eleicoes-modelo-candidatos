<?php

namespace App\Services\Front;

use App\Repositories\Front\HomeRepository;
use App\Repositories\Front\LogRepository;
use Exception;

class HomeService
{
    protected $homeRepository;
    protected $logRepository;

    public function __construct(
        HomeRepository $homeRepository,
        LogRepository $logRepository
    ) {
        $this->homeRepository = $homeRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosHome()
    {
        try {
            $dados = $this->homeRepository->buscarDados();

            if (!isset($dados['configuracoes']) || !$dados['configuracoes']) {
                $dados['configuracoes'] = $this->homeRepository->obterConfiguracoes();
            }

            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosHome - HomeService', $e);
            return null;
        }
    }
}