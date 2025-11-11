<?php

namespace App\Repositories\Admin;

use App\Models\Ajuda;

class AjudaAdminRepository
{
    protected Ajuda $model;

    public function __construct(Ajuda $model)
    {
        $this->model = $model;
    }

    public function listarComFiltro(array $filtros = [])
    {
        $query = $this->model->query();

        if (!empty($filtros['q'])) {
            $query->where('titulo', 'like', '%' . $filtros['q'] . '%')
                  ->orWhere('descricao', 'like', '%' . $filtros['q'] . '%');
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $query->where('ativo', $filtros['ativo']);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function buscarPorId(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function criar(array $dados)
    {
        return $this->model->create($dados);
    }

    public function atualizar(int $id, array $dados)
    {
        $ajuda = $this->buscarPorId($id);
        $ajuda->update($dados);
        return $ajuda;
    }

    public function alternarAtivo(int $id)
    {
        $ajuda = $this->buscarPorId($id);
        $ajuda->ativo = !$ajuda->ativo;
        $ajuda->save();
        return $ajuda->ativo;
    }

    public function excluir(int $id)
    {
        $ajuda = $this->buscarPorId($id);
        return $ajuda->delete();
    }
}