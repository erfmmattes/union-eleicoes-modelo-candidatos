<?php

namespace App\Services\Admin;

use App\Repositories\Admin\NaoVotanteRepository;
use App\Repositories\Front\LogRepository;
use App\Exports\NaoVotanteEcxelExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class NaoVotanteService
{
    protected NaoVotanteRepository $naoVotanteRepository;
    protected LogRepository $logRepository;

    public function __construct(NaoVotanteRepository $naoVotanteRepository, LogRepository $logRepository)
    {
        $this->naoVotanteRepository = $naoVotanteRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodosComFiltro(array $filtros)
    {
        try {
            $busca = $filtros['busca'] ?? null;
            $perPage = $filtros['perPage'] ?? 10;
            return $this->naoVotanteRepository->listarTodos($busca, $perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosComFiltro - NaoVotanteService', $e);
            return null;
        }
    }

    public function obterDetalhes(int $id)
    {
        try {
            return $this->naoVotanteRepository->buscarComVoto($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterDetalhes - NaoVotanteService', $e);
            return null;
        }
    }
    
    public function buscarVotante(int $id)
    {
        try {
            return $this->naoVotanteRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarVotante - NaoVotanteService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $naoVotantes = $this->naoVotanteRepository->listarTodosSemPaginacao();
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'relatorio_nao_votantes';

            $pdf = Pdf::loadView('adminNaoVotantes.pdf', compact('naoVotantes'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - NaoVotanteService', $e);
            return null;
        }
    }

    public function exportarExcel(?string $busca, array $campos = ['id','nome','cpf_cnpj','celular','email','votou'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'relatorio_nao_votantes';
            $export = new NaoVotanteEcxelExport($this->naoVotanteRepository, $busca, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - exportarExcel - NaoVotanteService', $e);
            return null;
        }
    }
}