<?php

namespace App\Repositories\Admin;

use App\Models\Eleitor;

class NaoVotanteRepository
{
    protected Eleitor $eleitor;

    public function __construct(Eleitor $eleitor)
    {
        $this->eleitor = $eleitor;
    }

    public function listarTodos(?string $busca, int $perPage = 15)
    {
        $query = $this->eleitor
            ->select('eleitores.*', 'votos.eleitor_id', 'votos.votado_em')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '0')
            ->orderBy('votos.votado_em', 'desc');

        if ($busca) {
            $query->where(function ($q) use ($busca) {

                $busca = preg_replace('/[^a-zA-Z0-9À-ÿ\s]/u', '', trim($busca));
                
                $q->where('eleitores.nome', 'like', "%{$busca}%")
                ->orWhere('eleitores.email', 'like', "%{$busca}%")
                ->orWhere('eleitores.cpf_cnpj', 'like', "%{$busca}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function buscarComVoto(int $id)
    {
        return $this->eleitor
            ->select('eleitores.*', 'votos.votado_em')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.id', $id)
            ->first();
    }

    public function buscarPorId(int $id): ?Eleitor
    {
        return $this->eleitor->find($id);
    }

    public function listarTodosSemPaginacao()
    {
        return $this->eleitor
            ->select('eleitores.*', 'votos.votado_em')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '0')
            ->orderBy('votos.votado_em', 'desc')
            ->get();
    }

    public function listarTodosSemPaginacaoComBusca(?string $busca = null)
    {
        $query = $this->eleitor
            ->select('eleitores.*', 'votos.votado_em', 'votos.eleitor_id')
            ->leftJoin('votos', 'votos.eleitor_id', '=', 'eleitores.id')
            ->where('eleitores.votou', '=', '0')
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