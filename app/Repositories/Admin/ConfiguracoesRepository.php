<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Configuracao;
use App\Models\DadosEleicaoStatus;
use App\Models\Eleitor;
use App\Models\EleitorLogado;
use App\Models\RelatorioLogEleitor;
use App\Models\ListaChamadaEleitor;
use App\Models\Log;
use App\Models\Voto;

class ConfiguracoesRepository
{
    /**
     * Retorna a primeira configuração ou cria uma nova vazia
     */
    public function getFirstOrCreate(): Configuracao
    {
        return Configuracao::first() ?? Configuracao::create();
    }

    /**
     * Retorna a configuração pelo ID
     */
    public function find(int $id): ?Configuracao
    {
        return Configuracao::find($id);
    }

    /**
     * Atualiza a configuração pelo ID
     */
    public function update(int $id, array $data): ?Configuracao
    {
        $config = Configuracao::find($id);

        if ($config) {
            if (!empty($data['cnpj'])) {
                $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
            }
            if (!empty($data['numero_suporte_0800'])) {
                $data['numero_suporte_0800'] = preg_replace('/\D/', '', $data['numero_suporte_0800']);
            }
            if (!empty($data['numero_suporte_whatsapp'])) {
                $data['numero_suporte_whatsapp'] = preg_replace('/\D/', '', $data['numero_suporte_whatsapp']);
            }
            if (!empty($data['cpf_presidente'])) {
                $data['cpf_presidente'] = preg_replace('/\D/', '', $data['cpf_presidente']);
            }
            if (!empty($data['celular_presidente'])) {
                $data['celular_presidente'] = preg_replace('/\D/', '', $data['celular_presidente']);
            }

            if (!empty($data['cpf_mebro_1'])) {
                $data['cpf_mebro_1'] = preg_replace('/\D/', '', $data['cpf_mebro_1']);
            }
            if (!empty($data['celular_mebro_1'])) {
                $data['celular_mebro_1'] = preg_replace('/\D/', '', $data['celular_mebro_1']);
            }
            if (!empty($data['cpf_mebro_2'])) {
                $data['cpf_mebro_2'] = preg_replace('/\D/', '', $data['cpf_mebro_2']);
            }
            if (!empty($data['celular_mebro_2'])) {
                $data['celular_mebro_2'] = preg_replace('/\D/', '', $data['celular_mebro_2']);
            }
            $config->update($data);
        }

        return $config;
    }

    public function truncarTabelas()
    {
        $eleitores = Eleitor::all();
        foreach ($eleitores as $eleitor) {
            $eleitor->aceitou_os_termos = false;
            $eleitor->votou = false;
            $eleitor->ip = null;
            $eleitor->session_token_front = null;
            $eleitor->quantidade_recuperacao_senha = false;
            $eleitor->quantidade_troca_senha = false;
            $eleitor->senha = null;
            $eleitor->enviou_senha_email = false;
            $eleitor->enviou_senha_sms = false;
            $eleitor->save();
        }

        $dadosEleicaoStatus = DadosEleicaoStatus::find(1);
        $dadosEleicaoStatus->total_eleitores = false;
        $dadosEleicaoStatus->senhas_geradas = false;
        $dadosEleicaoStatus->emails_enviados = false;
        $dadosEleicaoStatus->telefones = false;
        $dadosEleicaoStatus->sms_enviados = false;
        $dadosEleicaoStatus->save();

        EleitorLogado::truncate();
        RelatorioLogEleitor::truncate();
        ListaChamadaEleitor::truncate();
        Log::truncate();
        Voto::truncate();
        return true;
    }
}