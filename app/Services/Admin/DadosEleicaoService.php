<?php

namespace App\Services\Admin;

use App\Repositories\Admin\DadosEleicaoRepository;
use App\Repositories\Front\LogRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DadosEleicaoService
{
    protected DadosEleicaoRepository $dadosEleicaoRepository;
    protected LogRepository $logRepository;

    public function __construct(
        DadosEleicaoRepository $dadosEleicaoRepository,
        LogRepository $logRepository
    ) {
        $this->dadosEleicaoRepository = $dadosEleicaoRepository;
        $this->logRepository = $logRepository;
    }

    public function listarResumo()
    {
        try {
            return $this->dadosEleicaoRepository->obterResumo();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - listarResumo - DadosEleicaoService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $dados = $this->dadosEleicaoRepository->obterResumo();
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'dados_da_eleicao';

            $pdf = Pdf::loadView('adminDadosEleicao.pdf', compact('dados'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - DadosEleicaoService', $e);
            return null;
        }
    }
}