<?php

namespace App\Services\Admin;

use App\Repositories\Admin\VotanteRepository;
use App\Repositories\Front\LogRepository;
use App\Exports\VotanteEcxelExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class VotanteService
{
    protected VotanteRepository $votanteRepository;
    protected LogRepository $logRepository;

    public function __construct(VotanteRepository $votanteRepository, LogRepository $logRepository)
    {
        $this->votanteRepository = $votanteRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodosComFiltro(array $filtros)
    {
        try {
            $busca = $filtros['busca'] ?? null;
            $etapa = $filtros['etapa'] ?? null;
            $perPage = $filtros['perPage'] ?? 10;
            return $this->votanteRepository->listarTodos($busca, $etapa, $perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosComFiltro - VotanteService', $e);
            return null;
        }
    }

    public function obterDetalhes(int $id)
    {
        try {
            return $this->votanteRepository->buscarComVoto($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterDetalhes - VotanteService', $e);
            return null;
        }
    }
    
    public function buscarVotante(int $id)
    {
        try {
            return $this->votanteRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarVotante - VotanteService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $etapa = $params['etapa'];
            $votantes = $this->votanteRepository->listarTodosSemPaginacao($etapa);
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'relatorio_votantes';

            $pdf = Pdf::loadView('adminVotantes.pdf', compact('etapa', 'votantes'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - VotanteService', $e);
            return null;
        }
    }

    public function exportarExcel(?string $etapa, ?string $busca, array $campos = ['id','nome','cpf_cnpj','votado_em','votou','etapa','ip'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'relatorio_votantes';
            $export = new VotanteEcxelExport($this->votanteRepository, $busca, $etapa, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - exportarExcel - VotanteService', $e);
            return null;
        }
    }
}