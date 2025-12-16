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

    public function mudarStatus(int $id, int $novoStatus): int
    {
        try {
            $etapa = $this->etapaCandidatoRepository->find($id);

            if (!$etapa) {
                throw new \DomainException('Etapa não encontrada.');
            }

            if ($novoStatus === 1) {
                $existeAtiva = $this->etapaCandidatoRepository->verificaEtapaAtiva($id);

                if ($existeAtiva) {
                    throw new \DomainException('Já existe uma etapa ativa.');
                }
            }

            $this->etapaCandidatoRepository->atualizarStatus($id, $novoStatus);

            return $novoStatus;

        } catch (\DomainException $e) {
            throw $e;

        } catch (\Throwable $e) {
            $this->logRepository->criarLog(
                'erro - mudarStatus - EtapaCandidatoService',
                $e
            );

            throw new \RuntimeException('Erro interno ao alterar status.');
        }
    }
}