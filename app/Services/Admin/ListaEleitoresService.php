<?php

namespace App\Services\Admin;

use Exception;
use App\Repositories\Admin\ListaEleitoresRepository;
use App\Repositories\Front\LogRepository;
use App\Exports\ListaDeEleitoresEcxelExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ListaEleitoresService
{
    protected ListaEleitoresRepository $listaEleitoresRepository;
    protected LogRepository $logRepository;

    public function __construct(
        ListaEleitoresRepository $listaEleitoresRepository,
        LogRepository $logRepository
    ) {
        $this->listaEleitoresRepository = $listaEleitoresRepository;
        $this->logRepository = $logRepository;
    }

    public function listarTodos(array $filtros = [])
    {
        try {
            return $this->listaEleitoresRepository->listarTodos($filtros);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodos - ListaEleitoresService', $e);
            return null;
        }
    }

    public function obterDetalhes(int $id)
    {
        try {
            return $this->listaEleitoresRepository->buscarListaDeEleitor($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterDetalhes - ListaEleitoresService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $listaDeEleitores = $this->listaEleitoresRepository->listarTodosSemPaginacao();
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'lista_de_eleitores';

            $pdf = Pdf::loadView('adminListaEleitores.pdf', compact('listaDeEleitores'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - ListaEleitoresService', $e);
            return null;
        }
    }

    public function exportarExcel(?string $busca, array $campos = ['id','nome','cpf_cnpj','email','celular', 'passou_por_ajuste', 'recuperacao_senha', 'troca_senha', 'status', 'created_at', 'updated_at'], ?string $nomeArquivo = null)
    {
        try {
            $nomeArquivo = $nomeArquivo ?? 'lista_de_eleitores';
            $export = new ListaDeEleitoresEcxelExport($this->listaEleitoresRepository, $busca, $campos);
            return Excel::download($export, $nomeArquivo.'.xlsx');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - exportarExcel - ListaEleitoresService', $e);
            return null;
        }
    }
}