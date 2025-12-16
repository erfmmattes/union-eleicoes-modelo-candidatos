<?php

namespace App\Services\Front;

use App\Repositories\Front\LoginEleicaoRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\AvisoTrocaDeSenhaMail;
use Exception;

class LoginEleicaoService
{
    protected $loginEleicaoRepository;
    protected $logRepository;

    public function __construct(
        LoginEleicaoRepository $loginEleicaoRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->loginEleicaoRepository = $loginEleicaoRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosLoginEleicao()
    {
        try {
            $dados = $this->loginEleicaoRepository->buscarDados();

            if (!isset($dados['configuracoes']) || !$dados['configuracoes']) {
                $dados['configuracoes'] = $this->loginEleicaoRepository->obterConfiguracoes();
            }

            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosLoginEleicao - LoginEleicaoService', $e);
            return null;
        }
    }

    public function verificarCpfSenha(string $cpf, string $senha): ?object
    {
        try {
            $eleitor = $this->loginEleicaoRepository->buscarPorCpf($cpf);

            if (!$eleitor) {
                return null;
            }

            if (!Hash::check($senha, $eleitor->senha)) {
                return null;
            }

            if ($eleitor) {
                $eleitorId = $eleitor->id;
                $eleitorLogado = $this->loginEleicaoRepository->logaEleitorTabela($eleitorId);
            }

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitor->id,
                $eleitor->nome,
                'Eleitor fez login na eleição',
                'Eleitor entrou no sistema de eleição com sucesso pela ação - verificarCpfSenha',
                request()->ip(),
                '/login-eleicao'
            );

            return $eleitor;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - verificarCpfSenha - LoginEleicaoService', $e);
            return null;
        }
    }

    public function verificarSegundaEtapa(int $idEleitor, string $dataNascimento, string $celular): ?object
    {
        try {
            $eleitor = $this->loginEleicaoRepository->buscarPorId($idEleitor);

            if (!$eleitor) {
                return null;
            }

            try {
                $dataBanco = date('Y-m-d', strtotime($eleitor->data_nascimento));
                $dataInformada = date('Y-m-d', strtotime($dataNascimento));
            } catch (\Exception $e) {
                return null;
            }

            $celularBanco = preg_replace('/\D/', '', $eleitor->celular ?? '');
            $celularInformado = preg_replace('/\D/', '', $celular);

            $celularConfere = str_ends_with($celularBanco, $celularInformado) || str_ends_with($celularInformado, $celularBanco);

            if ($dataBanco !== $dataInformada || !$celularConfere) {
                return null;
            }

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitor->id,
                $eleitor->nome,
                'Eleitor fez login na eleição',
                'Eleitor entrou no sistema de eleição com sucesso pela ação - verificarSegundaEtapa',
                request()->ip(),
                '/eleicao-login/segunda-etapa'
            );

            return $eleitor;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - verificarSegundaEtapa - LoginEleicaoService', $e);
            return null;
        }
    }

    public function aceitaTermos(array $parametros): ?object
    {
        try {
            $eleitorId = Session::get('eleitor_id');
            $eleitorNome = Session::get('eleitor_nome');

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitorId,
                $eleitorNome,
                'Eleitor aceitou os termos de uso na eleição',
                'Eleitor aceitou os termos de uso na eleição pela ação - aceitaTermos',
                request()->ip(),
                '/login-eleicao/aceitar-os-termos'
            );
            return $this->loginEleicaoRepository->updateTermosDeUso($parametros);

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - aceitaTermos - LoginEleicaoService', $e);
            return null;
        }
    }

    public function trocarSenhaDepoisLogin(array $dados): array
    {
        try {
            $eleitorId = session('eleitor_id');

            if (!$eleitorId) {
                return [
                    'status' => 'error',
                    'mensagem' => 'Sessão expirada. Faça login novamente.'
                ];
            }

            $eleitor = $this->loginEleicaoRepository->buscarPorId($eleitorId);

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

            $atualizado = $this->loginEleicaoRepository->atualizarSenhaAposLogin($eleitorId, bcrypt($dados['nova_senha']));

            $atualizaStatusTrocaSenha = $this->loginEleicaoRepository->atualizarStatusTrocaSenhaPosLogin($eleitorId);

            if ($atualizado) {
                Mail::to($eleitor->email)->send(new AvisoTrocaDeSenhaMail($eleitor->nome));
                $this->relatorioLogsEleitorRepository->criarLog(
                    session('eleitor_id'),
                    session('eleitor_nome'),
                    'Eleitor trocou a senha obrigatóriamente após login',
                    'Eleitor trocuo a senha obrigatóriamente apoós fazer login.',
                    request()->ip(),
                    '/trocar-senha-apos-login'
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
            $this->logRepository->criarLog('erro - trocarSenhaDepoisLogin - LoginEleicaoService', $e);
            return null;
        }
    }

    public function buscarDadosEleitor($id)
    {
        try {
            return $this->loginEleicaoRepository->getEleitorById($id);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarDadosEleitor - LoginEleicaoService', $e);
            return null;
        }
    }

    public function deslogaEleitor()
    {
        $eleitorId = Session::get('eleitor_id');
        $eleitorNome = Session::get('eleitor_nome');

        $eleitorRemove = $this->loginEleicaoRepository->removeEleitorTabela($eleitorId);

        $this->relatorioLogsEleitorRepository->criarLog(
            $eleitorId,
            $eleitorNome,
            'Eleitor deslogou da eleição',
            'Eleitor deslogou da eleição pela ação - deslogaEleitor',
            request()->ip(),
            '/login-eleicao/logout'
        );
    }
}