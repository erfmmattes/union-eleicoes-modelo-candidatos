<?php

namespace App\Services\Front;

use App\Repositories\Front\DocumentosRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Session;
use Exception;

class DocumentosService
{
    protected $documentosRepository;
    protected $logRepository;

    public function __construct(
        DocumentosRepository $documentosRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->documentosRepository = $documentosRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosDocumentos()
    {
        try {
            $dados = $this->documentosRepository->buscarDados();

            if (!isset($dados['configuracoes']) || !$dados['configuracoes']) {
                $dados['configuracoes'] = $this->documentosRepository->obterConfiguracoes();
            }

            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosDocumentos - DocumentosService', $e);
            return null;
        }
    }

    public function listarDocumentos()
    {
        try {
            $documentos = $this->documentosRepository->getAll();

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor acessou os documentos na eleição',
                'Eleitor acessou os documentos na eleição pela ação - listarDocumentos',
                request()->ip(),
                '/documentos'
            );

            return $documentos;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarDocumentos - DocumentosService', $e);
            return null;
        }
    }

    public function buscarDocumento(int $id)
    {
        try {
            $documento = $this->documentosRepository->findById($id);
            return $documento;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarDocumento - DocumentosService', $e);
            return null;
        }
    }
}