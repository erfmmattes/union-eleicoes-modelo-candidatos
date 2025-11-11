<?php

namespace App\Repositories\Admin;

use App\Models\Eleitor;

class VotanteRepository
{
    protected Eleitor $eleitor;

    public function __construct(Eleitor $eleitor)
    {
        $this->eleitor = $eleitor;
    }

    public function listarTodos(?string $busca, ?string $etapa, int $perPage = 15)
    {
        $query = $this->eleitor
            ->select('eleitores.*', 'votos.eleitor_id', 'votos.votado_em', 'votos.etapa')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '1')
            ->orderBy('votos.votado_em', 'desc');

        if ($busca) {
            $query->where(function ($q) use ($busca) {

                $busca = preg_replace('/[^a-zA-Z0-9À-ÿ\s]/u', '', trim($busca));

                $q->where('eleitores.nome', 'like', "%{$busca}%")
                ->orWhere('eleitores.email', 'like', "%{$busca}%")
                ->orWhere('eleitores.cpf_cnpj', 'like', "%{$busca}%");
            });
        }

        if ($etapa) {
            $query->where('votos.etapa', $etapa);
        }

        return $query->paginate($perPage);
    }

    public function buscarComVoto(int $id)
    {
        return $this->eleitor
            ->select('eleitores.*', 'votos.votado_em', 'votos.etapa')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.id', $id)
            ->first();
    }

    public function buscarPorId(int $id): ?Eleitor
    {
        return $this->eleitor->find($id);
    }

    public function listarTodosSemPaginacao($etapa)
    {
        return $this->eleitor
            ->select('eleitores.*', 'votos.votado_em', 'votos.etapa')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '1')
            ->where('votos.etapa', '=', $etapa)
            ->orderBy('votos.votado_em', 'desc')
            ->get();
    }

    public function listarTodosSemPaginacaoComBusca(?string $busca = null, ?string $etapa = null)
    {
        $query = $this->eleitor
            ->select('eleitores.*', 'votos.votado_em', 'votos.eleitor_id', 'votos.etapa')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '1')
            ->where('votos.etapa', '=', $etapa)
            ->orderBy('votos.votado_em', 'desc');

        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('eleitores.nome', 'like', "%{$busca}%")
                ->orWhere('eleitores.email', 'like', "%{$busca}%")
                ->orWhere('eleitores.cpf_cnpj', 'like', "%{$busca}%");
            });
        }

        return $query->get();
    }
}