<?php

namespace App\Services\Front;

use App\Repositories\Front\TrocarSenhaFrontRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RecuperarSenhaMail;
use Illuminate\Support\Facades\Session;
use Exception;

class TrocarSenhaFrontService
{
    protected $trocarSenhaFrontRepository;
    protected $logRepository;

    public function __construct(
        TrocarSenhaFrontRepository $trocarSenhaFrontRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->trocarSenhaFrontRepository = $trocarSenhaFrontRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosTrocarSenhaFront()
    {
        try {
            $dados = $this->trocarSenhaFrontRepository->buscarDados();
            $this->relatorioLogsEleitorRepository->criarLog(
                session('eleitor_id'),
                session('eleitor_nome'),
                'Eleitor entrou na tela trocar de senha',
                'Eleitor acessou a tela de getDadosTrocarSenhaFront para alterar sua senha.',
                request()->ip(),
                '/trocar-senha'
            );
            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosTrocarSenhaFront - TrocarSenhaFrontService', $e);
            return null;
        }
    }

    public function trocarSenha(array $dados): array
    {
        try {
            $eleitorId = session('eleitor_id');

            if (!$eleitorId) {
                return [
                    'status' => 'error',
                    'mensagem' => 'Sessão expirada. Faça login novamente.'
                ];
            }

            $eleitor = $this->trocarSenhaFrontRepository->buscarPorId($eleitorId);

            if (!$eleitor) {
                return [
                    'status' => 'error',
                    'mensagem' => 'Eleitor não encontrado.'
                ];
            }

            if (!password_verify($dados['senha_atual'], $eleitor->senha)) {
                return [
                    'status' => 'error',
                    'mensagem' => 'A senha atual está incorreta.'
                ];
            }

            $atualizado = $this->trocarSenhaFrontRepository->atualizarSenha($eleitorId, bcrypt($dados['nova_senha']));

            if ($atualizado) {
                $this->relatorioLogsEleitorRepository->criarLog(
                    session('eleitor_id'),
                    session('eleitor_nome'),
                    'Eleitor trocou a senha',
                    'Eleitor alterou a senha com sucesso pela tela de trocarSenha.',
                    request()->ip(),
                    '/trocar-senha'
                );

                return [
                    'status' => 'success',
                    'mensagem' => 'Senha atualizada com sucesso!'
                ];
            }

            return [
                'status' => 'error',
                'mensagem' => 'Falha ao atualizar a senha. Tente novamente.'
            ];

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - trocarSenha - TrocarSenhaFrontService', $e);
            return null;
        }
    }
}