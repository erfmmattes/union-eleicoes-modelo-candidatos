<?php

namespace App\Services\Admin;

use App\Repositories\Admin\PerfilRepository;
use App\Repositories\Front\LogRepository;
use Exception;

class PerfilService
{
    protected PerfilRepository $perfilRepository;
    protected LogRepository $logRepository;

    public function __construct(PerfilRepository $perfilRepository, LogRepository $logRepository)
    {
        $this->perfilRepository = $perfilRepository;
        $this->logRepository = $logRepository;
    }

    public function obterPerfil(int $id)
    {
        try {
            return $this->perfilRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterPerfil - PerfilService', $e);
            return null;
        }
    }

    public function atualizarPerfil(int $id, array $dados): array
    {
        try {
            $atualizado = $this->perfilRepository->atualizar($id, $dados);

            if (!$atualizado) {
                return ['status' => 'error', 'message' => 'Não foi possível atualizar o perfil.'];
            }

            return ['status' => 'success', 'message' => 'Perfil atualizado com sucesso!'];
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizarPerfil - PerfilService', $e);
            return null;
        }
    }
}