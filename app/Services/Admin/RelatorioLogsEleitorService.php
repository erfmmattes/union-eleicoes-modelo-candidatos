<?php

namespace App\Services\Admin;

use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Front\LogRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RelatorioLogsEleitorExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class RelatorioLogsEleitorService
{
    protected RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository;
    protected ConfiguracoesRepository $configuracoesRepository;
    protected LogRepository $logRepository;

    public function __construct(
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository
    ) {
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
    }

    public function listar(?string $search = null)
    {
        try {
            return $this->relatorioLogsEleitorRepository->listar($search);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listar - RelatorioLogsEleitorService', $e);
            return null;
        }
    }

    public function buscarPorId(int $id)
    {
        try {
            return $this->relatorioLogsEleitorRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog("erro - buscarPorId ({$id}) - RelatorioLogsEleitorService", $e);
            return null;
        }
    }

    public function excluir(int $id): array
    {
        try {
            $log = $this->relatorioLogsEleitorRepository->buscarPorId($id);

            if (!$log) {
                return ['status' => 'error', 'message' => 'Log não encontrado.'];
            }

            $this->relatorioLogsEleitorRepository->excluir($id);

            return ['status' => 'success', 'message' => 'Log excluído com sucesso.'];
        } catch (Exception $e) {
            $this->logRepository->criarLog("erro - excluir ({$id}) - RelatorioLogsEleitorService", $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $logs = $this->relatorioLogsEleitorRepository->obterTodos();
            $configuracao = $this->configuracoesRepository->find(1);

            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'relatorio_logs_eleitor';

            $pdf = Pdf::loadView('adminRelatoriosPdf.relatodioDeLogsDoEleitorPdf', compact('logs', 'configuracao'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - RelatorioLogsEleitorService', $e);
            return null;
        }
    }

    public function gerarExcel(?string $busca, array $campos = ['eleitor_id','eleitor_nome','acao','mensagem','ip','pagina','created_at'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'relatorio_logs_eleitor';
            $export = new RelatorioLogsEleitorExport($this->relatorioLogsEleitorRepository, $busca, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarExcel - RelatorioLogsEleitorService', $e);
            return null;
        }
    }

    // public function gerarExcel()
    // {
    //     try {
    //         $fileName = 'relatorio_logs_eleitor.xlsx';
    //         return Excel::download(new RelatorioLogsEleitorExport, $fileName);
    //     } catch (Exception $e) {
    //         $this->logRepository->criarLog('erro - gerarExcel - RelatorioLogsEleitorService', $e);
    //         return null;
    //     }
    // }
}