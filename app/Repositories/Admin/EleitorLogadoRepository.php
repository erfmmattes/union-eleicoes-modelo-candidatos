<?php

namespace App\Repositories\Admin;

use App\Models\EleitorLogado;
use Illuminate\Support\Facades\DB;

class EleitorLogadoRepository
{
    public function listarTodos(?string $busca = null, int $perPage = 15)
    {
        $query = EleitorLogado::with('eleitor')
            ->select('eleitores_logados.*', 'eleitores.*', 'eleitores_logados.id as eleitore_logado_id', 'eleitores_logados.created_at as eleitore_logado_created_at')
            ->leftJoin('eleitores', 'eleitores_logados.eleitor_id', '=', 'eleitores.id')
            ->orderBy('eleitores_logados.id', 'desc');

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
        return EleitorLogado::select('eleitores_logados.*', 'eleitores.*', 'eleitores_logados.id as eleitore_logado_id', 'eleitores_logados.ip as eleitore_logado_ip', 'eleitores_logados.created_at as eleitore_logado_created_at')
            ->leftJoin('eleitores', 'eleitores_logados.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores_logados.id', $id)
            ->first();
    }

    public function listarTodosSemPaginacao()
    {
        return EleitorLogado::query()
            ->select('eleitores_logados.*', 'eleitores.*', 'eleitores_logados.id as eleitore_logado_id', 'eleitores_logados.ip as eleitore_logado_ip', 'eleitores_logados.created_at as eleitore_logado_created_at')
            ->leftJoin('eleitores', 'eleitores_logados.eleitor_id', '=', 'eleitores.id')
            ->orderBy('eleitores_logados.id', 'desc')->get();
    }
}