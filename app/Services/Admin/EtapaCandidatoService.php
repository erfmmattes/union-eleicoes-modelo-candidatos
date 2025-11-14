<?php

namespace App\Services\Admin;

use App\Repositories\Admin\EtapaCandidatoRepository;
use App\Repositories\Front\LogRepository;
use Exception;

class EtapaCandidatoService
{
    protected EtapaCandidatoRepository $etapaCandidatoRepository;
    protected LogRepository $logRepository;

    public function __construct(
        EtapaCandidatoRepository $etapaCandidatoRepository,
        LogRepository $logRepository
    ) {
        $this->etapaCandidatoRepository = $etapaCandidatoRepository;
        $this->logRepository = $logRepository;
    }

    public function listar($perPage = 10)
    {
        try {
            return $this->etapaCandidatoRepository->paginate($perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function buscar($id)
    {
        try {
            return $this->etapaCandidatoRepository->find($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function criar(array $data)
    {
        try {
            return $this->etapaCandidatoRepository->create($data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function atualizar($id, array $data)
    {
        try {
            return $this->etapaCandidatoRepository->update($id, $data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function deletar($id)
    {
        try {
            return $this->etapaCandidatoRepository->delete($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - deletar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function toggleStatus($id)
    {
        try {
            $etapa = $this->buscar($id);
            $etapa->status = !$etapa->status;
            $etapa->save();
            return $etapa;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - toggleStatus - EtapaCandidatoService', $e);
            return null;
        }
    }
}