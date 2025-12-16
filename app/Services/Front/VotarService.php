<?php

namespace App\Services\Front;

use App\Repositories\Front\VotarRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\ComprovanteRepository;
use App\Repositories\Front\LogRepository;
use App\Models\EtapaCandidato;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\AvisoTrocaDeSenhaMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;

class VotarService
{
    protected $votarRepository;
    protected $relatorioLogsEleitorRepository;
    protected $comprovanteRepository;
    protected $logRepository;

    public function __construct(
        VotarRepository $votarRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        ComprovanteRepository $comprovanteRepository,
        LogRepository $logRepository
    ) {
        $this->votarRepository = $votarRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->comprovanteRepository = $comprovanteRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosLoginEleicao()
    {
        try {
            $dados = $this->votarRepository->buscarDados();

            if (!isset($dados['configuracoes']) || !$dados['configuracoes']) {
                $dados['configuracoes'] = $this->votarRepository->obterConfiguracoes();
            }

            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosLoginEleicao - VotarService', $e);
            return null;
        }
    }

    public function listaEtapasVotar()
    {
        try {
            $listaEtapasVotacao = $this->votarRepository->listaEtapasAtivas();

            $buscaNomeEtapa = $this->votarRepository->listaEtapasAtivas();

            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');

            return $listaEtapasVotacao;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listaEtapasVotar - VotarService', $e);
            return null;
        }
    }

    public function registrarVoto(array $data)
    {
        try {
            DB::beginTransaction();

            try {

                $eleitorId = (int) $data['eleitor_id'];
                $etapaId   = (int) $data['etapa_id'];

                $etapa = EtapaCandidato::with('escolhas')->findOrFail($etapaId);

                $etapaSlug = "etapa_" . $etapa->sequencia; // etapa_1, etapa_2, ...

                $eleitorNome = Session::get('eleitor_nome');

                $this->relatorioLogsEleitorRepository->criarLog(
                    $eleitorId,
                    $eleitorNome,
                    'Eleitor votou na etapa ' . $etapa->nome,
                    'Eleitor votou na etapa ' . $etapa->nome . ' pela ação - registrarVoto',
                    request()->ip(),
                    '/votar'
                );

                // Verifica se já votou - mas NÃO lança erro, apenas segue para a próxima
                $jaVotou = $this->votarRepository->eleitorJaVotouEtapa($eleitorId, $etapaSlug);

                if (!$jaVotou) {
                    // ---- validação ----
                    if ($etapa->multipla_escolha) {
                        $escolhas = $data['escolhas'] ?? [];
                        if (!is_array($escolhas) || count($escolhas) == 0) {
                            throw ValidationException::withMessages(['escolhas' => 'Selecione ao menos uma opção.']);
                        }

                        $count = count($escolhas);
                        $min = $etapa->quantidade_minima_escolhas;
                        $max = $etapa->quantidade_maxima_escolhas;

                        if ($count < $min || $count > $max) {
                            throw ValidationException::withMessages([
                                'escolhas' => "Você deve selecionar entre {$min} e {$max} opções."
                            ]);
                        }

                    } else {
                        if (!$data['escolha'] ?? false) {
                            throw ValidationException::withMessages(['escolha' => 'Selecione uma opção.']);
                        }
                        $escolhas = [$data['escolha']];
                    }

                    // monta o JSON
                    $payload = [
                        'eleitor_id' => $eleitorId,
                        'etapa_id' => $etapaId,
                        'escolhas' => $escolhas,
                        'ip' => $data['ip'] ?? null,
                        'user_agent' => $data['user_agent'] ?? null,
                        'timestamp' => now()->toIso8601String(),
                    ];

                    $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

                    $criptografado = Crypt::encryptString($json);

                    $this->votarRepository->create([
                        'eleitor_id' => $eleitorId,
                        'voto'       => $criptografado,
                        'etapa'      => $etapaSlug,
                        'votado_em'  => now(),
                    ]);

                    $this->gerarComprovanteEtapa($eleitorId, $etapaId);
                }

                // Buscar próxima etapa
                $proxima = $this->votarRepository->buscarProximaEtapa($etapa->sequencia);

                DB::commit();

                return $proxima;

            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - registrarVoto - VotarService', $e);
            return null;
        }
    }

    public function jaVotou($eleitorId, $etapaSlug)
    {
        try {
            return $this->votarRepository->eleitorJaVotouEtapa($eleitorId, $etapaSlug);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - jaVotou - VotarService', $e);
            return null;
        }
    }

    public function jaVotouEtapa($eleitorId, $etapaId)
    {
        try {
            return DB::table('comprovantes')
                ->where('eleitor_id', $eleitorId)
                ->where('etapa_id', $etapaId)
                ->exists();
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - jaVotouEtapa - VotarService', $e);
            return null;
        }
    }

    public function gerarComprovanteFinal($eleitorId)
    {
        try {
            $eleLog = $this->votarRepository->dadosEleitorLogado();
            $dados = $this->votarRepository->buscarDados();

            return $this->votarRepository->finalizarVotacao([
                'eleitor_id'   => $eleitorId,
                'nome_votacao' => $dados['configuracoes']->nome_eleicao,
                'nome_eleitor' => $eleLog->nome,
                'cpf_cnpj'     => $eleLog->cpf_cnpj,
                'ip'           => request()->ip(),
            ]);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarComprovanteFinal - VotarService', $e);
            return null;
        }
    }

    public function gerarComprovanteEtapa($eleitorId, $etapaId)
    {
        try {
            $eleitor = $this->votarRepository->dadosEleitorLogado();
            $dados   = $this->votarRepository->buscarDados();

            return $this->votarRepository->finalizarVotacao([
                'eleitor_id'   => $eleitorId,
                'etapa_id'     => $etapaId,
                'nome_votacao' => $dados['configuracoes']->nome_eleicao,
                'nome_eleitor' => $eleitor->nome,
                'cpf_cnpj'     => $eleitor->cpf_cnpj,
                'ip'           => request()->ip(),
            ]);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarComprovanteEtapa - VotarService', $e);
            return null;
        }
    }
}