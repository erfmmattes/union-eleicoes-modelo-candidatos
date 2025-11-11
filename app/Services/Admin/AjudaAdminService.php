<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AjudaAdminRepository;
use App\Repositories\Front\LogRepository;
use Exception;

class AjudaAdminService
{
    protected AjudaAdminRepository $ajudaAdminRepository;

    public function __construct(
        ajudaAdminRepository $ajudaAdminRepository,
        LogRepository $logRepository
    ) {
        $this->ajudaAdminRepository = $ajudaAdminRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodosComFiltro(array $filtros = [])
    {
        try {
            return $this->ajudaAdminRepository->listarComFiltro($filtros);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosComFiltro - AjudaAdminService', $e);
            return null;
        }
    }

    public function buscarPorId(int $id)
    {
        try {
            return $this->ajudaAdminRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarPorId - AjudaAdminService', $e);
            return null;
        }
    }

    public function criar(array $dados)
    {
        try {
            return $this->ajudaAdminRepository->criar($dados);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - AjudaAdminService', $e);
            return null;
        }
    }

    public function atualizar(int $id, array $dados)
    {
        try {
            return $this->ajudaAdminRepository->atualizar($id, $dados);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - AjudaAdminService', $e);
            return null;
        }
    }

    public function alternarAtivo(int $id): bool
    {
        try {
            return $this->ajudaAdminRepository->alternarAtivo($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - alternarAtivo - AjudaAdminService', $e);
            return null;
        }
    }

    public function excluir(int $id)
    {
        try {
            return $this->ajudaAdminRepository->excluir($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - excluir - AjudaAdminService', $e);
            return null;
        }
    }
}