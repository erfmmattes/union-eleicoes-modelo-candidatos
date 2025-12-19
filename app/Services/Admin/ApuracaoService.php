<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ApuracaoRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Front\LogRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ApuracaoService
{
    protected ApuracaoRepository $apuracaoRepository;
    protected ConfiguracoesRepository $configuracoesRepository;
    protected LogRepository $logRepository;

    public function __construct(
        ApuracaoRepository $apuracaoRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository
    ) {
        $this->apuracaoRepository = $apuracaoRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
    }

    public function dataConfiguracao()
    {
        try {
            return $this->configuracoesRepository->getFirstOrCreate();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - dataConfiguracao - ApuracaoService', $e);
            return null;
        }
    }

    public function listarEtapas()
    {
        try {
            return \App\Models\EtapaCandidato::orderBy('nome')->get();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - listarEtapas - ApuracaoService', $e);
            return null;
        }
    }

    public function resgataEtapa(int $etapaId)
    {
        try {
            return $this->apuracaoRepository
                ->etapaResgatada($etapaId);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - resgataEtapa - ApuracaoService', $e);
            return collect();
        }
    }

    public function apuracao(int $etapaId)
    {
        try {
            return $this->apuracaoRepository
                ->apuracaoVotosPorEtapaId($etapaId);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - apuracao - ApuracaoService', $e);
            return collect();
        }
    }

    public function dadosApuracaoPdf(int $etapaId): array
    {
        try {
            $etapa = $this->apuracaoRepository->etapaResgatada($etapaId);
            $apuracao = $this->apuracaoRepository->apuracaoVotosPorEtapaId($etapaId);
            $seqEtapa = 'etapa_' . $etapa->sequencia;

            return [
                'etapa'      => $etapa,
                'apuracao'   => $apuracao,
            ];
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - dadosApuracaoPdf - ApuracaoService', $e);
            return collect();
        }
    }

    public function gerarPdfApuracao(int $etapaId)
    {
        try {
            $dados = $this->dadosApuracaoPdf($etapaId);
            $configuracao = $this->dataConfiguracao();

            $viewData = array_merge($dados, [
                'configuracao' => $configuracao,
            ]);

            return Pdf::loadView('adminApuracao.apuracaoTotalPdf', $viewData)
                ->setPaper('A4', 'portrait')
                ->download(
                    'apuracao-' . Str::slug($dados['etapa']->nome) . '.pdf'
            );
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdfApuracao - ApuracaoService', $e);
            return collect();
        }
    }

    public function votantesApuracaoPdfgerarPdf(array $params, $etapaSequencia)
    {
        try {
            $etapa = $etapaSequencia;
            $votantes = $this->apuracaoRepository->listarEleitoresEVotantesParaGerarPdf($etapa);
            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'relatorio_votantes';

            $pdf = Pdf::loadView('adminVotantes.pdf', compact('etapa', 'votantes'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - votantesApuracaoPdfgerarPdf - ApuracaoService', $e);
            return null;
        }
    }
}