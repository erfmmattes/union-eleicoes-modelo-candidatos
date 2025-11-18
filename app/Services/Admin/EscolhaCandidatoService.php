<?php

namespace App\Services\Admin;

use App\Repositories\Admin\EscolhaCandidatoRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Storage;

class EscolhaCandidatoService
{
    protected EscolhaCandidatoRepository $escolhaCandidatoRepository;
    protected LogRepository $logRepository;

    public function __construct(
        EscolhaCandidatoRepository $escolhaCandidatoRepository,
        LogRepository $logRepository
    ) {
        $this->escolhaCandidatoRepository = $escolhaCandidatoRepository;
        $this->logRepository = $logRepository;
    }

    public function listar($perPage = 10)
    {
        try {
            return $this->escolhaCandidatoRepository->paginate($perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listar - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function criar(array $data)
    {
        try {
            if (isset($data['foto_upload'])) {
                $path = $data['foto_upload']->store('escolhas', 'public');

                $data['tem_foto'] = true;
                $data['foto'] = $data['foto_upload']->getClientOriginalName();
                $data['caminho'] = $path;
            }

            return $this->escolhaCandidatoRepository->create($data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function listarTodasEtapas()
    {
        try {
            return $this->escolhaCandidatoRepository->etapasAll();
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodasEtapas - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function buscar($id)
    {
        try {
            return $this->escolhaCandidatoRepository->find($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscar - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function atualizar($id, array $data)
    {
        try {
            $escolha = $this->escolhaCandidatoRepository->find($id);

            if (isset($data['foto_upload'])) {

                // Apagar foto antiga
                if ($escolha->caminho && Storage::disk('public')->exists($escolha->caminho)) {
                    Storage::disk('public')->delete($escolha->caminho);
                }

                // Salvar nova foto
                $path = $data['foto_upload']->store('escolhas', 'public');

                $data['tem_foto'] = true;
                $data['foto'] = $data['foto_upload']->getClientOriginalName();
                $data['caminho'] = $path;
            }

            return $this->escolhaCandidatoRepository->update($id, $data);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function deletar($id)
    {
        try {
            $escolha = $this->repo->find($id);

            if ($escolha->caminho && Storage::disk('public')->exists($escolha->caminho)) {
                Storage::disk('public')->delete($escolha->caminho);
            }

            return $this->escolhaCandidatoRepository->delete($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - deletar - EscolhaCandidatoService', $e);
            return null;
        }
    }

    public function toggleStatus($id)
    {
        try {
            $item = $this->escolhaCandidatoRepository->find($id);
            $novoStatus = !$item->status;

            return $this->escolhaCandidatoRepository->update($id, ['status' => $novoStatus]);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - toggleStatus - EscolhaCandidatoService', $e);
            return null;
        }
    }
}