<?php

namespace App\Services\Front;

use App\Repositories\Front\ComprovanteRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarComprovanteMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ComprovanteService
{
    protected $ajudaRepository;
    protected $relatorioLogsEleitorRepository;
    protected $logRepository;

    public function __construct(
        ComprovanteRepository $comprovanteRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->comprovanteRepository = $comprovanteRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getConfiguracoesComprovante()
    {
        try {
            $dados = $this->comprovanteRepository->buscarDados();
            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getConfiguracoesComprovante - ComprovanteService', $e);
            return null;
        }
    }

    public function getComprovanteEleitor()
    {
        try {
            $comprovante = $this->comprovanteRepository->listaComprovante();

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');
            
            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor acessou o comprovante de votação',
                'Eleitor acessou o comprovante de votação pela ação - getComprovanteEleitor',
                request()->ip(),
                '/comprovante'
            );

            return $comprovante;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getComprovanteEleitor - ComprovanteService', $e);
            return null;
        }
    }

    public function receberPorEmailComprovanteEleicao()
    {
        try {
            $comprovante = $this->comprovanteRepository->getComprovanteById();
            
            if (!$comprovante) {
                throw new \Exception("Comprovante não encontrado.");
            }

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');
            
            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor enviou por e-mail o comprovante de votação',
                'Eleitor enviou por e-mail o comprovante de votação pela ação - receberPorEmailComprovanteEleicao',
                request()->ip(),
                '/comprovante'
            );

            $dadosEleitor = $this->comprovanteRepository->listaDadosEleitor();
            Mail::to($dadosEleitor->email)->send(new EnviarComprovanteMail($comprovante));
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - receberPorEmailComprovanteEleicao - ComprovanteService', $e);
            return null;
        }
    }

    public function baixarPdfComprovanteEleicao()
    {
        try {
            $comprovantes = $this->comprovanteRepository->getComprovanteById();

            if (!$comprovantes) {
                throw new \Exception("Comprovante não encontrado.");
            }

            if ($comprovantes instanceof \Illuminate\Support\Collection === false) {
                $comprovantes = collect([$comprovantes]);
            }

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');
            
            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor baixou em pdf o comprovante de votação',
                'Eleitor baixou em pdf o comprovante de votação pela ação - baixarPdfComprovanteEleicao',
                request()->ip(),
                '/comprovante'
            );

            $pdf = Pdf::loadView('comprovante.pdf', [
                'listaComprovantes' => $comprovantes
            ])->setPaper('a4', 'portrait');

            $nomeArquivo = 'comprovante_votacao_' . $comprovantes->first()->nome_eleitor . '.pdf';

            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - baixarPdfComprovanteEleicao - ComprovanteService', $e);
            return null;
        }
    }
}