<?php

namespace App\Repositories\Admin;

use App\Models\DadosEleicaoStatus;
use App\Models\Eleitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class EleitoresAdminRepository
{
    protected DadosEleicaoStatus $dadosEleicaoStatus;
    protected Eleitor $model;

    public function __construct(DadosEleicaoStatus $dadosEleicaoStatus, Eleitor $model)
    {
        $this->dadosEleicaoStatus = $dadosEleicaoStatus;
        $this->model = $model;
    }

    public function listarComFiltro(array $filtros = [])
    {
        try {
            $query = $this->model->query();

            if (!empty($filtros['q'])) {

                $busca = preg_replace('/[^a-zA-Z0-9À-ÿ\s]/u', '', trim($filtros['q']));

                $query->where(function ($q) use ($busca) {
                    $q->where('nome', 'like', '%' . $busca . '%')
                      ->orWhere('email', 'like', '%' . $busca . '%')
                      ->orWhere('cpf_cnpj', 'like', '%' . $busca . '%');
                });
            }

            if (isset($filtros['status']) && $filtros['status'] !== '') {
                $query->where('status', (bool) $filtros['status']);
            }

            return $query->orderBy('id', 'desc')->paginate(10);
        } catch (Exception $e) {
            report($e);
            return collect();
        }
    }

    public function buscarPorId(int $id)
    {
        return $this->model->find($id);
    }

    public function criar(array $dados)
    {
        if (!empty($dados['cpf_cnpj'])) {
            $dados['cpf_cnpj'] = preg_replace('/\D/', '', $dados['cpf_cnpj']);
        }

        return $this->model->create($dados);
    }

    public function atualizar(int $id, array $dados)
    {
        $eleitor = $this->buscarPorId($id);
        if (!$eleitor) {
            return null;
        }

        if (!empty($dados['celular'])) {
            $dados['celular'] = preg_replace('/\D/', '', $dados['celular']);
        }

        if (!empty($dados['cpf_cnpj'])) {
            $dados['cpf_cnpj'] = preg_replace('/\D/', '', $dados['cpf_cnpj']);
        }

        $eleitor->passou_por_ajuste = 1;
        $eleitor->update($dados);
        return $eleitor;
    }

    public function excluir(int $id)
    {
        $eleitor = $this->buscarPorId($id);
        return $eleitor ? $eleitor->delete() : false;
    }

    public function alternarStatus(int $id): bool
    {
        $eleitor = $this->buscarPorId($id);
        if (!$eleitor) {
            return false;
        }

        $eleitor->passou_por_ajuste = 1;
        $eleitor->status = !$eleitor->status;
        $eleitor->save();

        return $eleitor->status;
    }

    public function buscaCampos(): array
    {
        $todas = Schema::getColumnListing($this->model->getTable());
        $permitidas = [
            'nome',
            'email',
            'cpf_cnpj',
            'celular',
            'status',
            'data_nascimento',
            'nome_do_representante',
            'razao_social',
            'peso_do_voto',
            'setor',
        ];
        $colunas = array_values(array_intersect($todas, $permitidas));
        sort($colunas);

        return $colunas;
    }

    public function criarEleitor(array $dados): Eleitor
    {
        return Eleitor::create($dados);
    }

    public function buscarPorCpfOuEmail(?string $cpf_cnpj, ?string $email)
    {
        return $this->model
            ->where('cpf_cnpj', $cpf_cnpj)
            ->orWhere('email', $email)
            ->first();
    }

    public function all()
    {
        return $this->model->get();
    }

    public function eleicaoBuscarPorId()
    {
        return $this->dadosEleicaoStatus->find(1);
    }

    public function alterarDadosEleicaoStatus(int $id, array $camposValores): bool
    {
        $eleicao = $this->eleicaoBuscarPorId($id);
        if (!$eleicao) {
            return false;
        }

        $camposPermitidos = ['total_eleitores', 'senhas_geradas', 'emails_enviados', 'telefones', 'sms_enviados'];
        $atualizou = false;

        foreach ($camposValores as $campo => $valor) {
            if (!in_array($campo, $camposPermitidos)) {
                continue;
            }

            $eleicao->$campo = is_null($valor) ? !$eleicao->$campo : $valor;
            $atualizou = true;
        }

        if ($atualizou) {
            $eleicao->save();
        }

        return $atualizou;
    }
}