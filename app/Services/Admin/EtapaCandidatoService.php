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

    public function buscarEtapasRelacionadas($id)
    {
        try {
            return $this->etapaCandidatoRepository->etapasRelacionadas($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarEtapasRelacionadas - EtapaCandidatoService', $e);
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

    public function listarTodosSetores()
    {
        return $this->etapaCandidatoRepository->setoresAll();
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
            $resultado = $this->etapaCandidatoRepository->delete($id);

            if (!$resultado) {
                return [
                    'deleted' => false,
                    'reason' => 'possui_escolhas'
                ];
            }

            return ['deleted' => true];

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - deletar - EtapaCandidatoService', $e);
            return null;
        }
    }

    public function toggleStatus($id)
    {
        try {
            $etapa = $this->buscar($id);
            if ($etapa->escolhas()->exists()) {
                return [
                    'allowed' => false,
                    'etapa' => $etapa
                ];
            }
            $etapa->status = !$etapa->status;
            $etapa->save();

            return [
                'allowed' => true,
                'etapa' => $etapa
            ];

        } catch (Exception $e) {
            $this->logRepository->criarLog(
                'erro - toggleStatus - EtapaCandidatoService',
                $e->getMessage()
            );

            return [
                'allowed' => false,
                'error' => true
            ];
        }
    }
}