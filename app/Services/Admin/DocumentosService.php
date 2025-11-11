<?php

namespace App\Services\Admin;

use App\Repositories\Admin\DocumentosRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class DocumentosService
{
    protected DocumentosRepository $documentosRepository;

    public function __construct(
        DocumentosRepository $documentosRepository,
        LogRepository $logRepository
    ) {
        $this->documentosRepository = $documentosRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodosComFiltro(array $filtros = [])
    {
        try {
            return $this->documentosRepository->listarComFiltro($filtros);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosComFiltro - DocumentosService', $e);
            return null;
        }
    }

    public function alternarAtivo(int $id): bool
    {
        try {
            $documento = $this->buscarPorId($id);
            $documento->ativo = !$documento->ativo;

            $this->documentosRepository->atualizarStatus($documento->id, ['ativo' => $documento->ativo]);

            return $documento->ativo;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - alternarAtivo - DocumentosService', $e);
            return null;
        }
    }

    public function buscarPorId(int $id)
    {
        try {
            return $this->documentosRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarPorId - DocumentosService', $e);
            return null;
        }
    }

    public function criar(array $dados, ?UploadedFile $arquivo = null)
    {
        try {
            if ($arquivo) {
                $path = $arquivo->store('documentos', 'public');
                $dados['arquivo'] = $arquivo->getClientOriginalName();
                $dados['caminho'] = $path;
            }

            return $this->documentosRepository->criar($dados);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - DocumentosService', $e);
            return null;
        }
    }

    public function atualizar(int $id, array $dados, ?UploadedFile $arquivo = null)
    {
        try {
            if ($arquivo) {
                $path = $arquivo->store('documentos', 'public');
                $dados['arquivo'] = $arquivo->getClientOriginalName();
                $dados['caminho'] = $path;
            }

            return $this->documentosRepository->atualizar($id, $dados);
        } catch (Exception $e) {
            $this->logRepository->atualizar('erro - criar - DocumentosService', $e);
            return null;
        }
    }

    public function excluir(int $id)
    {
        try {
            $documento = $this->buscarPorId($id);
            if ($documento && $documento->caminho) {
                Storage::disk('public')->delete($documento->caminho);
            }

            return $this->documentosRepository->excluir($id);
        } catch (Exception $e) {
            $this->logRepository->atualizar('erro - excluir - DocumentosService', $e);
            return null;
        }
    }
}