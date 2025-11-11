<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ZeresimaRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Front\LogRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;

class ZeresimaService
{
    protected ZeresimaRepository $zeresimaRepository;
    protected ConfiguracoesRepository $configuracoesRepository;
    protected LogRepository $logRepository;

    public function __construct(
        ZeresimaRepository $zeresimaRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository
    ) {
        $this->zeresimaRepository = $zeresimaRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
    }

    public function verificarStatus(): array
    {
        try {
            $totalVotos = $this->zeresimaRepository->contarVotos();

            return [
                'total_votos' => $totalVotos,
                'status' => $totalVotos === 0 ? 'Zerésima válida (sem votos registrados)' : 'Zerésima inválida (já há votos)'
            ];
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - verificarStatus - ZeresimaService', $e);
            return null;
        }
    }

    public function gerarPdf(array $params)
    {
        try {
            $dados = $this->verificarStatus();
            $configuracao = $this->configuracoesRepository->find(1);

            $orientacao = $params['orientacao'] ?? 'portrait';
            $nomeArquivo = $params['nome_arquivo'] ?? 'zeresima_de_votos';

            $pdf = Pdf::loadView('adminZeresima.zeresimaPdf', compact('dados', 'configuracao'))
                ->setPaper('a4', $orientacao);

            return $pdf->download("{$nomeArquivo}.pdf");
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarPdf - ZeresimaService', $e);
            return null;
        }
    }
}