<?php

namespace App\Services\Admin;

use App\Repositories\Admin\EleitoresAdminRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository;
use App\Models\Configuracao;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarSenhaEleitorMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class EleitoresAdminService
{
    protected EleitoresAdminRepository $eleitoresAdminRepository;

    public function __construct(
        EleitoresAdminRepository $eleitoresAdminRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository
    ) {
        $this->eleitoresAdminRepository = $eleitoresAdminRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
    }

    public function listarTodosComFiltro(array $filtros = [])
    {
        try {
            return $this->eleitoresAdminRepository->listarComFiltro($filtros);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosComFiltro - EleitoresAdminService', $e);
            return null;
        }
    }

    public function buscarPorId(int $id)
    {
        try {
            return $this->eleitoresAdminRepository->buscarPorId($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarPorId - EleitoresAdminService', $e);
            return null;
        }
    }

    public function criar(array $dados)
    {
        try {
            return $this->eleitoresAdminRepository->criar($dados);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - criar - EleitoresAdminService', $e);
            return null;
        }
    }

    public function atualizar(int $id, array $dados)
    {
        try {
            $this->relatorioLogsEleitorRepository->criarLog(
                $id,
                $dados['nome'],
                'Atualização Cadastral',
                'O suporte realizou atualização cadastral do eleitor.',
                request()->ip(),
                'admin/eleitores'
            );
            return $this->eleitoresAdminRepository->atualizar($id, $dados);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - EleitoresAdminService', $e);
            return null;
        }
    }

    public function excluir(int $id)
    {
        try {
            return $this->eleitoresAdminRepository->excluir($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - excluir - EleitoresAdminService', $e);
            return null;
        }
    }

    public function alternarStatus(int $id): bool
    {
        try {
            $eleitor = $this->eleitoresAdminRepository->buscarPorId($id);
            $this->relatorioLogsEleitorRepository->criarLog(
                $id,
                $eleitor->nome,
                'Alteração do Status do Eleitor',
                'O suporte realizou a alteração de status do eleitor.',
                request()->ip(),
                'admin/eleitores'
            );
            return $this->eleitoresAdminRepository->alternarStatus($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - alternarStatus - EleitoresAdminService', $e);
            return null;
        }
    }

    public function camposEle()
    {
        try {
            $camposEleitor = $this->eleitoresAdminRepository->buscaCampos();
            return $camposEleitor;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - camposEle - EleitoresAdminService', $e);
            return null;
        }
    }

    public function lerArquivo(string $arquivoTemp, string $diretorio = 'public/eleitores'): array
    {
        $caminho = storage_path("app/{$diretorio}/{$arquivoTemp}");

        if (!file_exists($caminho)) {
            throw new \Exception('Arquivo não encontrado.');
        }

        $linhas = array_map('str_getcsv', file($caminho));
        $cabecalhos = array_shift($linhas);

        return [
            'cabecalhos' => $cabecalhos,
            'linhas' => array_slice($linhas, 0, 5),
        ];
    }

    public function processarImportacao(string $arquivoTemp, array $mapeamento): int
    {
        $caminho = storage_path("app/public/eleitores/{$arquivoTemp}");

        if (!file_exists($caminho)) {
            throw new \Exception('Arquivo temporário não encontrado.');
        }

        $linhas = array_map('str_getcsv', file($caminho));
        $cabecalhos = array_shift($linhas);

        $importados = 0;

        foreach ($linhas as $linha) {
            $dados = [];

            foreach ($mapeamento as $index => $campo) {
                if (empty($campo)) {
                    continue;
                }

                if (isset($linha[$index])) {
                    $dados[$campo] = trim($linha[$index]);
                }
            }

            if (!empty($dados)) {
                $this->eleitoresAdminRepository->criarEleitor($dados);
                $importados++;
            }
        }
        Storage::delete("app/public/eleitores/{$arquivoTemp}");

        return $importados;
    }

    public function enviarSenhasParaTodos(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                if ($eleitor->email) {
                    try {

                        $this->relatorioLogsEleitorRepository->criarLog(
                            $eleitor->id,
                            $eleitor->nome,
                            'Envio de senha para o eleitor',
                            'Foi enviado a senha por E-MAIL e SMS para o eleitor.',
                            request()->ip(),
                            'admin/eleitores'
                        );

                        $configuracao = Configuracao::find(1);
                        $rem = $configuracao->remetente_do_email;
                        if (preg_match('/^(.*?)\s*-\s*<(.+?)>$/', $rem, $matches)) {
                            $nome = trim($matches[1]);   // Union Eleições
                            $email = trim($matches[2]);  // no-reply@unioneleicoes.com.br
                        }

                        $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $assunto = $configuracao->assunto_do_email ?? 'Union Eleições - Sua senha de acesso ao sistema de eleição';
                        $remetenteEmail = $email ?? 'nao-responda@unioneleicoes.com';
                        $remetenteNome = $nome ?? 'Union Eleições';

                        Mail::send('emails.senha_eleitor', [
                            'eleitor' => $eleitor,
                            'senha' => $senha,
                            'configuracao' => $configuracao
                        ], function($message) use ($eleitor, $assunto, $remetenteEmail, $remetenteNome) {
                            $message->to($eleitor->email)
                                ->subject($assunto)
                                ->from($remetenteEmail, $remetenteNome);
                        });
                        $eleitor->enviou_senha_email = 1;
                        $eleitor->save();
                        $this->eleitoresAdminRepository->alterarDadosEleicaoStatus(1, [
                            'total_eleitores' => true,
                            'emails_enviados' => true,
                            'senhas_geradas' => true
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar e-mail para {$eleitor->email}: " . $e->getMessage());
                    }
                }

                // Enviar SMS
                if ($eleitor->celular) {
                    try {
                        // $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $this->enviarSms($eleitor->id, $eleitor->celular, "Sua senha de acesso à eleição: {$senha}");
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar SMS para {$eleitor->celular}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodos - EleitoresAdminService', $e);
            return null;
         }
    }

    public function enviarSenhasParaTodosPorEmail(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                if ($eleitor->email) {
                    try {

                        $this->relatorioLogsEleitorRepository->criarLog(
                            $eleitor->id,
                            $eleitor->nome,
                            'Envio de senha para o eleitor',
                            'Foi enviado a senha por E-MAIL e SMS para o eleitor.',
                            request()->ip(),
                            'admin/eleitores'
                        );

                        $configuracao = Configuracao::find(1);
                        $rem = $configuracao->remetente_do_email;
                        if (preg_match('/^(.*?)\s*-\s*<(.+?)>$/', $rem, $matches)) {
                            $nome = trim($matches[1]);   // Union Eleições
                            $email = trim($matches[2]);  // no-reply@unioneleicoes.com.br
                        }

                        $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $assunto = $configuracao->assunto_do_email ?? 'Union Eleições - Sua senha de acesso ao sistema de eleição';
                        $remetenteEmail = $email ?? 'nao-responda@unioneleicoes.com';
                        $remetenteNome = $nome ?? 'Union Eleições';

                        Mail::send('emails.senha_eleitor', [
                            'eleitor' => $eleitor,
                            'senha' => $senha,
                            'configuracao' => $configuracao
                        ], function($message) use ($eleitor, $assunto, $remetenteEmail, $remetenteNome) {
                            $message->to($eleitor->email)
                                ->subject($assunto)
                                ->from($remetenteEmail, $remetenteNome);
                        });
                        $eleitor->enviou_senha_email = 1;
                        $eleitor->save();
                        $this->eleitoresAdminRepository->alterarDadosEleicaoStatus(1, [
                            'total_eleitores' => true,
                            'emails_enviados' => true,
                            'senhas_geradas' => true
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar e-mail para {$eleitor->email}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodosPorEmail - EleitoresAdminService', $e);
            return null;
         }
    }

    public function enviarSenhasParaTodosPorSms(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                // Enviar SMS
                if ($eleitor->celular) {
                    try {
                        // $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $this->enviarSms($eleitor->id, $eleitor->celular, "Sua senha de acesso à eleição: {$senha}");
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar SMS para {$eleitor->celular}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodosPorSms - EleitoresAdminService', $e);
            return null;
         }
    }

    public function enviarSenhasParaTodosNaoVotantes(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores que já votaram
                if ($eleitor->votou == 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                if ($eleitor->email) {
                    try {

                        $this->relatorioLogsEleitorRepository->criarLog(
                            $eleitor->id,
                            $eleitor->nome,
                            'Envio de senha para todos eleitores não votantes por e-mail e sms',
                            'Foi enviado a senha por E-MAIL e SMS para todos eleitores não votantes.',
                            request()->ip(),
                            'admin/eleitores'
                        );

                        $configuracao = Configuracao::find(1);
                        $rem = $configuracao->remetente_do_email;
                        if (preg_match('/^(.*?)\s*-\s*<(.+?)>$/', $rem, $matches)) {
                            $nome = trim($matches[1]);   // Union Eleições
                            $email = trim($matches[2]);  // no-reply@unioneleicoes.com.br
                        }

                        $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $assunto = $configuracao->assunto_do_email ?? 'Union Eleições - Sua senha de acesso ao sistema de eleição';
                        $remetenteEmail = $email ?? 'nao-responda@unioneleicoes.com';
                        $remetenteNome = $nome ?? 'Union Eleições';

                        Mail::send('emails.senha_eleitor', [
                            'eleitor' => $eleitor,
                            'senha' => $senha,
                            'configuracao' => $configuracao
                        ], function($message) use ($eleitor, $assunto, $remetenteEmail, $remetenteNome) {
                            $message->to($eleitor->email)
                                ->subject($assunto)
                                ->from($remetenteEmail, $remetenteNome);
                        });
                        $eleitor->enviou_senha_email = 1;
                        $eleitor->save();
                        $this->eleitoresAdminRepository->alterarDadosEleicaoStatus(1, [
                            'total_eleitores' => true,
                            'emails_enviados' => true,
                            'senhas_geradas' => true
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar e-mail para {$eleitor->email}: " . $e->getMessage());
                    }
                }

                // Enviar SMS
                if ($eleitor->celular) {
                    try {
                        // $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $this->enviarSms($eleitor->id, $eleitor->celular, "Sua senha de acesso à eleição: {$senha}");
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar SMS para {$eleitor->celular}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodosNaoVotantes - EleitoresAdminService', $e);
            return null;
         }
    }

    public function enviarSenhasParaTodosNaoVotantesPorEmail(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores que já votaram
                if ($eleitor->votou == 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                if ($eleitor->email) {
                    try {

                        $this->relatorioLogsEleitorRepository->criarLog(
                            $eleitor->id,
                            $eleitor->nome,
                            'Envio de senha para o eleitor Nâo Votante Por E-mail',
                            'Foi enviado a senha por E-MAIL para o eleitor.',
                            request()->ip(),
                            'admin/eleitores'
                        );

                        $configuracao = Configuracao::find(1);
                        $rem = $configuracao->remetente_do_email;
                        if (preg_match('/^(.*?)\s*-\s*<(.+?)>$/', $rem, $matches)) {
                            $nome = trim($matches[1]);   // Union Eleições
                            $email = trim($matches[2]);  // no-reply@unioneleicoes.com.br
                        }

                        $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $assunto = $configuracao->assunto_do_email ?? 'Union Eleições - Sua senha de acesso ao sistema de eleição';
                        $remetenteEmail = $email ?? 'nao-responda@unioneleicoes.com';
                        $remetenteNome = $nome ?? 'Union Eleições';

                        Mail::send('emails.senha_eleitor', [
                            'eleitor' => $eleitor,
                            'senha' => $senha,
                            'configuracao' => $configuracao
                        ], function($message) use ($eleitor, $assunto, $remetenteEmail, $remetenteNome) {
                            $message->to($eleitor->email)
                                ->subject($assunto)
                                ->from($remetenteEmail, $remetenteNome);
                        });
                        $eleitor->enviou_senha_email = 1;
                        $eleitor->save();
                        $this->eleitoresAdminRepository->alterarDadosEleicaoStatus(1, [
                            'total_eleitores' => true,
                            'emails_enviados' => true,
                            'senhas_geradas' => true
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar e-mail para {$eleitor->email}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodosNaoVotantesPorEmail - EleitoresAdminService', $e);
            return null;
         }
    }

    public function enviarSenhasParaTodosNaoVotantesPorSms(): array
    {
        try {
            $eleitores = $this->eleitoresAdminRepository->all();
            $enviadas = 0;
            $puladas = 0;

            Cache::forget('abort_envio_senhas');

            foreach ($eleitores as $eleitor) {

                if (Cache::get('abort_envio_senhas') === true) {
                    break; 
                }

                // Pula eleitores que já votaram
                if ($eleitor->votou == 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores sem status ativo
                if ($eleitor->status != 1) {
                    $puladas++;
                    continue;
                }

                // Pula eleitores que já têm senha
                if ($eleitor->senha) {
                    $puladas++;
                    continue;
                }

                $senha = $this->gerarSenha();
                $eleitor->senha = Hash::make($senha);
                $eleitor->save();

                // Enviar SMS
                if ($eleitor->celular) {
                    try {
                        // $configuracao = $this->configuracoesRepository->getFirstOrCreate();
                        $this->enviarSms($eleitor->id, $eleitor->celular, "Sua senha de acesso à eleição: {$senha}");
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar SMS para {$eleitor->celular}: " . $e->getMessage());
                    }
                }

                $enviadas++;

                // Pequena pausa (opcional) para não sobrecarregar servidor/envios em massa
                usleep(50000); // 0.05s
            }

            // Se foi abortado, podemos retornar status parcial
            if (Cache::get('abort_envio_senhas') === true) {
                return [
                    'status' => 'abortado',
                    'enviadas' => $enviadas,
                    'puladas' => $puladas
                ];
            }

            return [
                'status' => 'concluido',
                'enviadas' => $enviadas,
                'puladas' => $puladas
            ];
         } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenhasParaTodosNaoVotantesPorSms - EleitoresAdminService', $e);
            return null;
         }
    }
    
    protected function gerarSenha(int $length = 8): string
    {
        try {
            return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - gerarSenha - EleitoresAdminService', $e);
            return null;
         }
    }

    protected function enviarSms(int $id, string $numero, string $mensagem)
    {
        try {
            // Aqui você integra com o serviço de SMS que você usa, ex:
            // Twilio, Zenvia, Mercado Pago, etc.
            // Exemplo fictício:
            // SmsService::send($numero, $mensagem);
            $eleitor = $this->eleitoresAdminRepository->buscarPorId($id);
            $eleitor->enviou_senha_sms = 1;
            $eleitor->save();
            $this->eleitoresAdminRepository->alterarDadosEleicaoStatus(1, [
                'telefones' => true,
                'sms_enviados' => true
            ]);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSms - EleitoresAdminService', $e);
            return null;
         }
    }

    public function individualEnviarSenha(int $id): array
    {
        try {
            $eleitor = $this->eleitoresAdminRepository->buscarPorId($id);
            $this->relatorioLogsEleitorRepository->criarLog(
                $id,
                $eleitor->nome,
                'Envio de senha para o eleitor',
                'Foi enviado a senha por E-MAIL e SMS para o eleitor.',
                request()->ip(),
                'admin/eleitores'
            );

            if ($eleitor['status'] === 0) {
                return ['status' => 'error', 'message' => 'Eleitor inativo, não podemos enviar senha para ele antes de contatar o cliente.'];
            }

            if (!$eleitor) {
                return ['status' => 'error', 'message' => 'Eleitor não encontrado.'];
            }

            if (empty($eleitor->email) && empty($eleitor->celular)) {
                return ['status' => 'error', 'message' => 'Eleitor sem e-mail ou celular para envio.'];
            }

            // Pula eleitores que já votaram
            if ($eleitor->votou == 1) {
                return ['status' => 'error', 'message' => 'Eleitor já votou, não é possível enviar nova senha.'];
            }

            $novaSenha = strtoupper(\Str::random(8));
            $eleitor->senha = bcrypt($novaSenha);
            $eleitor->save();

            $configuracao = $this->configuracoesRepository->getFirstOrCreate();
            $mensagem = "Olá {$eleitor->nome}, sua nova senha de acesso é: {$novaSenha}";

            try {
                // Enviar e-mail
                if (!empty($eleitor->email)) {
                    \Mail::to($eleitor->email)->send(new \App\Mail\EnviarSenhaEleitorMail($eleitor, $novaSenha, $configuracao));
                    $eleitor->enviou_senha_email = 1;
                    $eleitor->save();
                }

                // Enviar SMS (se configurado)
                if (!empty($eleitor->celular)) {
                    // \App\Helpers\SmsHelper::enviar($eleitor->celular, $mensagem);
                    $eleitor->enviou_senha_sms = 1;
                    $eleitor->save();
                }

                return [
                    'status' => 'success',
                    'message' => 'Nova senha gerada e enviada com sucesso por e-mail e SMS (quando disponível).'
                ];

            } catch (\Exception $e) {
                \Log::error('Erro ao enviar senha para eleitor '.$eleitor->id.': '.$e->getMessage());

                return [
                    'status' => 'error',
                    'message' => 'Erro ao enviar a nova senha. Tente novamente mais tarde.'
                ];
            }
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - individualEnviarSenha - EleitoresAdminService', $e);
            return null;
         }
    }
}