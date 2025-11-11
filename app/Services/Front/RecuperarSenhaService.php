<?php

namespace App\Services\Front;

use App\Repositories\Front\RecuperarSenhaRepository;
use App\Repositories\Admin\RelatorioLogsEleitorRepository;
use App\Repositories\Front\LogRepository; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RecuperarSenhaMail;
use Illuminate\Support\Facades\Session;
use Exception;

class RecuperarSenhaService
{
    protected $recuperarSenhaRepository;
    protected $logRepository;

    public function __construct(
        RecuperarSenhaRepository $recuperarSenhaRepository,
        RelatorioLogsEleitorRepository $relatorioLogsEleitorRepository,
        LogRepository $logRepository
    ) {
        $this->recuperarSenhaRepository = $recuperarSenhaRepository;
        $this->relatorioLogsEleitorRepository = $relatorioLogsEleitorRepository;
        $this->logRepository = $logRepository;
    }

    public function getDadosRecuperarSenha()
    {
        try {
            $dados = $this->recuperarSenhaRepository->buscarDados();
            return $dados;
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - getDadosRecuperarSenha - RecuperarSenhaService', $e);
            return null;
        }
    }

    public function buscarPorCpf($request)
    {
        try {
            $cpf = preg_replace('/\D/', '', $request->input('cpf'));
            $eleitor = $this->recuperarSenhaRepository->buscarPorCpf($cpf);

            $this->relatorioLogsEleitorRepository->criarLog(
                $eleitor->id,
                $eleitor->nome,
                'Eleitor requisitou recuperação de senha na eleição',
                'Eleitor requisitou recuperação de senha na eleição pela ação - buscarPorCpf',
                request()->ip(),
                '/recuperar-senha'
            );

            if (!$eleitor) {
                return back()->with('error', 'CPF não encontrado.');
            }

            session(['eleitor_id' => $eleitor->id]);

            return back()->with('email', $eleitor->email);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - buscarPorCpf - RecuperarSenhaService', $e);
            return null;
        }
    }

    public function enviarSenha($request)
    {
        try {
            $eleitorId = session('eleitor_id');

            if (!$eleitorId) {
                return back()->with('error', 'Nenhum CPF buscado. Por favor, busque o CPF primeiro.');
            }

            $eleitor = $this->recuperarSenhaRepository->buscarPorId($eleitorId);

            if (!$eleitor) {
                return back()->with('error', 'Eleitor não encontrado.');
            }

            $novaSenha = Str::random(8);
            $this->recuperarSenhaRepository->atualizarSenha($eleitor, $novaSenha);

            Mail::to($eleitor->email)->send(new RecuperarSenhaMail($novaSenha, $eleitor->nome));

            session()->forget('eleitor_id');

            return back()->with('success', 'Nova senha gerada e enviada por email!');
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - enviarSenha - service', $e);

            return back()->with('error', 'Ocorreu um erro ao enviar a nova senha. Tente novamente.');
        }
    }
}