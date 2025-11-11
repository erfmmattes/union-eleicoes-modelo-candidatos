<?php

namespace App\Repositories\Admin;

use App\Models\ListaChamadaEleitor;
use Illuminate\Support\Facades\DB;

class ListaChamadaEleitoresRepository
{
    public function listarTodos(?string $busca = null, int $perPage = 15)
    {
        $query = ListaChamadaEleitor::with('eleitor')
            ->select('lista_chamada_eleitores.*', 'eleitores.*', 'lista_chamada_eleitores.id as lista_chamada_id')
            ->leftJoin('eleitores', 'lista_chamada_eleitores.eleitor_id', '=', 'eleitores.id')
            ->orderBy('lista_chamada_eleitores.id', 'desc');

        if ($busca) {
            $busca = preg_replace('/[^a-zA-Z0-9À-ÿ\s]/u', '', trim($busca));

            $query->where(function ($q) use ($busca) {
                $q->where('eleitores.nome', 'like', "%{$busca}%")
                ->orWhere('eleitores.email', 'like', "%{$busca}%")
                ->orWhereRaw("REPLACE(REPLACE(REPLACE(eleitores.cpf_cnpj, '.', ''), '-', ''), '/', '') LIKE ?", ["%{$busca}%"]);
            });
        }

        return $query->paginate($perPage);
    }

    public function buscarListaDeChamada(int $id)
    {
        return ListaChamadaEleitor::select('lista_chamada_eleitores.*', 'eleitores.*', 'lista_chamada_eleitores.id as lista_chamada_id')
            ->leftJoin('eleitores', 'lista_chamada_eleitores.eleitor_id', '=', 'eleitores.id')
            ->where('lista_chamada_eleitores.id', $id)
            ->first();
    }

    public function listarTodosSemPaginacao()
    {
        return ListaChamadaEleitor::query()
            ->select('lista_chamada_eleitores.*', 'eleitores.*', 'lista_chamada_eleitores.id as lista_chamada_id')
            ->leftJoin('eleitores', 'lista_chamada_eleitores.eleitor_id', '=', 'eleitores.id')
            ->orderBy('lista_chamada_eleitores.id', 'desc')->get();
    }
}