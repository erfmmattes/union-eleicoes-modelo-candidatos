<?php

namespace App\Services\Admin;

use Exception;
use App\Repositories\Admin\ListaChamadaEleitoresRepository;
use App\Repositories\Front\LogRepository;
use App\Exports\ListaDeChamadaEcxelExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ListaChamadaEleitoresService
{
    protected ListaChamadaEleitoresRepository $listaChamadaEleitoresRepository;
    protected LogRepository $logRepository;

    public function __construct(
        ListaChamadaEleitoresRepository $listaChamadaEleitoresRepository,
        LogRepository $logRepository
    ) {
        $this->listaChamadaEleitoresRepository = $listaChamadaEleitoresRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodos(array $filtros)
    {
        try {
            $busca = $filtros['q'] ?? null;
            $perPage = $filtros['perPage'] ?? 10;
            return $this->listaChamadaEleitoresRepository->listarTodos($busca, $perPage);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodos - ListaChamadaEleitoresService', $e);
            return null;
        }
    }

    public function obterDetalhes(int $id)
    {
        try {
            return $this->listaChamadaEleitoresRepository->buscarListaDeChamada($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterDetalhes - ListaChamadaEleitoresService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $listaDeChamadas = $this->listaChamadaEleitoresRepository->listarTodosSemPaginacao();
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'lista_de_chamada';

            $pdf = Pdf::loadView('adminListaChamada.pdf', compact('listaDeChamadas'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - ListaChamadaEleitoresService', $e);
            return null;
        }
    }

    public function exportarExcel(?string $busca, array $campos = ['lista_chamada_id','nome','cpf_cnpj','email','celular', 'created_at'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'lista_de_chamada';
            $export = new ListaDeChamadaEcxelExport($this->listaChamadaEleitoresRepository, $busca, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - exportarExcel - ListaChamadaEleitoresService', $e);
            return null;
        }
    }
}