<?php

namespace App\Services\Admin;

use App\Repositories\Admin\DeclaracaoEleicaoRepository;
use App\Repositories\Front\LogRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DeclaracaoEleicaoService
{
    protected DeclaracaoEleicaoRepository $declaracaoEleicaoRepository;
    protected LogRepository $logRepository;

    public function __construct(
        DeclaracaoEleicaoRepository $declaracaoEleicaoRepository,
        LogRepository $logRepository
    ) {
        $this->declaracaoEleicaoRepository = $declaracaoEleicaoRepository;
        $this->logRepository = $logRepository;
    }

    public function gerarDeclaracaoPdf(array $inf)
    {
        try {
            $dados = $this->declaracaoEleicaoRepository->obterDadosDeclaracao();
            $orientacao = $inf['orientacao'] ?? 'portrait';
            $nomeArquivo = !empty($inf['nome_arquivo'])
                ? $inf['nome_arquivo'] . '.pdf'
                : 'declaracao_eleicao.pdf';

            $pdf = Pdf::loadView('adminDeclaracaoDaEleicao.declaracao-eleicao', compact('dados'))
                ->setPaper('a4', $orientacao);

            return $pdf->download($nomeArquivo);

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - DeclaracaoEleicaoService', $e);
            return null;
        }
    }
}