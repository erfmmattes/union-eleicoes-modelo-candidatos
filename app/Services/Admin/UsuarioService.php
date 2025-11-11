<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsuarioRepository;
use App\Repositories\Admin\UsuarioTelaPermissaoRepository;
use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Front\LogRepository;
use App\Mail\NovoUsuarioMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UsuarioService
{
    protected UsuarioRepository $usuarioRepository;
    protected UsuarioTelaPermissaoRepository $usuarioTelaPermissaoRepository;
    protected ConfiguracoesRepository $configuracoesRepository;
    protected LogRepository $logRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        UsuarioTelaPermissaoRepository $usuarioTelaPermissaoRepository,
        ConfiguracoesRepository $configuracoesRepository,
        LogRepository $logRepository
    ) {
        $this->usuarioRepository = $usuarioRepository;
        $this->usuarioTelaPermissaoRepository = $usuarioTelaPermissaoRepository;
        $this->configuracoesRepository = $configuracoesRepository;
        $this->logRepository = $logRepository;
    }

    public function listarUsuarios(int $logadoId)
    {
        try {
            return $this->usuarioRepository->listarTodosExceto($logadoId);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - listarUsuarios - service', $e);
            return null;
        }
    }

    public function buscarUsuario(int $id)
    {
        try {
            return $this->usuarioRepository->buscarPorId($id);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - buscarUsuario - service', $e);
            return null;
        }
    }

    public function criarUsuarioComPermissoes(array $dados)
    {
        try {
            $senha = Str::random(10);
            $permissoes = $dados['permissoes'] ?? [];
            $dadosUsuario = [
                'name' => $dados['name'],
                'email' => $dados['email'],
                'tipo_usuario' => $dados['tipo_usuario'],
                'password' => $senha,
                'status' => $dados['status'],
            ];

            $usuario = $this->usuarioRepository->criar($dadosUsuario);
            $configuracao = $this->configuracoesRepository->getFirstOrCreate();
            $assunto = 'Union Eleições - Senha do Admin';
            $remetenteEmail = 'nao-responda@unioneleicoes.com';
            $remetenteNome = 'Union Eleições';

            Mail::send('emails.novoUsuario', [
                'usuario' => $usuario,
                'senha' => $senha,
                'configuracao' => $configuracao
            ], function($message) use ($usuario, $assunto, $remetenteEmail, $remetenteNome) {
                $message->to($usuario->email)
                        ->subject($assunto)
                        ->from($remetenteEmail, $remetenteNome);
            });

            foreach ($permissoes as $telaSlug => $acoes) {
                $this->usuarioTelaPermissaoRepository->updateOrCreate(
                    $usuario->id,
                    $telaSlug,
                    [
                        'criar' => in_array('criar', $acoes),
                        'importar_eleitores' => in_array('importar_eleitores', $acoes),
                        'enviar_senha' => in_array('enviar_senha', $acoes),
                        'ver' => in_array('ver', $acoes),
                        'editar' => in_array('editar', $acoes),
                        'deletar' => in_array('deletar', $acoes),
                    ]
                );
            }

            return $usuario;

        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - criarUsuarioComPermissoes - service', $e);
            return null;
        }
    }

    public function atualizarUsuarioComPermissoes(int $id, array $dados)
    {
        try {
            $this->usuarioRepository->atualizar($id, [
                'name' => $dados['name'],
                'email' => $dados['email'],
                'tipo_usuario' => $dados['tipo_usuario'],
            ]);

            $permissoes = $dados['permissoes'] ?? [];
            $this->usuarioTelaPermissaoRepository->atualizarPermissoes($id, $permissoes);

        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - atualizarUsuarioComPermissoes - service', $e);
            return null;
        }
    }

    public function excluirUsuario(int $id)
    {
        try {
            return $this->usuarioRepository->excluir($id);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - excluirUsuario - service', $e);
            return null;
        }
    }

    public function listarTodosOsTiposDeUsuarios()
    {
        try {
            return $this->usuarioRepository->listarTodosTiposDeUsuarioExcetoAdmin();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - listarTodosOsTiposDeUsuarios - service', $e);
            return null;
        }
    }

    public function listarTodasTelas()
    {
        try {
            return $this->usuarioRepository->listarTodasAsTelas();
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - listarTodasTelas - service', $e);
            return null;
        }
    }

    public function buscarUsuarioPorId(int $id)
    {
        try {
            return $this->usuarioRepository->buscarPorId($id);
        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - buscarUsuarioPorId - service', $e);
            return null;
        }
    }

    public function obterPermissoesPorUsuario(int $id): array
    {
        try {
            $usuario = $this->usuarioRepository->buscarPorId($id);

            if (!$usuario || !$usuario->permissoes) {
                return [];
            }

            $usuarioPermissoes = [];
            foreach ($usuario->permissoes as $permissao) {
                $usuarioPermissoes[$permissao->tela_slug] = [
                    'criar' => (bool)$permissao->criar,
                    'importar_eleitores' => (bool)$permissao->importar_eleitores,
                    'enviar_senha' => (bool)$permissao->enviar_senha,
                    'ver' => (bool)$permissao->ver,
                    'editar' => (bool)$permissao->editar,
                    'deletar' => (bool)$permissao->deletar,
                ];
            }

            return $usuarioPermissoes;

        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - obterPermissoesPorUsuario - service', $e);
            return null;
        }
    }

    public function alternarStatus(int $id)
    {
        try {
            $usuario = $this->usuarioRepository->buscarPorId($id);

            if (!$usuario) {
                throw new \Exception('Usuário não encontrado.');
            }

            $novoStatus = !$usuario->status; // alterna true/false
            $this->usuarioRepository->statusAtualizar($id, ['status' => $novoStatus]);

            return $this->usuarioRepository->buscarPorId($id);

        } catch (\Exception $e) {
            $this->logRepository->criarLog('erro - alternarStatus - service', $e);
            return null;
        }
    }
}