<?php

namespace App\Repositories\Admin;

use App\Models\Eleitor;

class ListaEleitoresRepository
{
    protected Eleitor $eleitor;

    public function __construct(Eleitor $eleitor)
    {
        $this->eleitor = $eleitor;
    }

    public function listarTodos(array $filtros = [])
    {
        try {
            $query = $this->eleitor->query();

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

    public function buscarListaDeEleitor(int $id)
    {
        return $this->eleitor->find($id);
    }

    public function listarTodosSemPaginacao()
    {
        return Eleitor::all();
    }
}