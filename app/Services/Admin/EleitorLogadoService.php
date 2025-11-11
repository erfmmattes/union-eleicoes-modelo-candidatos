<?php

namespace App\Services\Admin;

use Exception;
use App\Repositories\Admin\EleitorLogadoRepository;
use App\Repositories\Front\LogRepository;
use App\Exports\EleitoresLogadosEcxelExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EleitorLogadoService
{
    protected EleitorLogadoRepository $eleitorLogadoRepository;
    protected LogRepository $logRepository;

    public function __construct(
        EleitorLogadoRepository $eleitorLogadoRepository,
        LogRepository $logRepository
    ) {
        $this->eleitorLogadoRepository = $eleitorLogadoRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodos(array $filtros)
    {
        try {
            $busca = $filtros['q'] ?? null;
            $perPage = $filtros['perPage'] ?? 10;
            return $this->eleitorLogadoRepository->listarTodos($busca, $perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodos - EleitorLogadoService', $e);
            return null;
        }
    }

    public function obterDetalhes(int $id)
    {
        try {
            return $this->eleitorLogadoRepository->buscarListaDeChamada($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterDetalhes - EleitorLogadoService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $eleitoresLogados = $this->eleitorLogadoRepository->listarTodosSemPaginacao();
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'eleitores_logados';

            $pdf = Pdf::loadView('adminEleitorLogado.pdf', compact('eleitoresLogados'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - EleitorLogadoService', $e);
            return null;
        }
    }

    public function exportarExcel(?string $busca, array $campos = ['id','nome','cpf_cnpj','email','celular', 'ip', 'created_at'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'eleitores_logados';
            $export = new EleitoresLogadosEcxelExport($this->eleitorLogadoRepository, $busca, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - exportarExcel - EleitorLogadoService', $e);
            return null;
        }
    }
}