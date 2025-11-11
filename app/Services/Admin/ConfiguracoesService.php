<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ConfiguracoesRepository;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Front\LogRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Exception;

class ConfiguracoesService
{
    protected ConfiguracoesRepository $configuracoesRepository;

    public function __construct(
        ConfiguracoesRepository $configuracoesRepository,
        UserRepository $userRepository,
        LogRepository $logRepository
    ) {
        $this->configuracoesRepository = $configuracoesRepository;
        $this->userRepository = $userRepository;
        $this->logRepository = $logRepository;
    }

    /**
     * Retorna a primeira configuração.
     * Se não existir, cria uma nova.
     *
     * @return \App\Models\Configuracao|null
     * @throws Exception
     */
    public function obterPrimeira()
    {
        try {
            return $this->configuracoesRepository->getFirstOrCreate();
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - obterPrimeira - ConfiguracoesService', $e);
            return null;
        }
    }

    /**
     * Atualiza os dados da configuração
     *
     * @param int $id
     * @param array $dados
     * @return \App\Models\Configuracao|null
     * @throws Exception
     */
    public function atualizar(int $id, array $dados, ?UploadedFile $logotipo = null)
    {
        try {
            $config = $this->configuracoesRepository->find($id);

            if (!$config) {
                throw new Exception('Configuração não encontrada.');
            }

            if ($logotipo) {
                if (!empty($config->caminho) && Storage::disk('public')->exists($config->caminho)) {
                    Storage::disk('public')->delete($config->caminho);
                }

                $path = $logotipo->store('logotipo-cliente', 'public');
                $dados['logotipo'] = $logotipo->getClientOriginalName();
                $dados['caminho'] = $path;
            }

            return $this->configuracoesRepository->update($id, $dados);

        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - atualizar - ConfiguracoesService', $e);
            return null;
        }
    }

    /**
     * Verifica a senha do usuário logado
     */
    public function verificarSenha($user, string $senha): bool
    {
        try {
            return $this->userRepository->verificarSenha($user, $senha);
        } catch (Exception $e) {
            $this->logRepository->criarLog('erro - verificarSenha - ConfiguracoesService', $e);
            return null;
        }
    }

    public function reiniciar()
    {
        try {
            return $this->configuracoesRepository->truncarTabelas();
        } catch (\Throwable $e) {
            $this->logRepository->criarLog('erro - reiniciar - ConfiguracoesService', $e);
            return null;
        }
    }
}