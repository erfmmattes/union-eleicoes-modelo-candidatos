<?php

namespace App\Repositories\Admin;

use App\Models\EscolhaCandidato;
use App\Models\EtapaCandidato;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EscolhaCandidatoRepository
{
    protected EscolhaCandidato $model;

    public function __construct(EscolhaCandidato $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('sequencia')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->model
            ->orderBy('sequencia')
            ->get();
    }

    public function etapasAll()
    {
        return EtapaCandidato::where('status', '=', '1')->orderBy('sequencia')->get();
    }

    public function find(int $id): EscolhaCandidato
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): EscolhaCandidato
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): EscolhaCandidato
    {
        $item = $this->find($id);
        $item->update($data);

        return $item;
    }

    public function delete(int $id): bool
    {
        $item = $this->find($id);
        return $item->delete();
    }
}