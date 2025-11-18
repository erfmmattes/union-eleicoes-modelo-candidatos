<?php

namespace App\Services\Admin;

use App\Repositories\Admin\SetoresRepository;
use App\Repositories\Front\LogRepository;

class SetoresService
{
    protected SetoresRepository $setoresRepositor;
    protected LogRepository $logRepository;

    public function __construct(
        SetoresRepository $setoresRepositor,
        LogRepository $logRepository
    ) {
        $this->setoresRepositor = $setoresRepositor;
        $this->logRepository = $logRepository;
    }

    public function listar($perPage = 10)
    {
        try {
            return $this->setoresRepositor->paginate($perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listar - SetoresService', $e);
            return null;
        }
    }

    public function criar(array $data)
    {
        try {
            return $this->setoresRepositor->create($data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - SetoresService', $e);
            return null;
        }
    }

    public function buscar($id)
    {
        try {
            return $this->setoresRepositor->find($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscar - SetoresService', $e);
            return null;
        }
    }

    public function atualizar($id, array $data)
    {
        try {
            return $this->setoresRepositor->update($id, $data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - SetoresService', $e);
            return null;
        }
    }

    public function deletar($id)
    {
        try {
            $resultado = $this->setoresRepositor->delete($id);

            if (!$resultado) {
                return [
                    'deleted' => false,
                    'reason' => 'possui_relacionamentos'
                ];
            }

            return ['deleted' => true];

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - deletar - SetoresService', $e);
            return [
                'deleted' => false,
                'error' => true
            ];
        }
    }

    public function alterarStatus($id)
    {
        try {
            $item = $this->buscar($id);
            if ($item->etapas()->exists()) {
                return [
                    'allowed' => false,
                    'item' => $item
                ];
            }
            $item->status = !$item->status;
            $item->save();

            return [
                'allowed' => true,
                'item' => $item
            ];

        } catch (Exception $e) {
            $this->logRepository->criarLog(
                'erro - alterarStatus - SetoresService',
                $e->getMessage()
            );

            return [
                'allowed' => false,
                'error' => true
            ];
        }
    }
}