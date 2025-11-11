<?php

namespace App\Services\Admin;

use App\Repositories\Admin\LogsRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class LogsService
{
    protected LogsRepository $logsRepository;
    
    public function __construct(
        LogsRepository $logsRepository,
        LogRepository $logRepository
    ) {
        $this->logsRepository = $logsRepository;
        $this->logRepository = $logRepository;
    }

    /**
     * Lista todos os logs
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function listarTodos(?string $search = null)
    {
        try {
            return $this->logsRepository->getAll($search);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodos - LogsService', $e);
            return null;
        }
    }

    /**
     * Exibe detalhadamente um log pelo ID
     *
     * @param int $id
     * @return bool
     */
    public function buscarPorId(int $id)
    {
        try {
            return $this->logsRepository->findById($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarPorId - LogsService', $e);
            return null;
        }
    }

    /**
     * Exclui um log pelo ID
     *
     * @param int $id
     * @return bool
     */
    public function excluir(int $id): bool
    {
        try {
            return $this->logsRepository->deleteById($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - excluir - LogsService', $e);
            return null;
        }
    }
}